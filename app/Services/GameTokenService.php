<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Str;

class GameTokenService
{
    private const ALGORITHM = 'HS256';

    private const EXPIRY_HOURS = 4;

    private static function getSigningKey(): string
    {
        $key = config('app.game_token_key');
        if (empty($key)) {
            $key = hash('sha256', config('app.key', 'fallback-key'));
            config(['app.game_token_key' => $key]);
        }

        return $key;
    }

    public static function make(int $userId, string $gameUuid): string
    {
        $now = now();
        $payload = [
            'iss' => config('app.url'),
            'sub' => $userId,
            'game' => $gameUuid,
            'jti' => Str::random(32),
            'iat' => $now->timestamp,
            'exp' => $now->addHours(self::EXPIRY_HOURS)->timestamp,
            'status' => true,
        ];

        return JWT::encode($payload, self::getSigningKey(), self::ALGORITHM);
    }

    public static function decode(string $token): ?array
    {
        try {
            $decoded = JWT::decode($token, new Key(self::getSigningKey(), self::ALGORITHM));

            $data = (array) $decoded;

            if (! isset($data['status']) || $data['status'] !== true) {
                return null;
            }

            return $data;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getUserId(string $token): ?int
    {
        $data = self::decode($token);

        return $data ? (int) $data['sub'] : null;
    }

    public static function getGameUuid(string $token): ?string
    {
        $data = self::decode($token);

        return $data ? ($data['game'] ?? null) : null;
    }
}
