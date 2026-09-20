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

            // Detect scatter (Symbol_6) for bonus trigger
            $grid = $result[0];
            $scatterCount = 0;
            foreach ($grid as $symbol) {
                if ($symbol === 'Symbol_6') {
                    $scatterCount++;
                }
            }
            $isBonusTrigger = ($scatterCount >= 3);

            // Check if all 9 positions have the same symbol (full screen)
            $uniqueSymbols = array_unique($grid);
            $isFullScreen = (count($uniqueSymbols) === 1 && ! in_array('Symbol_6', $uniqueSymbols));

            $payout = $result[self::PAYOUT];

            // Apply 10x multiplier only for full screen wins (all 9 same symbol)
            if ($isFullScreen) {
                $payout = $payout * 10;
            }

            $winAmount = $cpl * $amount * $payout;

            if ($isFreeMode && $freeNumRemaining > 0) {
                // During free spins: don't deduct bet, just credit wins
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

                // Decrement free spin count
                $freeNumRemaining--;
                if ($freeNumRemaining <= 0) {
                    session(['freemode_'.$gameUuid => false]);
                    session(['free_num_'.$gameUuid => 0]);
                    $isFreeMode = false;
                } else {
                    session(['free_num_'.$gameUuid => $freeNumRemaining]);
                }
            } else {
                // Normal spin: deduct bet, credit wins
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
                $pull['CountScatter'] = $scatterCount;
            }

            $data = [
                'credit' => $finalBalance,
                'freemode' => ($isBonusTrigger && ! $isFreeMode) ? true : $isFreeMode,
                'jackpot' => $settingGame['jackpot'],
                'free_spin' => ($isBonusTrigger && ! $isFreeMode) ? 1 : 0,
                'free_num' => ($isBonusTrigger && ! $isFreeMode) ? 8 : $freeNumRemaining,
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
