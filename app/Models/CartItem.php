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

    // CRUD Operations
    public static function createCartItem(array $data)
    {
        return self::create($data);
    }

    public static function getCartItemById($id)
    {
        return self::find($id);
    }

    public static function getAllCartItems()
    {
        return self::all();
    }

    public static function getCartItemsByCart($cartId)
    {
        return self::where('cart_id', $cartId)->get();
    }

    public static function getCartItemsWithProduct()
    {
        return self::with('product')->get();
    }

    public function updateCartItem(array $data)
    {
        return $this->update($data);
    }

    public function deleteCartItem()
    {
        return $this->delete();
    }

    public static function findExistingItem($cartId, $productId)
    {
        return self::where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->first();
    }

    public function incrementQuantity($amount = 1)
    {
        return $this->update(['quantity' => $this->quantity + $amount]);
    }

    public function decrementQuantity($amount = 1)
    {
        $newQuantity = max(0, $this->quantity - $amount);
        return $this->update(['quantity' => $newQuantity]);
    }
}
