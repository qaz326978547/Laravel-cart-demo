<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartItemController extends Controller
{
    public function addCartItem(Request $request)
    {
        try {
            $form = $this->validate($request, [
                'cartId' => 'required | integer', // 這裡的cart_id是指購物車的id
                'productId' => 'required | integer',
                'quantity' => 'required | integer',
            ]);
            $cart = Cart::find($form['cartId']);
            //找到商品後，更新商品庫存數量
            $product = Product::where('id', $form['productId'])->first();
            $product->quantity -= $form['quantity'];
            $product->save();
            //如果有重複的商品，就更新數量
            $cartItem = $cart->cartItems()->where('product_id', $form['productId'])->first();
            if ($cartItem) {
                $cartItem->quantity += $form['quantity'];
                $cartItem->save();
                return response()->json($cartItem);
            } else {
                $result = $cart->cartItems()->create([
                    'product_id' => $form['productId'],
                    'quantity' => $form['quantity']
                ]);
                return response()->json($result);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            return response()->json($errors, 400);
        }
    }

    public function updateCartItem(Request $request)
    {
        try {
            $form = $this->validate($request, [
                'cartId' => 'required | integer',
                'productId' => 'required | integer', // 這裡的product_id是指商品的id
                'quantity' => 'required | integer',
            ]);
            $cart = Cart::find($form['cartId']);
            $cartItem = $cart->cartItems()->where('product_id', $form['productId'])->first();
            $product = Product::find($form['productId']);

            // 計算購物車項目的數量變化
            $quantityChange = $form['quantity'] - $cartItem->quantity;

            if ($quantityChange > $product->quantity) { 
                return response()->json(['error' => '商品庫存不足'], 400);
            } else {
                $product->quantity -= $quantityChange;
                $product->save();

                $cartItem->quantity = $form['quantity'];
                $cartItem->save();

                return response()->json($cartItem);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            return response()->json($errors, 400);
        }
    }
    public function deleteCartItem(Request $request)
    {
        try {

            $form = $this->validate($request, [
                'cartId' => 'required | integer',
                'productId' => 'required | integer',
            ]);
            $cart = Cart::find($form['cartId']);
            $cartItem = $cart->cartItems()->where('product_id', $form['productId'])->first();
            $product = Product::find($form['productId']);
            $product->quantity += $cartItem->quantity;
            $product->save();
            // $cartItem->forceDelete();         // forceDelete() 是永久刪除
            $cartItem->delete();              // delete() 是軟刪除
            return response()->json(['status' => 'success'], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            return response()->json($errors, 400);
        }
    }
    //復原軟刪除
    public function restoreCartItem(Request $request)
    {
        try {
            $form = $this->validate($request, [
                'cartId' => 'required | integer',
                'productId' => 'required | integer',
            ]);
            $cart = Cart::find($form['cartId']);
            $cartItem = $cart->cartItems()->where('product_id', $form['productId'])->onlyTrashed()->first();
            $product = Product::find($form['productId']);
            $product->quantity -= $cartItem->quantity;
            $product->save();
            $cartItem->restore();
            return response()->json(['status' => 'success'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            return response()->json($errors, 400);
        }
    }
}
