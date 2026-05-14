<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'provider',
        'provider_session_id',
        'provider_payment_id',
        'amount',
        'currency',
        'status',
        'raw_payload',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'      => 'decimal:2',
            'raw_payload' => 'array',
            'paid_at'     => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
