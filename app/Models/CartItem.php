<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
class CartItem extends Model
{
    // use SoftDeletes;
    protected $table = 'cart_items';
    protected $fillable = ['product_id', 'cart_id', 'quantity'];


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'id');
    }
}
