<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vip extends Model
{
    protected $fillable = [
        'level',
        'name',
        'weekly_bonus',
        'monthly_bonus',
        'level_up_bonus',
        'min_deposit',
        'min_bets',
        'color',
        'icon',
        'description',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'weekly_bonus' => 'decimal:2',
            'monthly_bonus' => 'decimal:2',
            'level_up_bonus' => 'decimal:2',
            'min_deposit' => 'decimal:2',
            'min_bets' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
