<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\ProductsFactory;

class Products extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products1';

    protected static function newFactory()
    {
        return ProductsFactory::new();
    }

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function orderProducts()
    {
        return $this->belongsToMany(
            Order::class,
            'order_products',
            'product_id',
            'order_id'
        )->withPivot('quantity');
    }
}