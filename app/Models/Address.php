<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'address_line_1',
        'address_line_2',
        'country',
        'city',
        'postal_code',
        'landmark',
        'phone_number',
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

    // CRUD Operations
    public static function createAddress(array $data)
    {
        return self::create($data);
    }

    public static function getAddressById($id)
    {
        return self::find($id);
    }

    public static function getAllAddresses()
    {
        return self::all();
    }

    public static function getAddressesByUser($userId)
    {
        return self::where('user_id', $userId)->get();
    }

    public static function getAddressWithUser($id)
    {
        return self::with('user')->find($id);
    }

    public function updateAddress(array $data)
    {
        return $this->update($data);
    }

    public function deleteAddress()
    {
        return $this->delete();
    }

    public static function getAddressesByCity($city)
    {
        return self::where('city', $city)->get();
    }

    public static function getAddressesByCountry($country)
    {
        return self::where('country', $country)->get();
    }
}
