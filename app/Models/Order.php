<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'payment_status',
        'subtotal',
        'delivery_fee',
        'tax_amount',
        'total',
        'currency',
        'customer_name',
        'customer_email',
        'customer_phone',
        'delivery_address',
        'notes',
        'payment_provider',
        'payment_reference',
    ];

    protected function casts(): array
    {
        return [
            'subtotal'     => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'tax_amount'   => 'decimal:2',
            'total'        => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function proofs(): HasMany
    {
        return $this->hasMany(PaymentProof::class);
    }

    public function amountPaid(): float
    {
        return (float) $this->proofs()->where('status', 'validated')->sum('amount');
    }

    public function amountRemaining(): float
    {
        return max(0, (float) $this->total - $this->amountPaid());
    }

    public function isFullyPaid(): bool
    {
        return $this->amountRemaining() <= 0;
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'pending'    => 'En attente',
            'confirmed'  => 'Confirmée',
            'processing' => 'En traitement',
            'shipped'    => 'Expédiée',
            'delivered'  => 'Livrée',
            'cancelled'  => 'Annulée',
            default      => $this->status,
        };
    }

    public function paymentStatusLabel(): string
    {
        return match($this->payment_status) {
            'unpaid'   => 'Non payée',
            'pending'  => 'En attente',
            'paid'     => 'Payée',
            'failed'   => 'Échouée',
            'refunded' => 'Remboursée',
            default    => $this->payment_status,
        };
    }
}
