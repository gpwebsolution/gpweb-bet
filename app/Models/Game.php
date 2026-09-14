<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    protected $fillable = [
        'provider_id', 'name', 'uuid', 'image', 'type', 'provider', 'provider_service',
        'technology', 'has_lobby', 'is_mobile', 'has_freespins', 'has_tables',
        'slug', 'active', 'views',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }
}
