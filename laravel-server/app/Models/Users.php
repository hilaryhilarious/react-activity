<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\UsersFactory;

class Users extends Model
{
    use HasFactory;

    protected $table = 'users1';

    protected static function newFactory()
    {
        return UsersFactory::new();
    }

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
