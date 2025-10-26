<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_item';

    protected $fillable = [
        'order_detail_id',
        'product_id',
        'product_sku_id',
        'qty',
        'unit_price',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'integer',
            'unit_price' => 'decimal:2',
        ];
    }

    // Relationships
    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productSku()
    {
        return $this->belongsTo(ProductSku::class, 'product_sku_id');
    }

    /**
     * Get subtotal for this order item
     */
    public function getSubtotalAttribute()
    {
        return $this->qty * $this->unit_price;
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
