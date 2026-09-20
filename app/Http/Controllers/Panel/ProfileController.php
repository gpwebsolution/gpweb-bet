<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Helpers\CpfHelper;
use App\Services\VipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $vipData = VipService::resolveForUser($user);

        return view('panel.profile.index', $vipData);
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
        if (!CpfHelper::validate($cpf)) {
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
}
