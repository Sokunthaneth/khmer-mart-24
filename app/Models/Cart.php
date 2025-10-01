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

    // CRUD Operations
    public static function createCart(array $data)
    {
        return self::create($data);
    }

    public static function getCartById($id)
    {
        return self::find($id);
    }

    public static function getAllCarts()
    {
        return self::all();
    }

    public static function getCartsByUser($userId)
    {
        return self::where('user_id', $userId)->get();
    }

    public static function getCartsWithItems()
    {
        return self::with('cartItems')->get();
    }

    public function updateCart(array $data)
    {
        return $this->update($data);
    }

    public function deleteCart()
    {
        return $this->delete();
    }

    public static function getUserCartWithItems($userId)
    {
        return self::with('cartItems.product')->where('user_id', $userId)->first();
    }

    public function getTotalAmount()
    {
        return $this->cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
    }
}
