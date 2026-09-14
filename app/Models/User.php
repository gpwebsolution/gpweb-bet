<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'balance',
        'affiliate_code', 'referred_by', 'status',
        'google_id', 'avatar',
        'role_id', 'last_name', 'cpf', 'phone',
        'banned', 'inviter',
        'affiliate_revenue_share', 'affiliate_cpa', 'affiliate_baseline',
        'is_demo_agent', 'token', 'token_time', 'logged_in',
        'oauth_id', 'oauth_type', 'cpf_confirmed',
        'vip_id',
        'vip_leveled_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
            'affiliate_cpa' => 'decimal:2',
            'affiliate_baseline' => 'decimal:2',
            'cpf_confirmed' => 'boolean',
        ];
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function gameSessions(): HasMany
    {
        return $this->hasMany(GameSession::class);
    }

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class, 'referred_by', 'code');
    }

    public function vip(): BelongsTo
    {
        return $this->belongsTo(Vip::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInfluencers($query)
    {
        return $query->where('is_demo_agent', 1);
    }

    public function isInfluencer(): bool
    {
        return $this->is_demo_agent == 1;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole(['admin', 'influencer']);
    }
}
