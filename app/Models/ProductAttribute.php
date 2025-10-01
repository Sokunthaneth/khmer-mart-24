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
    public function sizeProductSkus()
    {
        return $this->hasMany(ProductSku::class, 'size_attribute_id');
    }

    public function colorProductSkus()
    {
        return $this->hasMany(ProductSku::class, 'color_attribute_id');
    }

    // Scopes
    public function scopeColors($query)
    {
        return $query->where('type', 'color');
    }

    public function scopeSizes($query)
    {
        return $query->where('type', 'size');
    }
}
