<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderItem;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderItemResource;
use stdClass;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        $user = User::findOrFail($order->customer->id);
        $orderToSend = new stdClass();

        $orderToSend->id = $order->id;
        $orderToSend->customer_id = $order->customer_id;
        $orderToSend->customer_name = $user->name;
        $orderToSend->notes = $order->notes;
        $orderToSend->opened_at = $order->opened_at;
        $orderToSend->date = $order->date;
        $orderToSend->status = $order->status;
        $orderToSend->total_price = $order->total_price;
        $orderToSend->preparation_time = $order->preparation_time;
        $orderToSend->prepared_by = $order->prepared_by;
        $orderToSend->delivery_time = $order->delivery_time;
        $orderToSend->delivered_by = $order->delivered_by;
        $orderToSend->total_time = $order->total_time;
        $orderItems = [];
        foreach ($order->orderItems as $item) {
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


    public function updatePreparedBy($id, Request $request)
    {
        if($request->has('prepared_by')){
            Order::where('id', '=', $id)->update(['prepared_by' => $request->prepared_by]);
        }else if($request->has('status')){
            Order::where('id', '=', $id)->update(['status' => $request->status]);
        }
        
        return;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
