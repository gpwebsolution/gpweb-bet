<?php

namespace App\Traits\Providers;

use App\Models\GameExclusive;
use App\Models\User;
use App\Models\Wallet;
use App\Traits\Affiliates\CommissionTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

trait PrivateGamesTrait
{
    private const SLOTINCONS = 0;

    private const ACTIVEICONS = 1;

    private const ACTIVELINES = 2;

    private const DROPLINEDATA = 3;

    private const MULTIPLYCOUNT = 4;

    private const PAYOUT = 5;

    private static function secureShuffle(array &$array): void
    {
        $count = count($array);
        for ($i = $count - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$array[$i], $array[$j]] = [$array[$j], $array[$i]];
        }
    }

    private static function isFortuneTiger(string $gameUuid): bool
    {
        $game = GameExclusive::whereActive(1)->where('uuid', $gameUuid)->first();
        return $game && stripos($game->name, 'fortune') !== false && stripos($game->name, 'tiger') !== false;
    }

    private static function generateFortuneTigerBonusGrid(string $bonusSymbol, array $heldPositions): array
    {
        $wildSymbol = 'Symbol_6';
        $newGrid = [];
        $newHeld = $heldPositions;
        $hasNewMatch = false;

        foreach (range(0, 8) as $pos) {
            if (in_array($pos, $heldPositions)) {
                $newGrid[$pos] = $bonusSymbol;
            } else {
                if (rand(1, 100) <= 45) {
                    $newGrid[$pos] = (rand(1, 100) <= 25) ? $wildSymbol : $bonusSymbol;
                    $newHeld[] = $pos;
                    $hasNewMatch = true;
                } else {
                    $others = ['Symbol_0', 'Symbol_1', 'Symbol_2', 'Symbol_3', 'Symbol_4', 'Symbol_5', 'Symbol_7', 'Symbol_8'];
                    $others = array_values(array_diff($others, [$bonusSymbol]));
                    $newGrid[$pos] = $others[array_rand($others)];
                }
            }
        }

        return [$newGrid, $newHeld, $hasNewMatch];
    }

    private static function calculateFortuneTigerBonusPayout(string $bonusSymbol, int $matchingCount, bool $isFullScreen): int
    {
        $symbolPayouts = [
            'Symbol_0' => 250,
            'Symbol_1' => 100,
            'Symbol_2' => 25,
            'Symbol_3' => 10,
            'Symbol_4' => 8,
            'Symbol_5' => 5,
            'Symbol_7' => 4,
            'Symbol_8' => 3,
        ];

        $basePayout = $symbolPayouts[$bonusSymbol] ?? 5;
        $payout = intval(($matchingCount / 3) * $basePayout);

        if ($isFullScreen) {
            $payout *= 10;
        }

        return $payout;
    }

    /**
     * @return JsonResponse
     */
    public static function SessionStructure(string $token, array $settingGame, array $iconData, array $activeLines, array $dropLine, array $betSizeList, array $multipleList, array $feature, array $featureResult = [])
    {
        try {
            $tokenOpen = \Helper::DecToken($token);
            $setting = \Helper::getSetting();

            if (isset($tokenOpen['status']) && $tokenOpen['status']) {
                $userId = (int) $tokenOpen['sub'];
                $user = User::with('wallet')->find($userId);

                if (! $user) {
                    return response()->json(['success' => false, 'message' => 'Usuário não encontrado'], 404);
                }

                $wallet = $user->wallet;

                $data = new \stdClass;
                $data->user_name = $user->name;
                $data->credit = $wallet ? ($wallet->balance + $wallet->balance_bonus) : 0;
                $data->num_line = $settingGame['num_line'];
                $data->line_num = $settingGame['line_num'];
                $data->bet_amount = $settingGame['bet_amount'];
                $data->free_num = $settingGame['free_num'];
                $data->free_total = $settingGame['free_total'];
                $data->free_amount = $settingGame['free_amount'];
                $data->free_multi = $settingGame['free_multi'];
                $data->freespin_mode = $settingGame['freespin_mode'];
                $data->multiple_list = $multipleList;
                $data->credit_line = $settingGame['credit_line'];
                $data->buy_feature = $settingGame['buy_feature'];
                $data->buy_max = $settingGame['buy_max'];
                $data->feature = $feature;
                $data->total_way = $settingGame['total_way'];
                $data->multiply = $settingGame['multiply'];
                $data->icon_data = $iconData;
                $data->active_lines = $activeLines;
                $data->drop_line = $dropLine;
                $data->currency_prefix = $setting->prefix;
                $data->currency_suffix = '';
                $data->currency_thousand = '.';
                $data->currency_decimal = ',';
                $data->bet_size_list = $betSizeList;

                $data->previous_session = $settingGame['previous_session'];
                $data->game_state = $settingGame['game_state'];
                $data->feature_result = $featureResult;

                return response()->json([
                    'data' => $data,
                    'success' => true,
                    'message' => 'Session success',
                ]);
            }

            return response()->json([], 400);
        } catch (\Throwable $e) {
            \Log::error('SessionStructure error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro interno'], 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public static function SpinStructure(string $token, array $settingGame, array $pull, array $dataLose, array $dataDemo, array $dataWin, array $dataBonus)
    {
        try {
            $tokenOpen = \Helper::DecToken($token);

            if (! (isset($tokenOpen['status']) && $tokenOpen['status'])) {
                return response()->json(['success' => false, 'message' => 'Token inválido'], 401);
            }

            $userId = (int) $tokenOpen['sub'];
            $gameUuid = $tokenOpen['game'] ?? '';

            $game = GameExclusive::whereActive(1)->where('uuid', $gameUuid)->first();
            if (! $game) {
                return response()->json(['success' => false, 'message' => 'Jogo não encontrado'], 404);
            }

            $cpl = intval($settingGame['cpl']);
            $amount = floatval($settingGame['betamount']);
            $numline = intval($settingGame['num_line']);
            $bet = $amount * $cpl * $numline;

            if ($bet <= 0) {
                return response()->json(['success' => false, 'message' => 'Aposta inválida'], 422);
            }

            $allowedBets = $game->bet_size_list ?? [];
            if (! empty($allowedBets) && ! in_array((string) $amount, array_map('strval', $allowedBets), true)) {
                return response()->json(['success' => false, 'message' => 'Valor de aposta não permitido'], 422);
            }

            $user = User::find($userId);
            if (! $user) {
                return response()->json(['error' => 'Usuário não encontrado'], 404);
            }

            $isFreeMode = session('freemode_'.$gameUuid, false);
            $freeNumRemaining = session('free_num_'.$gameUuid, 0);

            $isFortuneTiger = self::isFortuneTiger($gameUuid);
            $bonusActive = $isFortuneTiger && session('bonus_active_'.$gameUuid, false);

            $isBonusTrigger = false;
            $featureSymbol = '';
            $winAmount = 0;
            $result = null;

            if ($bonusActive) {
                $bonusSymbol = session('bonus_symbol_'.$gameUuid);
                $heldPositions = session('bonus_held_'.$gameUuid, []);
                $respinsLeft = session('bonus_respins_'.$gameUuid, 7);

                [$newGrid, $newHeld, $hasNewMatch] = self::generateFortuneTigerBonusGrid($bonusSymbol, $heldPositions);

                $isFullScreen = (count($newHeld) >= 9);
                $bonusEnds = ! $hasNewMatch || $isFullScreen || $respinsLeft <= 0;

                $payout = self::calculateFortuneTigerBonusPayout($bonusSymbol, count($newHeld), $isFullScreen);

                $result = [
                    $newGrid,
                    array_map(fn ($p) => $p + 1, $newHeld),
                    [
                        [
                            'index' => 0,
                            'name' => $bonusSymbol,
                            'combine' => count($newHeld),
                            'way_243' => 1,
                            'payout' => $payout,
                            'multiply' => 0,
                            'win_amount' => 0,
                            'active_icon' => array_map(fn ($p) => $p + 1, $newHeld),
                        ],
                    ],
                    [],
                    0,
                    $payout,
                ];

                $winAmount = $payout * $cpl * $amount;

                if ($bonusEnds) {
                    session(['bonus_active_'.$gameUuid => false]);
                    session(['bonus_held_'.$gameUuid => []]);
                    session(['bonus_respins_'.$gameUuid => 0]);
                    $bonusActive = false;
                } else {
                    session(['bonus_held_'.$gameUuid => $newHeld]);
                    session(['bonus_respins_'.$gameUuid => $respinsLeft - 1]);
                }

                $finalBalance = DB::transaction(function () use ($userId, $winAmount, $user, $game) {
                    $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->first();

                    if (! $wallet) {
                        return null;
                    }

                    if ($winAmount > 0) {
                        $wallet->increment('balance', $winAmount);
                    }

                    \Helper::generateGameHistory($user, $winAmount == 0 ? 'loss' : 'win', $winAmount, 0, $game->name, $game->uuid, 'balance', 'originals');

                    return $wallet->balance + $wallet->balance_bonus;
                });
            } else {
                $loseResults = $dataLose;
                $demoWinResults = $dataDemo;
                $winResults = $dataWin;

                self::secureShuffle($loseResults);
                self::secureShuffle($demoWinResults);
                self::secureShuffle($dataBonus);

                if ($user->is_demo_agent) {
                    $winResults = array_merge($winResults, $demoWinResults, $dataBonus);
                    $loseLength = $game->influencer_loseLength;
                    $winLength = $game->influencer_winLength;
                } else {
                    $winResults = array_merge($winResults, $dataBonus);
                    $winLength = $game->winLength;
                    $loseLength = $game->loseLength;
                }

                self::secureShuffle($winResults);

                $winResults = array_slice($winResults, 0, $winLength);
                $loseResults = array_slice($loseResults, 0, $loseLength);

                $possibleResults = array_merge($winResults, $loseResults);
                self::secureShuffle($possibleResults);
                $result = $possibleResults[0];

                $grid = $result[0];

                if ($isFortuneTiger && ! $isFreeMode) {
                    $triggerChance = rand(1, 1000);
                    if ($triggerChance <= 80) {
                        $isBonusTrigger = true;

                        $payingSymbols = ['Symbol_0', 'Symbol_1', 'Symbol_2', 'Symbol_3', 'Symbol_4', 'Symbol_5', 'Symbol_7', 'Symbol_8'];
                        $bonusSymbol = $payingSymbols[array_rand($payingSymbols)];
                        $featureSymbol = $bonusSymbol;

                        $initialCount = rand(2, 5);
                        $positions = range(0, 8);
                        self::secureShuffle($positions);
                        $heldPositions = array_slice($positions, 0, $initialCount);

                        [$bonusGrid, $finalHeld, $dummy] = self::generateFortuneTigerBonusGrid($bonusSymbol, $heldPositions);

                        $payout = self::calculateFortuneTigerBonusPayout($bonusSymbol, count($finalHeld), false);

                        $result = [
                            $bonusGrid,
                            array_map(fn ($p) => $p + 1, $finalHeld),
                            [
                                [
                                    'index' => 0,
                                    'name' => $bonusSymbol,
                                    'combine' => count($finalHeld),
                                    'way_243' => 1,
                                    'payout' => $payout,
                                    'multiply' => 0,
                                    'win_amount' => 0,
                                    'active_icon' => array_map(fn ($p) => $p + 1, $finalHeld),
                                ],
                            ],
                            [],
                            0,
                            $payout,
                        ];

                        $winAmount = $payout * $cpl * $amount;

                        session(['bonus_active_'.$gameUuid => true]);
                        session(['bonus_symbol_'.$gameUuid => $bonusSymbol]);
                        session(['bonus_held_'.$gameUuid => $finalHeld]);
                        session(['bonus_respins_'.$gameUuid => 7]);
                    }
                }

                if (! $isBonusTrigger) {
                    $uniqueSymbols = array_unique($grid);
                    $isFullScreen = (count($uniqueSymbols) === 1 && ! in_array('Symbol_6', $uniqueSymbols));

                    $payout = $result[self::PAYOUT];

                    if ($isFullScreen) {
                        $payout = $payout * 10;
                    }

                    $winAmount = $cpl * $amount * $payout;
                }

                if ($isFreeMode && $freeNumRemaining > 0) {
                    $finalBalance = DB::transaction(function () use ($userId, $winAmount, $user, $game) {
                        $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->first();

                        if (! $wallet) {
                            return null;
                        }

                        if ($winAmount > 0) {
                            $wallet->increment('balance', $winAmount);
                        }

                        \Helper::generateGameHistory($user, $winAmount == 0 ? 'loss' : 'win', $winAmount, 0, $game->name, $game->uuid, 'balance', 'originals');

                        return $wallet->balance + $wallet->balance_bonus;
                    });

                    $freeNumRemaining--;
                    if ($freeNumRemaining <= 0) {
                        session(['freemode_'.$gameUuid => false]);
                        session(['free_num_'.$gameUuid => 0]);
                        $isFreeMode = false;
                    } else {
                        session(['free_num_'.$gameUuid => $freeNumRemaining]);
                    }
                } else {
                    $finalBalance = DB::transaction(function () use ($userId, $bet, $winAmount, $user, $game) {
                        $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->first();

                        if (! $wallet) {
                            return null;
                        }

                        $totalAvailable = $wallet->balance + $wallet->balance_bonus;
                        if ($totalAvailable < $bet) {
                            return -1;
                        }

                        if ($wallet->balance >= $bet) {
                            $wallet->decrement('balance', $bet);
                        } else {
                            $fromBonus = $bet - $wallet->balance;
                            $wallet->update(['balance' => 0]);
                            $wallet->decrement('balance_bonus', $fromBonus);
                        }

                        $wallet->increment('total_bet', $bet);

                        if ($winAmount > 0) {
                            $wallet->increment('balance', $winAmount);
                        }

                        \Helper::generateGameHistory($user, $winAmount == 0 ? 'loss' : 'win', $winAmount, $bet, $game->name, $game->uuid, 'balance', 'originals');

                        CommissionTrait::processAffiliateCommission($user, $bet);

                        return $wallet->balance + $wallet->balance_bonus;
                    });
                }
            }

            if ($finalBalance === null) {
                return response()->json(['success' => false, 'message' => 'Carteira não encontrada'], 404);
            }

            if ($finalBalance === -1) {
                return response()->json(['success' => false, 'message' => 'Saldo insuficiente'], 400);
            }

            $result[self::ACTIVELINES][0]['win_amount'] = $winAmount;

            $pull['WinAmount'] = $winAmount;
            $pull['WinOnDrop'] = $winAmount;

            $pull['SlotIcons'] = $result[0];
            $pull['ActiveIcons'] = $result[1];
            $pull['ActiveLines'] = $result[2];
            $pull['DropLineData'] = $result[3];

            if ($isBonusTrigger && ! $isFreeMode) {
                $pull['HasScatter'] = true;
                $pull['CountScatter'] = 0;
            }

            $data = [
                'credit' => $finalBalance,
                'freemode' => $bonusActive || $isFreeMode,
                'jackpot' => $settingGame['jackpot'],
                'free_spin' => $isBonusTrigger ? 1 : ($bonusActive ? 1 : 0),
                'free_num' => $bonusActive ? session('bonus_respins_'.$gameUuid, 0) : ($isBonusTrigger ? 7 : $freeNumRemaining),
                'feature_symbol' => $bonusActive ? session('bonus_symbol_'.$gameUuid, '') : $featureSymbol,
                'scaler' => $settingGame['scaler'],
                'num_line' => $settingGame['num_line'],
                'cpl' => $cpl,
                'betamount' => $amount,
                'bet_amount' => $bet,
                'pull' => $pull,
            ];

            return response()->json([
                'data' => $data,
                'success' => true,
                'message' => 'Spin success',
            ]);
        } catch (\Throwable $e) {
            \Log::error('SpinStructure error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro interno'], 500);
        }
    }

    /**
     * @return JsonResponse|void
     */
    public static function FreeNumStructure($request, $token, $freeSpin, $multiples = [])
    {
        try {
            $index = $request->index ?? 0;
            $tokenOpen = \Helper::DecToken($token);

            if (! (isset($tokenOpen['status']) && $tokenOpen['status'])) {
                return response()->json(['success' => false, 'message' => 'Token inválido'], 401);
            }

            $gameUuid = $tokenOpen['game'] ?? '';
            $game = GameExclusive::whereActive(1)->where('uuid', $gameUuid)->first();

            if (! $game) {
                return response()->json(['success' => false, 'message' => 'Jogo não encontrado'], 404);
            }

            if (! isset($freeSpin[$index])) {
                return response()->json(['success' => false, 'message' => 'Rodada grátis inválida'], 422);
            }

            session(['free_num_'.$game->uuid => $freeSpin[$index]]);
            session(['free_num_last_'.$game->uuid => $freeSpin[$index]]);
            session(['multiples_'.$game->uuid => $multiples[$index] ?? 0]);
            session(['freemode_'.$game->uuid => true]);

            return response()->json([
                'success' => true,
                'data' => [
                    'free_num' => $freeSpin[$index],
                ],
                'message' => 'Change success',
            ]);
        } catch (\Throwable $e) {
            \Log::error('FreeNumStructure error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro interno'], 500);
        }
    }
}
