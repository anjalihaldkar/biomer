<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $fillable = [
        'product_id', 'sku', 'attribute_name', 'attribute_value',
        'price', 'weight', 'unit', 'stock_quantity', 'is_active', 'image_path',
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'weight'         => 'decimal:2',
        'is_active'      => 'boolean',
        'stock_quantity' => 'integer',
    ];

    public function product() { return $this->belongsTo(Product::class); }

    // ── Stock Helpers ──────────────────────────────────────
    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function isLowStock(int $threshold = 5): bool
    {
        return $this->stock_quantity <= $threshold && $this->stock_quantity > 0;
    }
}