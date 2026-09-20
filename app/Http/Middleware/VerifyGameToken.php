<?php

namespace App\Http\Middleware;

use App\Services\GameTokenService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyGameToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->route('token');

        if (empty($token)) {
            return response()->json(['success' => false, 'message' => 'Token missing'], 401);
        }

        $data = GameTokenService::decode($token);

        if ($data === null) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired token'], 401);
        }

        $request->merge([
            '_game_user_id' => (int) $data['sub'],
            '_game_uuid' => $data['game'],
        ]);

        return $next($request);
    }
}
