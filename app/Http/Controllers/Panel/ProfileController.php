<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Vip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $currentVip = $user->vip;
        $nextVip = null;
        $userDeposit = 0;
        $userBets = 0;
        $progressDeposit = 0;
        $progressBets = 0;

        $userDeposit = (float) DB::table('efi_payments')
            ->where('user_id', $user->id)
            ->where('status', 'paid')
            ->sum('amount');

        $userBets = (float) ($user->wallet->total_bet ?? 0);

        if ($currentVip) {
            $nextVip = Vip::where('active', true)
                ->where('level', '>', $currentVip->level)
                ->orderBy('level')
                ->first();
        } else {
            $nextVip = Vip::where('active', true)->orderBy('level')->first();
        }

        if ($nextVip) {
            $progressDeposit = $nextVip->min_deposit > 0
                ? min(100, round(($userDeposit / $nextVip->min_deposit) * 100))
                : ($nextVip->min_deposit == 0 ? 100 : 0);

            $progressBets = $nextVip->min_bets > 0
                ? min(100, round(($userBets / $nextVip->min_bets) * 100))
                : ($nextVip->min_bets == 0 ? 100 : 0);
        }

        return view('panel.profile.index', compact(
            'currentVip',
            'nextVip',
            'userDeposit',
            'userBets',
            'progressDeposit',
            'progressBets',
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->cpf_confirmed) {
            $data = [
                'name' => $request->name,
                'phone' => $request->phone,
            ];

            if ($user->update($data)) {
                return back()->with('success', 'Dados atualizados com sucesso.');
            }
            return back()->with('error', 'Erro ao atualizar dados.')->withInput();
        }

        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'cpf' => 'required|string|max:14|unique:users,cpf',
        ];

        $messages = [
            'name.required' => 'O nome é obrigatório.',
            'phone.required' => 'O telefone é obrigatório.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.unique' => 'Este CPF já está cadastrado em outra conta.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $cpf = preg_replace('/\D/', '', $request->cpf);
        if (!self::validateCpf($cpf)) {
            return back()->with('error', 'CPF inválido. Verifique os dígitos.')->withInput();
        }

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'cpf' => $cpf,
            'cpf_confirmed' => 1,
        ];

        if ($user->update($data)) {
            return back()->with('success', 'Dados atualizados com sucesso.');
        }

        return back()->with('error', 'Erro ao atualizar dados.')->withInput();
    }

    public function destroy(Request $request)
    {
        $user = auth()->user();

        $user->wallet()->delete();
        $user->delete();

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Conta excluída permanentemente.');
    }

    public static function validateCpf(string $cpf): bool
    {
        $cpf = preg_replace('/\D/', '', $cpf);
        if (strlen($cpf) !== 11) return false;
        if (preg_match('/^(\d)\1{10}$/', $cpf)) return false;
        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) return false;
        }
        return true;
    }
}
