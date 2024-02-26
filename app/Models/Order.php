<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use App\Models\OrderItem;
use App\Models\Cart;
class Order extends Model
{
    //
    protected $table = 'orders';
    protected $fillable = ['user_id','cart_id', 'status', 'total'];
    protected $hidden = ['updated_at','created_at'];

    public function user() //一對多 一個訂單只屬於一個用戶
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function orderItems() //一對多 一個訂單有多個訂單項目
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function cart() //一對一 一個訂單只對應一個購物車 
    //cart_id是order表的外鍵 關聯cart表的id
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'id');
    }
    
}
