<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    public const STATUSES = [
        'pending' => 'Menunggu Pembayaran',
        'settlement' => 'Berhasil',
        'expire' => 'Kedaluwarsa',
        'cancel' => 'Dibatalkan',
    ];

    protected $fillable = [
        'user_id',
        'order_id',
        'transaction_id',
        'amount',
        'status',
        'payment_type',
        'issuer',
        'reference',
        'qr_string',
        'qr_url',
        'checkout_url',
        'meta',
        'expired_at',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'meta' => 'array',
            'expired_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'settlement';
    }

    public function isFinal(): bool
    {
        return in_array($this->status, ['settlement', 'expire', 'cancel'], true);
    }

    public function getRouteKeyName(): string
    {
        return 'order_id';
    }
}
