<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function productSkus()
    {
        return $this->hasMany(ProductSku::class);
    }

    public function wishlists()
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

    // CRUD Operations
    public static function createProduct(array $data)
    {
        return self::create($data);
    }

    public static function getProductById($id)
    {
        return self::find($id);
    }

    public static function getAllProducts()
    {
        return self::all();
    }

    public static function getProductsWithCategory()
    {
        return self::with(['category', 'subCategory'])->get();
    }

    public static function getProductsWithSkus()
    {
        return self::with('productSkus')->get();
    }

    public static function getProductsByCategory($categoryId)
    {
        return self::where('category_id', $categoryId)->get();
    }

    public static function getProductsBySubCategory($subCategoryId)
    {
        return self::where('sub_category_id', $subCategoryId)->get();
    }

    public function updateProduct(array $data)
    {
        return $this->update($data);
    }

    public function deleteProduct()
    {
        return $this->delete();
    }

    public static function searchProducts($query)
    {
        return self::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();
    }
}
