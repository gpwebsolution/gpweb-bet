<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Vip;
use App\Models\VipBonus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VipController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $levels = Vip::where('active', true)->orderBy('level')->get();
        $currentVip = $user->vip;
        $nextVip = null;
        $progressDeposit = 0;
        $progressBets = 0;
        $userDeposit = 0;
        $userBets = 0;
        $weeklyClaimed = false;
        $monthlyClaimed = false;

        // total accumulated ever (no reset — excess carries forward)
        $userDeposit = (float) DB::table('efi_payments')
            ->where('user_id', $user->id)
            ->where('status', 'paid')
            ->sum('amount');

        $userBets = (float) ($user->wallet->total_bet ?? 0);

        // auto-assign the highest VIP level where EITHER deposit OR bet requirement is met
        $qualified = Vip::where('active', true)
            ->where(function ($q) use ($userDeposit, $userBets) {
                $q->where('min_deposit', '<=', $userDeposit)
                  ->orWhere('min_bets', '<=', $userBets);
            })
            ->orderBy('level', 'desc')
            ->first();

        if ($qualified && (!$currentVip || $qualified->level > $currentVip->level)) {
            $oldLevel = $currentVip?->level ?? 0;
            $user->vip_id = $qualified->id;
            $user->vip_leveled_at = now();
            $user->save();
            $user->unsetRelation('vip');
            $currentVip = $user->vip;

            // create a claimable level-up reward for each newly unlocked level
            for ($lvl = $oldLevel + 1; $lvl <= $currentVip->level; $lvl++) {
                $levelVip = Vip::where('active', true)->where('level', $lvl)->first();
                if ($levelVip && $levelVip->level_up_bonus > 0) {
                    VipBonus::create([
                        'user_id' => $user->id,
                        'vip_id' => $levelVip->id,
                        'type' => 'level_up',
                        'amount' => $levelVip->level_up_bonus,
                        'claimed_at' => null,
                    ]);
                }
            }
        }

        if ($currentVip) {
            $nextVip = Vip::where('active', true)
                ->where('level', '>', $currentVip->level)
                ->orderBy('level')
                ->first();

            $weeklyClaimed = VipBonus::where('user_id', $user->id)
                ->where('type', 'weekly')
                ->where('claimed_at', '>=', Carbon::now()->subWeek())
                ->exists();

            $monthlyClaimed = VipBonus::where('user_id', $user->id)
                ->where('type', 'monthly')
                ->where('claimed_at', '>=', Carbon::now()->subMonth())
                ->exists();
        } else {
            $nextVip = Vip::where('active', true)
                ->orderBy('level')
                ->first();
        }

        if ($nextVip) {
            $progressDeposit = $nextVip->min_deposit > 0
                ? min(100, round(($userDeposit / $nextVip->min_deposit) * 100))
                : ($nextVip->min_deposit == 0 ? 100 : 0);

            $progressBets = $nextVip->min_bets > 0
                ? min(100, round(($userBets / $nextVip->min_bets) * 100))
                : ($nextVip->min_bets == 0 ? 100 : 0);
        }

        $progressOverall = (int) min($progressDeposit, $progressBets);

        $totalUsers = DB::table('users')->count();

        $pendingRewards = VipBonus::with('vip')
            ->where('user_id', $user->id)
            ->where('type', 'level_up')
            ->whereNull('claimed_at')
            ->get();

        return view('panel.vip.index', compact(
            'levels',
            'currentVip',
            'nextVip',
            'progressDeposit',
            'progressBets',
            'userDeposit',
            'userBets',
            'totalUsers',
            'weeklyClaimed',
            'monthlyClaimed',
            'pendingRewards',
        ));
    }

    public function claimWeekly(Request $request)
    {
        $user = auth()->user();
        $vip = $user->vip;

        if (!$vip) {
            return response()->json(['status' => false, 'error' => 'Você não possui nível VIP.'], 400);
        }

        if (now()->dayOfWeek !== Carbon::MONDAY) {
            return response()->json(['status' => false, 'error' => 'Bônus semanal disponível apenas às segundas-feiras.'], 400);
        }

        $already = VipBonus::where('user_id', $user->id)
            ->where('type', 'weekly')
            ->where('claimed_at', '>=', Carbon::now()->subWeek())
            ->exists();

        if ($already) {
            return response()->json(['status' => false, 'error' => 'Bônus semanal já foi retirado esta semana.'], 400);
        }

        $amount = $vip->weekly_bonus;

        if ($amount <= 0) {
            return response()->json(['status' => false, 'error' => 'Seu nível não possui bônus semanal.'], 400);
        }

        DB::beginTransaction();
        try {
            $user->wallet()->increment('balance', $amount);

            VipBonus::create([
                'user_id' => $user->id,
                'vip_id' => $vip->id,
                'type' => 'weekly',
                'amount' => $amount,
                'claimed_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Bônus semanal de R$ ' . number_format($amount, 2, ',', '.') . ' creditado!',
                'balance' => \Helper::getBalance(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'error' => 'Erro ao processar bônus.'], 500);
        }
    }

    public function claimMonthly(Request $request)
    {
        $user = auth()->user();
        $vip = $user->vip;

        if (!$vip) {
            return response()->json(['status' => false, 'error' => 'Você não possui nível VIP.'], 400);
        }

        if (now()->day !== 1) {
            return response()->json(['status' => false, 'error' => 'Bônus mensal disponível apenas no dia 1 de cada mês.'], 400);
        }

        $already = VipBonus::where('user_id', $user->id)
            ->where('type', 'monthly')
            ->where('claimed_at', '>=', Carbon::now()->subMonth())
            ->exists();

        if ($already) {
            return response()->json(['status' => false, 'error' => 'Bônus mensal já foi retirado este mês.'], 400);
        }

        $amount = $vip->monthly_bonus;

        if ($amount <= 0) {
            return response()->json(['status' => false, 'error' => 'Seu nível não possui bônus mensal.'], 400);
        }

        DB::beginTransaction();
        try {
            $user->wallet()->increment('balance', $amount);

            VipBonus::create([
                'user_id' => $user->id,
                'vip_id' => $vip->id,
                'type' => 'monthly',
                'amount' => $amount,
                'claimed_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Bônus mensal de R$ ' . number_format($amount, 2, ',', '.') . ' creditado!',
                'balance' => \Helper::getBalance(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'error' => 'Erro ao processar bônus.'], 500);
        }
    }

    public function claimLevelReward(Request $request)
    {
        $request->validate(['bonus_id' => 'required|integer|exists:vip_bonuses,id']);

        $user = auth()->user();
        $bonus = VipBonus::where('id', $request->bonus_id)
            ->where('user_id', $user->id)
            ->where('type', 'level_up')
            ->whereNull('claimed_at')
            ->first();

        if (!$bonus) {
            return response()->json(['status' => false, 'error' => 'Recompensa não encontrada ou já resgatada.'], 400);
        }

        $bonus->update(['claimed_at' => now()]);
        $user->wallet()->increment('balance', $bonus->amount);

        return response()->json([
            'status' => true,
            'message' => 'Recompensa de R$ ' . number_format($bonus->amount, 2, ',', '.') . ' resgatada!',
            'balance' => \Helper::getBalance(),
        ]);
    }
}
