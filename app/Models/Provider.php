<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    use HasFactory;

    protected $table = 'providers';

    protected $fillable = [
        'name',
        'description',
        'image',
        'slug'
    ];

    public function games() : HasMany
    {
        return $this->hasMany(Game::class, 'provider_id');
    }
}
