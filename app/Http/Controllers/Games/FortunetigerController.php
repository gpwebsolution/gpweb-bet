<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Games\SpinData\FortuneTiger\FortuneTigerDemo;
use App\Http\Controllers\Games\SpinData\FortuneTiger\FortuneTigerIcons;
use App\Http\Controllers\Games\SpinData\FortuneTiger\FortuneTigerLose;
use App\Http\Controllers\Games\SpinData\FortuneTiger\FortuneTigerWin;
use App\Services\Games\FortuneTiger\FortuneTigerBonusEngine;
use App\Traits\Providers\PrivateGamesTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Wallet;

class FortunetigerController extends Controller
{
    use PrivateGamesTrait;

    private const GAME_UUID_KEY = 'fortune_tiger';

    /**
     * @return JsonResponse
     */
    public function session(string $token)
    {
        $settingGame = [
            'num_line' => 5,
            'line_num' => 5,
            'bet_amount' => 0.2,
            'free_num' => 0,
            'free_total' => -1,
            'free_amount' => 4,
            'free_multi' => 0,
            'freespin_mode' => 0,
            'credit_line' => 1,
            'buy_feature' => 50,
            'buy_max' => 1300,
            'total_way' => 27,
            'multiply' => 0,
            'previous_session' => false,
            'game_state' => '',
        ];

        $multipleList = [];

        $iconData = [
            'Symbol_2',
            'Symbol_1',
            'Symbol_3',
            'Symbol_4',
            'Symbol_6',
            'Symbol_5',
            'Symbol_4',
            'Symbol_4',
            'Symbol_4',
        ];

        $activeLines = [
            [
                'name' => 'Symbol_4',
                'index' => 3,
                'payout' => 10,
                'combine' => 3,
                'way_243' => 1,
                'multiply' => 0,
                'win_amount' => 2,
                'active_icon' => [
                    7,
                    8,
                    9,
                ],
            ],
        ];

        $dropLine = [];
        $betSizeList = [
            '0.2',
            '2',
            '20',
            '100',
        ];

        $feature = [
            'bigwin' => [
                ['Big Win', 15],
                ['Super Win', 25],
                ['Mega Win', 50],
                ['Super Mega Win', 100],
            ],
        ];

        $featureResult = [];

        return self::SessionStructure($token, $settingGame, $iconData, $activeLines, $dropLine, $betSizeList, $multipleList, $feature, $featureResult);
    }

    /**
     * @return JsonResponse
     */
    public function spin(Request $request, $token)
    {
        $settingGame = [
            'cpl' => $request->cpl,
            'betamount' => $request->betamount,
            'num_line' => $request->numline,
            'jackpot' => 0,
            'free_spin' => 0,
            'free_num' => 0,
            'scaler' => 0,
            'freemode' => false,
        ];

        $pull = [
            'TotalWay' => 27,
            'FreeSpin' => 0,
            'LastMultiply' => 0,
            'WildFixedIcons' => [],
            'HasJackpot' => false,
            'HasScatter' => false,
            'CountScatter' => 0,
            'WildColumIcon' => '',
            'MultipyScatter' => 0,
            'MultiplyCount' => 2,
            'WinLogs' => [],
            'DropLine' => 3,
            'MultipleList' => [],
        ];

        $bonusHook = function (int $userId, string $gameUuid, bool $isFreeMode) {
            $engine = new FortuneTigerBonusEngine($userId, $gameUuid);

            if ($engine->isActive()) {
                $state = $engine->getState();
                $bonusSymbol = $state['symbol'] ?? '';

                $respinResult = $engine->processRespin();

                $grid = $respinResult['grid'];
                $held = $respinResult['held'];
                $payout = $respinResult['payout'];
                $matchingCount = $respinResult['matching_count'];
                $bonusEnds = $respinResult['ends'];
                $respinsLeft = $respinResult['respins_left'];

                $activeLines = [];
                if ($matchingCount > 0) {
                    $activeLines[] = [
                        'index' => 0,
                        'name' => $bonusSymbol,
                        'combine' => $matchingCount,
                        'way_243' => 1,
                        'payout' => $payout,
                        'multiply' => 0,
                        'win_amount' => 0,
                        'active_icon' => array_map(fn ($p) => $p + 1, $held),
                    ];
                }

                $result = [
                    $grid,
                    array_map(fn ($p) => $p + 1, $held),
                    $activeLines,
                    [],
                    0,
                    $payout,
                ];

                return [
                    'handled' => true,
                    'result' => $result,
                    'feature_symbol' => $bonusSymbol,
                    'bonus_active' => ! $bonusEnds,
                    'respins_left' => $respinsLeft,
                    'deduct_bet' => false,
                ];
            }

            if (! $isFreeMode && $engine->shouldTrigger()) {
                $bonusSymbol = $engine->trigger();
                $state = $engine->getState();

                $held = $state['held'];
                $initialCount = count($held);
                $grid = [];
                $others = array_values(array_diff(['Symbol_0', 'Symbol_1', 'Symbol_2', 'Symbol_3', 'Symbol_4', 'Symbol_5', 'Symbol_7', 'Symbol_8'], [$bonusSymbol]));

                foreach (range(0, 8) as $pos) {
                    if (in_array($pos, $held, true)) {
                        $isWild = random_int(1, 100) <= 25;
                        $grid[$pos] = $isWild ? 'Symbol_6' : $bonusSymbol;
                    } else {
                        $grid[$pos] = $others[random_int(0, count($others) - 1)];
                    }
                }

                $basePayout = FortuneTigerBonusEngine::getPaytable()[$bonusSymbol] ?? 0;
                $payout = intval(($initialCount / 3) * $basePayout);

                $result = [
                    $grid,
                    array_map(fn ($p) => $p + 1, $held),
                    [
                        [
                            'index' => 0,
                            'name' => $bonusSymbol,
                            'combine' => $initialCount,
                            'way_243' => 1,
                            'payout' => $payout,
                            'multiply' => 0,
                            'win_amount' => 0,
                            'active_icon' => array_map(fn ($p) => $p + 1, $held),
                        ],
                    ],
                    [],
                    0,
                    $payout,
                ];

                return [
                    'handled' => true,
                    'result' => $result,
                    'feature_symbol' => $bonusSymbol,
                    'bonus_active' => true,
                    'respins_left' => 7,
                    'deduct_bet' => true,
                ];
            }

            return null;
        };

        return self::SpinStructure($token, $settingGame, $pull, FortuneTigerLose::getLose(), FortuneTigerDemo::getDemo(), FortuneTigerWin::getWin(), [], $bonusHook);
    }

    /**
     * Freenum
     */
    public function freenum(Request $request, $token)
    {
        $freeSpin = [
            0 => 8,
            1 => 7,
            2 => 6,
            3 => 5,
            4 => 4,
            5 => 3,
            6 => 2,
            7 => 1,
        ];

        return self::FreeNumStructure($request, $token, $freeSpin);
    }

    /**
     * Get Icons
     */
    public function icons()
    {
        return FortuneTigerIcons::getIcons();
    }
}
