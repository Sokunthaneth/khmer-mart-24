<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'provider',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
        ];
    }

    // Relationships
    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class);
    }

    // CRUD Operations
    public static function createPaymentDetail(array $data)
    {
        return self::create($data);
    }

    public static function getPaymentDetailById($id)
    {
        return self::find($id);
    }

    public static function getAllPaymentDetails()
    {
        return self::all();
    }

    public static function getPaymentDetailsByOrder($orderDetailId)
    {
        return self::where('order_detail_id', $orderDetailId)->get();
    }

    public static function getPaymentDetailsWithOrder()
    {
        return self::with('orderDetail')->get();
    }

    public function updatePaymentDetail(array $data)
    {
        return $this->update($data);
    }

    public function deletePaymentDetail()
    {
        return $this->delete();
    }

    public static function getPaymentsByStatus($status)
    {
        return self::where('status', $status)->get();
    }

    public static function getPaymentsByProvider($provider)
    {
        return self::where('provider', $provider)->get();
    }

    public function isSuccessful()
    {
        return $this->status === 'success';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }
}
