<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderItem;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderToManagerResource;
use App\Http\Resources\OrderItemResource;
use Carbon\Carbon;
use DateTime;
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
        // Find order by the ID
        $order = Order::findOrFail($id);

        // Find the user by the customer ID,
        // We need this to get the customer name associated with the order
        $user = User::findOrFail($order->customer->id);


        // We create a new stdClass, which allow us to create a new object
        // with the attributes we want
        $orderToSend = new stdClass();

        // ORDER
        $orderToSend->id = $order->id;
        $orderToSend->notes = $order->notes;
        $orderToSend->opened_at = $order->opened_at;
        $orderToSend->date = $order->date;
        $orderToSend->status = $order->status;
        $orderToSend->total_price = $order->total_price;
        $orderToSend->preparation_time = $order->preparation_time;
        $orderToSend->current_status_at = $order->current_status_at;
        $orderToSend->prepared_by = $order->prepared_by;
        $orderToSend->delivery_time = $order->delivery_time;
        $orderToSend->delivered_by = $order->delivered_by;
        $orderToSend->total_time = $order->total_time;


        // CUSTOMER
        $orderToSend->customer_id = $order->customer_id;
        $orderToSend->customer_name = $user->name;


        // ORDER ITEMS
        $orderItems = [];
        foreach ($order->orderItems as $item) {
            // We create a new stdClass, which allow us to create a new object
            // with the attributes we want
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
        $order = Order::findOrFail($id);
        if ($request->has('prepared_by')) {
            $order->prepared_by = $request->prepared_by;
        }
        if ($request->has('delivered_by')) {
            $order->delivered_by = $request->delivered_by;
        }
        if ($request->has('status')) {
            $now = now();
            if($request->status == 'R'){
                $preparationStarted = $order->current_status_at;
                $elapsedTime =  $preparationStarted->diffInSeconds($now);
                $order->preparation_time = $elapsedTime;
            }
            if($request->status == 'D'){
                $deliveryStarted = $order->current_status_at;
                $elapsedTime =  $deliveryStarted->diffInSeconds($now);
                $order->delivery_time = $elapsedTime;
                $order->closed_at = $now;
                $order->total_time = Carbon::parse($order->opened_at)->diffInSeconds($now);
            }
            if($request->status == 'C'){
                $order->closed_at = $now;
                $order->total_time = Carbon::parse($order->opened_at)->diffInSeconds($now);
            }
            $order->status = $request->status;
            $order->current_status_at = $now;
        }
        $order->save();
        /*$order = Order::where('id', '=', $id);
        if ($request->has('delivered_by')) {
            $order->update(['delivered_by' => $request->delivered_by]);
        }else if ($request->has('prepared_by')) {
            $order->update(['prepared_by' => $request->prepared_by]);
        } else if ($request->has('status')) {
            $now = now();
            if($request->status == 'R'){
                $preparationStarted = $order->first()->current_status_at;
                $elapsedTime =  $preparationStarted->diffInSeconds($now);
                $order->update(['preparation_time' => $elapsedTime]);
            }
            $order->update(['status' => $request->status, 'current_status_at' => $now]);
        }*/

        return new OrderResource($order);
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

    public function nextOrderToPrepare(){
        $nextOrder = Order::where('status','H')->whereNull('prepared_by')->orderBy('current_status_at','asc')->first();
        if($nextOrder){
            return new OrderResource($nextOrder);
        }
        return null;
    }

    public function activeOrders(){
        return OrderToManagerResource::collection(Order::whereNotIn('status',['C','D'])->orderBy('opened_at','asc')->paginate(10));
    }
}
