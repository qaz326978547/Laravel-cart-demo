<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CartItem;
use App\Models\Order;
use App\User;
class Cart extends Model
{
    //
    protected $table = 'carts'; // 'carts' is the name of the table in the database
    // protected $fillable = ['user_id'];
    // protected $fillable = ['product_id', 'quantity', 'price'];

    protected $guarded = ['id']; // 黑名單

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'id'); // cart的id和cart_item的cart_id关联
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function order()
    {
        return $this->hasOne(Order::class, 'cart_id', 'id');
    }

    public function checkout()
    {
       $order =  $this->order()->create([
            'user_id' => $this->user_id,
            'status' => 'unpaid' 
        ]);
            //將購物車內的商品轉移到訂單內
        foreach ($this->cartItems as $cartItem) {
            $order->orderItems()->create([
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price * $cartItem->quantity,
            ]);
            $order->total += $cartItem->product->price * $cartItem->quantity;
            $order->save();

        }
        $cartItem = $this->cartItems()->forceDelete(); //刪除購物車內的商品
        //cart表格的checkout 欄位設為1
        $this->checkouted = 1;
        $this->save();
        return $order;
    }

}
