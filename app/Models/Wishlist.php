<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wishlist extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'wishlist';

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // CRUD Operations
    public static function createWishlist(array $data)
    {
        return self::create($data);
    }

    public static function getWishlistById($id)
    {
        return self::find($id);
    }

    public static function getAllWishlists()
    {
        return self::all();
    }

    public static function getWishlistsByUser($userId)
    {
        return self::where('user_id', $userId)->get();
    }

    public static function getWishlistsWithProduct()
    {
        return self::with('product')->get();
    }

    public function updateWishlist(array $data)
    {
        return $this->update($data);
    }

    public function deleteWishlist()
    {
        return $this->delete();
    }

    public static function getUserWishlistWithProducts($userId)
    {
        return self::with('product')->where('user_id', $userId)->get();
    }

    public static function checkIfInWishlist($userId, $productId)
    {
        return self::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();
    }
}
