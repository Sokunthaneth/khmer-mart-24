<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'name',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // CRUD Operations
    public static function createSubCategory(array $data)
    {
        return self::create($data);
    }

    public static function getSubCategoryById($id)
    {
        return self::find($id);
    }

    public static function getAllSubCategories()
    {
        return self::all();
    }

    public static function getSubCategoriesByCategory($categoryId)
    {
        return self::where('parent_id', $categoryId)->get();
    }

    public static function getSubCategoriesWithCategory()
    {
        return self::with('category')->get();
    }

    public static function getSubCategoriesWithProducts()
    {
        return self::with('products')->get();
    }

    public function updateSubCategory(array $data)
    {
        return $this->update($data);
    }

    public function deleteSubCategory()
    {
        return $this->delete();
    }

    public static function getSubCategoryByName($name)
    {
        return self::where('name', $name)->first();
    }
}
