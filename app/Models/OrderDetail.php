<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $this->hasMany(PaymentDetail::class);
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
        return $this->orderItems->sum('total');
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }
}
