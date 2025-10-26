<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description'];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'parent_id');
    }

    // CRUD Operations
    public static function createCategory(array $data)
    {
        return self::create($data);
    }

    public static function getCategoryById($id)
    {
        return self::find($id);
    }

    public static function getAllCategories()
    {
        return self::all();
    }

    public static function getCategoriesWithSubCategories()
    {
        return self::with('subCategories')->get();
    }

    public static function getCategoriesWithProducts()
    {
        return self::with('products')->get();
    }

    public function updateCategory(array $data)
    {
        return $this->update($data);
    }

    public function deleteCategory()
    {
        return $this->delete();
    }

    public static function getCategoryByName($name)
    {
        return self::where('name', $name)->first();
    }
}
