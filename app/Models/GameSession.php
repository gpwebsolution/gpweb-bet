<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameSession extends Model
{
    protected $fillable = [
        'user_id', 'game_id', 'game_name', 'game_uuid', 'provider', 'balance_source',
        'bet_amount', 'result_amount', 'profit', 'type', 'round_id',
    ];

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }
}
