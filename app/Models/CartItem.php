<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'cart_item';

    protected $fillable = [
        'cart_id',
        'product_id',
        'products_sku_id',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
        ];
    }

    // Relationships
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productSku()
    {
        return $this->belongsTo(ProductSku::class, 'products_sku_id');
    }

    // Helper methods
    public function getSubtotal()
    {
        return $this->quantity * $this->productSku->price;
    }
}
