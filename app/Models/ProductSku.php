<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSku extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products_skus';

    protected $fillable = [
        'product_id',
        'size_attribute_id',
        'color_attribute_id',
        'sku',
        'price',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quantity' => 'integer',
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sizeAttribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'size_attribute_id');
    }

    public function colorAttribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'color_attribute_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'products_sku_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'products_sku_id');
    }
}
