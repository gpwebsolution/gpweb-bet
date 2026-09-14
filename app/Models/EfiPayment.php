<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EfiPayment extends Model
{
    use HasFactory;

    protected $table = 'efi_payments';

    protected $appends = ['dateHumanReadable', 'createdAt'];

    protected $fillable = [
        'payment_id',
        'user_id',
        'amount',
        'cpf',
        'pix_qrcode',
        'pix_copy_paste',
        'txid',
        'e2eid',
        'status',
        'token',
        'expires_at',
        'paid_at',
        'last_efi_checked_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
        'last_efi_checked_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at']);
    }

    public function getDateHumanReadableAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
