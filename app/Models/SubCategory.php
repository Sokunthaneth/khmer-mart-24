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
}
