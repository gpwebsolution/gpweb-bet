<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Affiliate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'code', 'model',
        'revshare_percentage', 'cpa_value',
        'total_referred', 'total_commission',
        'pending_commission', 'status',
    ];

    protected function casts(): array
    {
        return [
            'revshare_percentage' => 'decimal:2',
            'cpa_value' => 'decimal:2',
            'total_referred' => 'integer',
            'total_commission' => 'decimal:2',
            'pending_commission' => 'decimal:2',
            'status' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    public function referredUsers()
    {
        return User::where('referred_by', $this->code);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function getCommissionRateAttribute(): float
    {
        if ($this->model === 'revshare' && $this->revshare_percentage > 0) {
            return floatval($this->revshare_percentage);
        }
        $setting = \App\Models\Setting::first();
        return floatval($setting->affiliate_default_percentage ?? 10);
    }
}
