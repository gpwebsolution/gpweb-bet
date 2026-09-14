<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoletaDiaria extends Model
{
    protected $table = 'roleta_diarias';

    protected $fillable = [
        'user_id',
        'roleta_recompensa_id',
        'value',
        'spin_date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reward(): BelongsTo
    {
        return $this->belongsTo(RoletaRecompensa::class, 'roleta_recompensa_id');
    }
}
