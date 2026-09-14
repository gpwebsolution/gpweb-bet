<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\AffiliateHistory;
use App\Models\Commission;
use App\Models\Setting;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffiliateController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $searchDate = $request->get('search_date', '');
        $affiliateLink = url('/register?ref=' . $user->id);

        $indications = User::where('inviter', $user->id)
            ->when($searchDate, function ($q) use ($searchDate) {
                $q->whereDate('created_at', $searchDate);
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        $histories = AffiliateHistory::where('inviter', $user->id)
            ->when($searchDate, function ($q) use ($searchDate) {
                $q->whereDate('created_at', $searchDate);
            })
            ->select(
                'user_id',
                DB::raw('SUM(commission_paid) as total_commission'),
                DB::raw('SUM(CASE WHEN status = 1 THEN commission_paid ELSE 0 END) as total_received'),
                DB::raw('SUM(CASE WHEN status = 0 THEN commission_paid ELSE 0 END) as total_pending'),
                DB::raw('MAX(created_at) as last_date')
            )
            ->groupBy('user_id')
            ->orderByDesc('last_date')
            ->paginate(10);
        $histories->setPageName('affiliate_history');

        $stats = [
            'total_indicated' => User::where('inviter', $user->id)->count(),
            'total_earnings' => Wallet::where('user_id', $user->id)->value('refer_rewards') ?? 0,
            'total_bet_volume' => AffiliateHistory::where('inviter', $user->id)
                ->where('commission_type', 'revshare')
                ->sum('losses_amount'),
            'pending_commissions' => AffiliateHistory::where('inviter', $user->id)
                ->where('status', 0)
                ->sum('commission_paid'),
        ];

        $revsharePercent = floatval($user->affiliate_revenue_share);
        if ($revsharePercent <= 0) {
            $setting = Setting::first();
            $revsharePercent = floatval($setting->affiliate_default_percentage ?? 10);
        }

        $monthlyEarnings = AffiliateHistory::where('inviter', $user->id)
            ->where('commission_type', 'revshare')
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->sum('commission_paid');

        $latestCommissions = Commission::whereIn('user_id', function ($q) use ($user) {
            $q->select('id')->from('users')->where('inviter', $user->id);
        })
        ->where('status', 'pending')
        ->orderByDesc('created_at')
        ->limit(5)
        ->get();

        return view('panel.affiliates.index', compact(
            'affiliateLink',
            'indications',
            'histories',
            'stats',
            'revsharePercent',
            'monthlyEarnings',
            'latestCommissions',
            'searchDate'
        ));
    }

    public function getSaque(Request $request)
    {
        $wallet = auth()->user()->wallet;
        $comission = $wallet->refer_rewards;

        if ($comission <= 0) {
            return response()->json([
                'status' => false,
                'error' => 'Você não possui ganhos disponíveis para saque.'
            ], 400);
        }

        $wallet->increment('balance', $comission);
        $wallet->update(['refer_rewards' => 0]);

        AffiliateHistory::where('inviter', auth()->id())
            ->where('status', 0)
            ->update(['status' => 1]);

        return response()->json(['status' => true], 200);
    }

    public function joinAffiliate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        \Mail::send('emails.join-affiliate', ['email' => $request->email], function ($message) use ($request) {
            $message->from(config('mail.from.address'))
                ->to(config('mail.from.address'), 'PEDIDO DE AFILIADO')
                ->subject(config('app.name') . ' - Novo Pedido de Afiliado');
        });

        return back()->with('success', 'Seu e-mail foi enviado com sucesso. Aguarde o contato!');
    }

    public function getStats()
    {
        $user = auth()->user();

        $totalReferred = User::where('inviter', $user->id)->count();
        $earnings = Wallet::where('user_id', $user->id)->value('refer_rewards') ?? 0;
        $totalBetVolume = AffiliateHistory::where('inviter', $user->id)
            ->where('commission_type', 'revshare')
            ->sum('losses_amount');

        return response()->json([
            'total_referred' => $totalReferred,
            'earnings' => number_format($earnings, 2, ',', '.'),
            'total_bet_volume' => number_format($totalBetVolume, 2, ',', '.'),
            'earning_formatted' => 'R$ ' . number_format($earnings, 2, ',', '.'),
        ]);
    }
}
