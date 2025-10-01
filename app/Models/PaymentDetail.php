<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'provider',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
        ];
    }

    // Relationships
    public function order()
    {
        return $this->belongsTo(OrderDetail::class, 'order_id');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    // Helper methods
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }
}
