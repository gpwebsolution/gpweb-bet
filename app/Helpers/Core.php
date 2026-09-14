<?php

namespace App\Helpers;

use App\Models\GameSession;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Core
{
    public static function createController(string $controllerName)
    {
        $fullControllerName = 'App\Http\Controllers\Games\\' . ucfirst($controllerName) . 'Controller';

        if (class_exists($fullControllerName)) {
            return new $fullControllerName();
        }

        throw new \Exception('Controller não encontrado: ' . $fullControllerName);
    }

    public static function generateGameHistory(User $user, string $type, float $amount, float $bet, string $nameGame, string $gameId, bool $changeBonus = false, string $provider = 'originals')
    {
        return GameSession::create([
            'user_id' => $user->id,
            'game_id' => 0,
            'bet_amount' => $type == 'loss' ? $bet : $amount,
            'result_amount' => $type == 'loss' ? 0 : $amount,
            'profit' => $type == 'loss' ? -$bet : ($amount - $bet),
            'type' => $type,
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

    public static function amountFormatDecimal($value)
    {
        $decimals = $value == floor($value) ? 0 : 2;
        return 'R$ ' . number_format($value, $decimals, ',', '.');
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
            $wallet = auth()->user()->wallet;
            $total = $wallet ? $wallet->balance + $wallet->balance_bonus : 0;
            return self::amountFormatDecimal($total);
        }

        return self::amountFormatDecimal(0.00);
    }

    public static function MakeToken(array $array): string
    {
        $output = '{"status": true';
        foreach ($array as $key => $value) {
            $output .= ',"' . $key . '": "' . $value . '"';
        }
        $output .= '}';

        return self::Encode($output);
    }

    public static function DecToken(string $token)
    {
        $json = self::Decode($token);
        if (is_numeric($json)) {
            return $token;
        }

        if (self::isJson($json)) {
            $json = str_replace('{"email', '{"status":true ,"email', $json);
            return json_decode($json, true);
        }

        return ['status' => false, 'message' => 'invalid token'];
    }

    private static function isJson(string $string): bool
    {
        json_decode($string);
        return json_last_error() == JSON_ERROR_NONE;
    }

    public static function Encode(string $texto): string
    {
        $texto = base64_encode($texto);
        $busca0 = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','x','w','y','z','0','1','2','3','4','5','6','7','8','9','='];
        $subti0 = ['8','e','9','f','b','d','h','g','j','i','m','o','k','z','l','w','4','s','r','u','t','x','v','p','6','n','7','2','1','5','q','3','y','0','c','a',''];

        $saidaSubs = '';
        for ($i = 0; $i < strlen($texto); $i++) {
            $ti = array_search($texto[$i], $busca0);
            if ($busca0[$ti] == $texto[$i]) {
                $saidaSubs .= $subti0[$ti];
            } else {
                $saidaSubs .= $texto[$i];
            }
        }

        return $saidaSubs;
    }

    public static function Decode(string $texto): string
    {
        $busca0 = ['8','e','9','f','b','d','h','g','j','i','m','o','k','z','l','w','4','s','r','u','t','x','v','p','6','n','7','2','1','5','q','3','y','0','c','a'];
        $subti0 = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','x','w','y','z','0','1','2','3','4','5','6','7','8','9'];

        $saidaSubs = '';
        for ($i = 0; $i < strlen($texto); $i++) {
            $ti = array_search($texto[$i], $busca0);
            if ($busca0[$ti] == $texto[$i]) {
                $saidaSubs .= $subti0[$ti];
            } else {
                $saidaSubs .= $texto[$i];
            }
        }

        return base64_decode($saidaSubs);
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
            return number_format($number / 1000, 1) . 'k';
        }

        if ($number >= 1000000) {
            return number_format($number / 1000000, 1) . 'M';
        }

        return $number;
    }

    public static function daysInMonth($month, $year)
    {
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }
}
