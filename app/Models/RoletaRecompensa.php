<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoletaRecompensa extends Model
{
    protected $table = 'roleta_recompensas';

    protected $fillable = [
        'label',
        'value',
        'weight',
        'color',
        'sort_order',
        'active',
        'garantido',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'weight' => 'integer',
            'active' => 'boolean',
            'garantido' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
