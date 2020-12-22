<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function show(int $orderId)
    {
        return new OrderResource(Order::findOrFail($orderId));
    }
}
