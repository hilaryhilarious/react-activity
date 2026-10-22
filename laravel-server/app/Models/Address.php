<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\AddressFactory;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'addresses';

    protected static function newFactory()
    {
        return AddressFactory::new();
    }

    protected $fillable = [
        'user_id',
        'street_address',
        'city',
        'state',
        'zip',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
}
