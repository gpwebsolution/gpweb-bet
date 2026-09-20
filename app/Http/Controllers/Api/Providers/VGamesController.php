<?php

namespace App\Http\Controllers\Api\Providers;

use App\Http\Controllers\Controller;
use App\Models\GameExclusive;
use App\Services\GameTokenService;
use Illuminate\Http\Request;

class VGamesController extends Controller
{
    /**
     * vGame Provider
     * Store a newly created resource in storage.
     */
    public function vgameProvider(Request $request, $token, $action)
    {
        $tokenData = GameTokenService::decode($token);
        $validEndpoints = ['session', 'icons', 'spin', 'freenum'];

        if (! in_array($action, $validEndpoints)) {
            return response()->json(['success' => false, 'message' => 'Endpoint inválido'], 404);
        }

        if (! $tokenData || empty($tokenData['status'])) {
            return response()->json(['success' => false, 'message' => 'Token inválido'], 401);
        }

        $gameUuid = $tokenData['game'] ?? '';
        $game = GameExclusive::whereActive(1)->where('uuid', $gameUuid)->first();

        if (! $game) {
            return response()->json(['success' => false, 'message' => 'Jogo não encontrado'], 404);
        }

        $controller = \Helper::createController($game->uuid);

        switch ($action) {
            case 'session':
                return $controller->session($token);
            case 'spin':
                return $controller->spin($request, $token);
            case 'freenum':
                return $controller->freenum($request, $token);
            case 'icons':
                return $controller->icons();
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->searchTerm ?? '';
        $tab = $request->tab ?? 'all';

        $games = GameExclusive::whereActive(1)
            ->when($search, fn ($q) => $q->where('name', 'like', "%$search%"))
            ->orderBy('views', 'desc')
            ->paginate(18);

        return view('web.game.list', compact('games', 'search', 'tab'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        if (auth()->check()) {
            $game = GameExclusive::whereActive(1)->where('uuid', $slug)->first();
            if (! empty($game)) {
                $game->increment('views', 1);

                $token = \Helper::MakeToken([
                    'id' => auth()->user()->id,
                    'game' => $slug,
                ]);

                return view('web.vgames.play', [
                    'game' => $game,
                    'gameUrl' => url('/vgames/'.$slug).'/',
                    'token' => $token,
                ]);
            }

            return back()->with('error', 'UUID Errado');
        }

        return back()->with('error', 'Você precisa fazer login para jogar');
    }
}
