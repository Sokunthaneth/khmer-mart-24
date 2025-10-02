<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'avatar',
        'first_name',
        'last_name',
        'username',
        'email',
        'role',
        'password',
        'birth_of_date',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // CRUD Operations
    public static function createUser(array $data)
    {
        return self::create($data);
    }

    public static function getUserById($id)
    {
        return self::find($id);
    }

    public static function getUserByEmail($email)
    {
        return self::where('email', $email)->first();
    }

    public static function getAllUsers()
    {
        return self::all();
    }

    public function updateUser(array $data)
    {
        return $this->update($data);
    }

    public function deleteUser()
    {
        return $this->delete();
    }

    public static function getUsersWithAddresses()
    {
        return self::with('addresses')->get();
    }

    public static function getUsersWithOrders()
    {
        return self::with('orderDetails')->get();
    }

    // Relationships
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
