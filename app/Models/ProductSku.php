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
            'price' => 'float',
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
        return $this->hasMany(OrderItem::class);
    }

    // CRUD Operations
    public static function createProductSku(array $data)
    {
        return self::create($data);
    }

    public static function getProductSkuById($id)
    {
        return self::find($id);
    }

    public static function getAllProductSkus()
    {
        return self::all();
    }

    public static function getSkusByProduct($productId)
    {
        return self::where('product_id', $productId)->get();
    }

    public static function getSkusWithProduct()
    {
        return self::with('product')->get();
    }

    public static function getSkusWithAttribute()
    {
        return self::with('productAttribute')->get();
    }

    public function updateProductSku(array $data)
    {
        return $this->update($data);
    }

    public function deleteProductSku()
    {
        return $this->delete();
    }

    public static function getSkuBySku($sku)
    {
        return self::where('sku', $sku)->first();
    }

    public static function getAvailableSkus()
    {
        return self::where('quantity', '>', 0)->get();
    }
}
