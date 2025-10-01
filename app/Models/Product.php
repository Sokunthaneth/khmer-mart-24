<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'description',
        'summary',
        'cover',
        'category_id'
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function productSkus()
    {
        return $this->hasMany(ProductSku::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
