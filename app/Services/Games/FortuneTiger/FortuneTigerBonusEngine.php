<?php

namespace App\Services\Games\FortuneTiger;

use Illuminate\Support\Facades\Cache;

class FortuneTigerBonusEngine
{
    private const WILD_SYMBOL = 'Symbol_6';

    private const PAYTABLE = [
        'Symbol_0' => 250,
        'Symbol_1' => 100,
        'Symbol_2' => 25,
        'Symbol_3' => 10,
        'Symbol_4' => 8,
        'Symbol_5' => 5,
        'Symbol_7' => 4,
        'Symbol_8' => 3,
    ];

    private const PAYING_SYMBOLS = [
        'Symbol_0', 'Symbol_1', 'Symbol_2', 'Symbol_3',
        'Symbol_4', 'Symbol_5', 'Symbol_7', 'Symbol_8',
    ];

    private const TRIGGER_CHANCE = 80;

    private const TRIGGER_DENOMINATOR = 1000;

    private const MAX_BONUS_PAYOUT_MULTIPLIER = 2500;

    private const INITIAL_MIN_SYMBOLS = 2;

    private const INITIAL_MAX_SYMBOLS = 5;

    private const NEW_SYMBOL_CHANCE = 45;

    private const WILD_CHANCE_ON_SYMBOL = 25;

    private const TTL_SECONDS = 600;

    private string $userId;

    private string $gameUuid;

    private string $cacheKey;

    public function __construct(int $userId, string $gameUuid)
    {
        $this->userId = (string) $userId;
        $this->gameUuid = $gameUuid;
        $this->cacheKey = "fortune_tiger_bonus:{$this->userId}:{$this->gameUuid}";
    }

    public function isActive(): bool
    {
        return Cache::has($this->cacheKey);
    }

    public function getState(): ?array
    {
        return Cache::get($this->cacheKey);
    }

    public function shouldTrigger(): bool
    {
        return random_int(1, self::TRIGGER_DENOMINATOR) <= self::TRIGGER_CHANCE;
    }

    public function trigger(): string
    {
        $symbol = self::PAYING_SYMBOLS[random_int(0, count(self::PAYING_SYMBOLS) - 1)];

        $initialCount = random_int(self::INITIAL_MIN_SYMBOLS, self::INITIAL_MAX_SYMBOLS);
        $positions = range(0, 8);
        $this->secureShuffle($positions);
        $heldPositions = array_slice($positions, 0, $initialCount);

        $state = [
            'symbol' => $symbol,
            'held' => $heldPositions,
            'respins_left' => 7,
        ];

        Cache::put($this->cacheKey, $state, self::TTL_SECONDS);

        return $symbol;
    }

    public function processRespin(): array
    {
        $state = $this->getState();

        if (! $state) {
            return ['ends' => true, 'grid' => [], 'payout' => 0, 'is_full_screen' => false, 'matching_count' => 0];
        }

        $bonusSymbol = $state['symbol'];
        $heldPositions = $state['held'];
        $respinsLeft = $state['respins_left'];

        $newGrid = [];
        $newHeld = $heldPositions;
        $hasNewMatch = false;

        foreach (range(0, 8) as $pos) {
            if (in_array($pos, $heldPositions, true)) {
                $newGrid[$pos] = $bonusSymbol;
            } else {
                $roll = random_int(1, 100);
                if ($roll <= self::NEW_SYMBOL_CHANCE) {
                    $isWild = random_int(1, 100) <= self::WILD_CHANCE_ON_SYMBOL;
                    $newGrid[$pos] = $isWild ? self::WILD_SYMBOL : $bonusSymbol;
                    $newHeld[] = $pos;
                    $hasNewMatch = true;
                } else {
                    $others = array_values(array_diff(self::PAYING_SYMBOLS, [$bonusSymbol]));
                    $newGrid[$pos] = $others[random_int(0, count($others) - 1)];
                }
            }
        }

        $isFullScreen = count($newHeld) >= 9;
        $bonusEnds = ! $hasNewMatch || $isFullScreen || $respinsLeft <= 0;

        $matchingCount = count($newHeld);
        $payout = $this->calculatePayout($bonusSymbol, $matchingCount, $isFullScreen);

        if ($bonusEnds) {
            Cache::forget($this->cacheKey);
        } else {
            $state['held'] = $newHeld;
            $state['respins_left'] = $respinsLeft - 1;
            Cache::put($this->cacheKey, $state, self::TTL_SECONDS);
        }

        return [
            'ends' => $bonusEnds,
            'grid' => $newGrid,
            'held' => $newHeld,
            'payout' => $payout,
            'is_full_screen' => $isFullScreen,
            'matching_count' => $matchingCount,
            'respins_left' => $bonusEnds ? 0 : $respinsLeft - 1,
        ];
    }

    public function abandon(): void
    {
        $state = $this->getState();

        if ($state) {
            $bonusSymbol = $state['symbol'];
            $matchingCount = count($state['held']);
            $payout = $this->calculatePayout($bonusSymbol, $matchingCount, false);

            Cache::forget($this->cacheKey);
        }
    }

    private function calculatePayout(string $symbol, int $matchingCount, bool $isFullScreen): int
    {
        if ($matchingCount < 3) {
            return 0;
        }

        $basePayout = self::PAYTABLE[$symbol] ?? 0;
        $payout = intval(($matchingCount / 3) * $basePayout);

        if ($isFullScreen) {
            $payout *= 10;
        }

        return min($payout, self::MAX_BONUS_PAYOUT_MULTIPLIER);
    }

    private function secureShuffle(array &$array): void
    {
        $count = count($array);
        for ($i = $count - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$array[$i], $array[$j]] = [$array[$j], $array[$i]];
        }
    }

    public static function getPaytable(): array
    {
        return self::PAYTABLE;
    }

    public static function getMaxPayoutMultiplier(): int
    {
        return self::MAX_BONUS_PAYOUT_MULTIPLIER;
    }
}
