<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VipBonus extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'vip_id',
        'type',
        'amount',
        'claimed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'claimed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vip(): BelongsTo
    {
        return $this->belongsTo(Vip::class);
    }
}
