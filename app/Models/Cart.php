<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $fillable = [
        'user_id',
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

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // Helper methods
    public function getTotalAmount()
    {
        return $this->cartItems->sum(function ($item) {
            return $item->quantity * $item->productSku->price;
        });
    }
}
