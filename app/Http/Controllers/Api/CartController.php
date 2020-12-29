<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use App\Http\Resources\OrderResource;

class CartController extends Controller
{
    public function checkout(Request $request)
    {
        if(empty($request->cart)){
            return response()->json("Cart is empty", 400);
        }
        $order = new Order();
        $now = Carbon::now();
        $order->date = $now->format('Y-m-d');
        $order->customer_id = $request->customer;
        if($request->notes){
            $order->notes = $request->notes;
        }
        $order->total_price = $request->totalPrice;
        $order->status = "H";
        $order->opened_at = $now->format('Y-m-d H:i:s');
        $order->current_status_at = $order->opened_at;
        $order->save();
        foreach($request->cart as $item ){
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item['id'];
            $orderItem->quantity = $item['quantity'];
            $orderItem->unit_price = $item['price'];
            $orderItem->sub_total_price = $orderItem->quantity*$orderItem->unit_price;
            $orderItem->save();
        }
        return new OrderResource($order);
    }
}
