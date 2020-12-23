<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use stdClass;

class OrderController extends Controller
{
    public function get(int $orderId)
    {
        $order = Order::findOrFail($orderId);
        $user = User::findOrFail($order->customer->id);
        $orderToSend = new stdClass();

        $orderToSend->id = $order->id;
        $orderToSend->customer_name = $user->name;
        $orderToSend->notes = $order->notes;
        $orderToSend->opened_at = $order->opened_at;
        $orderToSend->status = $order->status;
        $orderToSend->total_price = $order->total_price;
        $orderItems = [];
        foreach($order->orderItems as $item){
            $itemToSend = new stdClass();

            $product = Product::find($item->product_id);
            $itemToSend->name = $product->name;
            $itemToSend->photo_url = $product->photo_url;
            $itemToSend->quantity = $item->quantity;
            $itemToSend->sub_total_price = $item->sub_total_price;

            array_push($orderItems, $itemToSend);
        }
        $orderToSend->orderItems = $orderItems;

        return new OrderResource($orderToSend);
    }
}
