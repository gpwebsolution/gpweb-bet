<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameExclusive extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'game_exclusives';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider_id',
        'uuid',
        'name',
        'description',
        'cover',
        'icon',
        'winLength',
        'loseLength',
        'influencer_winLength',
        'influencer_loseLength',
        'active',
        'views',
        'bet_size_list',
    ];

    protected function casts(): array
    {
        return [
            'bet_size_list' => 'array',
        ];
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

}
