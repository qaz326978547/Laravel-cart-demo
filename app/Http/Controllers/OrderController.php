<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function getOrder(Request $request)
    {
        $user = auth()->user();
        $order = Order::where("user_id", $user->id)->with('orderItems.product')->first();
        if(empty($order)){
            return response()->json(['error'=>'訂單不存在'], 400);
        }
        $data = [
            'orderId' => $order->id,
            'userId' => $order->user_id,
            'products' => $order->orderItems->map(function($orderItem){
                return [
                    'id' => $orderItem->id,
                    'productId' => $orderItem->product->id,
                    'productName' => $orderItem->product->name,
                    'description' => $orderItem->product->description,
                    'quantity' => $orderItem->quantity,
                    'price' => $orderItem->price,
                ];
            }),
        ];
        return response()->json(['data'=>$data]);
    }
}
