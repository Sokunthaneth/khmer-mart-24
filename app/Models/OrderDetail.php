<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_id',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'integer',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class, 'order_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // CRUD Operations
    public static function createOrderDetail(array $data)
    {
        return self::create($data);
    }

    public static function getOrderDetailById($id)
    {
        return self::find($id);
    }

    public static function getAllOrderDetails()
    {
        return self::all();
    }

    public static function getOrderDetailsByUser($userId)
    {
        return self::where('user_id', $userId)->get();
    }

    public static function getOrderDetailsWithItems()
    {
        return self::with('orderItems')->get();
    }

    public static function getOrderDetailsWithPayments()
    {
        return self::with('paymentDetails')->get();
    }

    public function updateOrderDetail(array $data)
    {
        return $this->update($data);
    }

    public function deleteOrderDetail()
    {
        return $this->delete();
    }

    public static function getOrdersByStatus($status)
    {
        return self::where('order_status', $status)->get();
    }

    public function getTotalAmount()
    {
        return $this->orderItems->sum(function ($item) {
            return $item->calculateTotal();
        });
    }

    public function getFormattedTotal()
    {
        // Calculate total from order items instead of using stored total
        return number_format($this->getTotalAmount(), 2);
    }

    public function getStoredFormattedTotal()
    {
        // Method to get the originally stored total if needed
        return number_format($this->total / 100, 2);
    }

    public function updateStoredTotal()
    {
        // Update the stored total to match the calculated total from items
        $calculatedTotal = $this->getTotalAmount() * 100; // Convert to cents
        $this->update(['total' => $calculatedTotal]);

        return $this;
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }
}
