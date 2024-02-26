<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    //
    protected $table = 'order_items';
    protected $fillable = ['order_id','product_id', 'quantity', 'price'];
    protected $hidden = ['updated_at','created_at'];

    public function order() //一對多 一個訂單項目只屬於一個訂單
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function product() //一對多 一個訂單項目只屬於一個產品
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
