<?php

namespace App\Http\Controllers\Games\SpinData\FortuneTiger;

class FortuneTigerBonus
{
    /**
     * Fortune Tiger bonus is triggered randomly during any base spin (NOT by scatter).
     * The bonus grid is generated dynamically in SpinStructure.
     * This file returns an empty array since scatter-based entries are not used.
     *
     * @return array
     */
    public static function getBonus(): array
    {
        return [];
    }
}
