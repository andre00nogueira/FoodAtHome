<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Order;
use stdClass;

class UserToManagerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->type=='EC'){
            $order=Order::where('prepared_by',$this->id)->whereIn('status', ['H','P'])->first();
            
        }else{
            $order=Order::where('delivered_by', $this->id)->where('status', 'T')->first();
        }
        
        $orderToSend = new stdClass();

        //did this cause of date format
        if($order){
            $orderToSend->id = $order->id;
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
            $orderToSend->customer_id = $order->customer_id;
            $orderToSend->current_status_at = $order->current_status_at->format('Y-m-d H:i:s');
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'photo_url' => $this->photo_url,
            'type' => $this->type,
            'blocked' => $this->blocked,
            'logged_at' => $this->logged_at,
            'available_at' => $this->available_at,
            'current_order' => $orderToSend
        ];
    }
}
