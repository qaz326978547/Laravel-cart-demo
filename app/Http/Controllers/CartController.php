<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    
    public function getCart()
    {
        $user = auth()->user();
        $cart = Cart::where("user_id", $user->id)->with('cartItems.product')->first();
        if(empty($cart)){
            $cart = Cart::create(['user_id' => $user->id]);
            $cart->save();
        }

        $data = [
            'cartId' => $cart->id,
            'userId' => $cart->user_id,
            'products' => $cart->cartItems->map(function($cartItem){
                return [
                    'id' => $cartItem->id,
                    'productId' => $cartItem->product->id,
                    'productName' => $cartItem->product->name,
                    'description' => $cartItem->product->description,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ];
            }),
        ];
        return response()->json(['data'=>$data]);
    } 

    public function checkout(Request $request)
    {
        $user = auth()->user();
        $cart = Cart::where("user_id", $user->id)->where('checkouted', 0)->with('cartItems.product')->first();
        if(empty($cart)){
            return response()->json(['error'=>'購物車不存在或已結帳'], 400);
        }
        $order = $cart->checkout();
        return response()->json(['data'=>$order]);
    }
}
 