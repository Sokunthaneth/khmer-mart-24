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

    public function payment()
    {
        return $this->belongsTo(PaymentDetail::class, 'payment_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // Helper methods
    public function getCalculatedTotal()
    {
        return $this->orderItems->sum(function ($item) {
            return $item->quantity * $item->productSku->price;
        });
    }
}
