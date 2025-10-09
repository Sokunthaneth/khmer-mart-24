<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_item';

    protected $fillable = [
        'order_id',
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
    public function order()
    {
        return $this->belongsTo(OrderDetail::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productSku()
    {
        return $this->belongsTo(ProductSku::class, 'products_sku_id');
    }

    // CRUD Operations
    public static function createOrderItem(array $data)
    {
        return self::create($data);
    }

    public static function getOrderItemById($id)
    {
        return self::find($id);
    }

    public static function getAllOrderItems()
    {
        return self::all();
    }

    public static function getOrderItemsByOrder($orderDetailId)
    {
        return self::where('order_detail_id', $orderDetailId)->get();
    }

    public static function getOrderItemsWithProduct()
    {
        return self::with('product')->get();
    }

    public static function getOrderItemsWithSku()
    {
        return self::with('productSku')->get();
    }

    public function updateOrderItem(array $data)
    {
        return $this->update($data);
    }

    public function deleteOrderItem()
    {
        return $this->delete();
    }

    public function calculateTotal()
    {
        if ($this->productSku) {
            return floatval($this->productSku->price) * $this->quantity;
        }

        return 0;
    }

    public function getSubtotalInCents()
    {
        return $this->calculateTotal() * 100;
    }

    public static function getItemsByProduct($productId)
    {
        return self::where('product_id', $productId)->get();
    }
}
