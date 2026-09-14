<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'software_name', 'software_description',
        'software_logo_white', 'software_logo_black', 'software_favicon',
        'prefix', 'currency_code', 'decimal_format', 'currency_position',
        'storage', 'min_deposit', 'max_deposit',
        'min_saque', 'max_saque',
        'initial_bonus',
        'ngr_percent', 'revshare_reverse',
        'instagram', 'tiktok', 'whatsapp', 'discord', 'telegram', 'twitter',
        'affiliate_default_percentage', 'affiliate_default_cpa', 'affiliate_default_baseline',
    ];

    public function logoUrl(): string
    {
        $path = $this->software_logo_white;
        if (!empty($path) && is_string($path)) {
            return asset('storage/' . $path) . '?v=' . $this->updated_at?->timestamp;
        }
        return asset('assets/images/logo.svg');
    }

    public static function getInstance(): ?self
    {
        return Cache::remember('settings.instance', 3600, function () {
            return static::first();
        });
    }

    public static function flushCache(): void
    {
        Cache::forget('settings.instance');
    }
}
