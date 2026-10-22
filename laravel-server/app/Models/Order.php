<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\OrderFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';

    protected static function newFactory()
    {
        return OrderFactory::new();
    }

    protected $fillable = [
        'user_id',
        'date',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function products()
    {
        return $this->belongsToMany(
            Products::class,
            'order_products',
            'order_id',
            'product_id'
        )->withPivot('quantity');
    }
}