<?php

namespace App\Helpers;

use App\Models\GameSession;
use App\Models\Setting;
use App\Models\User;
use App\Services\GameTokenService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Core
{
    public static function createController(string $controllerName)
    {
        $fullControllerName = 'App\Http\Controllers\Games\\'.ucfirst($controllerName).'Controller';

        if (class_exists($fullControllerName)) {
            return new $fullControllerName;
        }

        throw new \Exception('Controller não encontrado: '.$fullControllerName);
    }

    public static function generateGameHistory(User $user, string $type, float $amount, float $bet, string $nameGame, string $gameId, string $changeBonus = 'balance', string $provider = 'originals')
    {
        return GameSession::create([
            'user_id' => $user->id,
            'game_id' => null,
            'game_name' => $nameGame,
            'game_uuid' => $gameId,
            'bet_amount' => $type == 'loss' ? $bet : $amount,
            'result_amount' => $type == 'loss' ? 0 : $amount,
            'profit' => $type == 'loss' ? -$bet : ($amount - $bet),
            'type' => $type,
            'provider' => $provider,
            'balance_source' => $changeBonus,
            'round_id' => Str::random(40),
        ]);
    }

    public static function getCustomLayout()
    {
        if (Cache::has('customlayout')) {
            return Cache::get('customlayout');
        }

        return [
            'primary_color' => '#e63946',
            'secondary_color' => '#1a1a2e',
            'background_color' => '#0d0d0d',
            'primary_text' => '#ffffff',
            'footer_color' => '#1a1a2e',
            'primary_border_color' => 'rgba(255,255,255,0.1)',
            'expanded_layout' => true,
        ];
    }

    public static function getGatewaySelected()
    {
        return config('efi.gateway_selected', 'efi');
    }

    public static function getSetting()
    {
        if (Cache::has('setting')) {
            return Cache::get('setting');
        }

        $setting = Setting::first();
        if ($setting) {
            Cache::put('setting', $setting);
        }

        return $setting ?? (object) [
            'software_name' => 'MarioBET',
            'prefix' => 'R$',
            'currency_code' => 'BRL',
            'decimal_format' => 'dot',
            'currency_position' => 'left',
            'ngr_percent' => 0,
            'revshare_reverse' => false,
        ];
    }

    public static function getAdminUsers()
    {
        if (Cache::has('admin_users')) {
            return Cache::get('admin_users');
        }

        $admins = User::role('admin')->get();
        Cache::put('admin_users', $admins, 300);

        return $admins;
    }

    public static function amountFormatDecimal($value)
    {
        $decimals = $value == floor($value) ? 0 : 2;

        return 'R$ '.number_format($value, $decimals, ',', '.');
    }

    public static function getToken()
    {
        if (auth()->check()) {
            return self::MakeToken(['id' => auth()->id()]);
        }

        return null;
    }

    public static function getBalance()
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->relationLoaded('wallet') && $user->wallet) {
                $total = $user->wallet->balance + $user->wallet->balance_bonus;
            } else {
                $wallet = $user->wallet;
                $total = $wallet ? $wallet->balance + $wallet->balance_bonus : 0;
            }

            return self::amountFormatDecimal($total);
        }

        return self::amountFormatDecimal(0.00);
    }

    public static function MakeToken(array $array): string
    {
        $userId = $array['id'] ?? auth()->id();
        $gameUuid = $array['game'] ?? '';

        return GameTokenService::make((int) $userId, $gameUuid);
    }

    public static function DecToken(string $token)
    {
        $data = GameTokenService::decode($token);

        if ($data === null) {
            return ['status' => false, 'message' => 'invalid token'];
        }

        return $data;
    }

    public static function porcentagem_xn($porcentagem, $total)
    {
        return ($porcentagem / 100) * $total;
    }

    public static function upload($file)
    {
        $path = Storage::disk('public')->putFile('uploads', $file, 'public');
        $name = explode('uploads/', $path);

        if ($path && isset($name[1])) {
            return [
                'path' => $path,
                'name' => $name[1],
                'extension' => $file->extension(),
                'size' => $file->getSize(),
            ];
        }

        return false;
    }

    public static function formatNumber($number)
    {
        if ($number >= 1000 && $number < 1000000) {
            return number_format($number / 1000, 1).'k';
        }

        if ($number >= 1000000) {
            return number_format($number / 1000000, 1).'M';
        }

        return $number;
    }

    public static function daysInMonth($month, $year)
    {
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }
}
