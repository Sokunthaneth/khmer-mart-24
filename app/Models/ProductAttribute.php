<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    // Relationships
    public function productSkus()
    {
        return $this->hasMany(ProductSku::class);
    }

    // CRUD Operations
    public static function createProductAttribute(array $data)
    {
        return self::create($data);
    }

    public static function getProductAttributeById($id)
    {
        return self::find($id);
    }

    public static function getAllProductAttributes()
    {
        return self::all();
    }

    public static function getProductAttributesWithSkus()
    {
        return self::with('productSkus')->get();
    }

    public function updateProductAttribute(array $data)
    {
        return $this->update($data);
    }

    public function deleteProductAttribute()
    {
        return $this->delete();
    }

    public static function getAttributesByName($name)
    {
        return self::where('attribute_name', $name)->get();
    }

    public static function getAttributesByValue($value)
    {
        return self::where('attribute_value', $value)->get();
    }
}
