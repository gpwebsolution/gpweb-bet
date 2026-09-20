<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\EfiPayment;
use App\Models\Game;
use App\Models\GameSession;
use App\Models\User;
use App\Models\Saque;
use App\Notifications\NewSaqueNotification;
use App\Traits\DateFilter;
use App\Traits\Gateways\EfiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    use DateFilter;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filterType = $request->get('type', 'all');
        $filterGame = $request->get('game_id', 'all');
        $filterDate = $request->get('date', 'all');
        $searchDate = $request->get('search_date', '');

        $query = GameSession::with('game')->where('user_id', auth()->id());

        if ($filterType !== 'all') {
            $query->where('type', $filterType);
        }
        if ($filterGame !== 'all') {
            $query->where('game_id', (int) $filterGame);
        }
        $query->applyDateFilter($filterDate, $searchDate);

        $sessions = $query->latest()->paginate(10);
        $games = Game::where('active', 1)->get();

        return view('panel.wallet.index', compact(['sessions', 'games', 'filterType', 'filterGame', 'filterDate', 'searchDate']));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function viewSaques(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $searchDate = $request->get('search_date', '');
        $query = Saque::whereUserId(auth()->id());

        $query->applyDateFilter($filter, $searchDate);

        $saques = $query->latest()->paginate(10);
        return view('panel.wallet.saque', compact(['saques', 'filter', 'searchDate']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function viewDeposits(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $searchDate = $request->get('search_date', '');
        $query = EfiPayment::whereUserId(auth()->id());

        $query->applyDateFilter($filter, $searchDate);

        $deposits = $query->latest()->paginate(10);
        return view('panel.wallet.deposits', compact(['deposits', 'filter', 'searchDate']));
    }

    /**
     * Standalone deposit form page.
     */
    public function viewDepositForm()
    {
        return view('panel.wallet.deposit_form');
    }

    /**
     * Standalone saque form page.
     */
    public function viewSaqueForm()
    {
        return view('panel.wallet.saque_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function hideBalance()
    {
        $user = auth()->user();
        $currentValue = $user->wallet->hide_balance ?? 0;
        $user->wallet()->update(['hide_balance' => $currentValue == 0 ? 1 : 0]);

        return back()->with('success', 'Visibilidade alterada com sucesso');
    }

    /**
     * @param Request $request
     * @return void
     */
    public function generateDeposit(Request $request)
    {
        $result = EfiTrait::requestQrcode($request);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result);
        }

        if ($result['status'] ?? false) {
            return redirect()->route('panel.wallet.deposit_form')->with([
                'qr_data' => $result,
            ]);
        }

        $error = $result['error'] ?? 'Erro ao gerar QR Code';
        return redirect()->route('panel.wallet.deposit_form')->with('error', $error);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestSaque(Request $request)
    {
        $setting = \Helper::getSetting();
        $rules = [
            'amount' => ['required', 'numeric', 'min:'.$setting->min_saque, 'max:'.$setting->max_saque],
            'chave_pix' => 'required',
            'tipo_chave' => 'required',
            'document' => 'required',
            'accept_terms' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if($request->accept_terms == "1") {
            if(floatval($request->amount) > floatval(auth()->user()->wallet->balance)) {
                return response()->json(['status' => false, 'error' => 'Você não tem saldo suficiente']);
            }

            $saque = Saque::create([
                'user_id' => auth()->id(),
                'amount' => \Helper::amountPrepare($request->amount),
                'type' => 'pix',
                'chave_pix' => $request->chave_pix,
                'tipo_chave' => $request->tipo_chave,
                'document' => $request->document,
                'status' => 0,
            ]);

            if($saque) {
                auth()->user()->wallet->decrement('balance', floatval($request->amount));

                $admins = \Helper::getAdminUsers();
                foreach ($admins as $admin) {
                    $admin->notify(new NewSaqueNotification(auth()->user()->name, $request->amount));
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Saque realizado com sucesso',
                ], 200);
            }
        }

        return response()->json(['status' => false, 'error' => 'Você precisa aceitar os termos']);
    }

}
