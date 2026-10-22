<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\OrderProductFactory;

class OrderProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_products';

    protected static function newFactory()
    {
        return OrderProductFactory::new();
    }

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}