<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'sku',
        'oem_reference',
        'name',
        'slug',
        'description',
        'condition',
        'price',
        'currency',
        'stock_quantity',
        'status',
        'main_image_path',
        'location',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock_quantity' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function compatibilities(): HasMany
    {
        return $this->hasMany(ProductCompatibility::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function conditionLabel(): string
    {
        return match($this->condition) {
            'used_good'    => 'Bon état',
            'used_fair'    => 'État correct',
            'refurbished'  => 'Reconditionné',
            'for_parts'    => 'Pour pièces',
            default        => $this->condition,
        };
    }
}
