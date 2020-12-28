<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'customer_id' => $this->customer_id,
            'customer_name' => $this->customer_name,
            'notes' => $this->notes,
            'total_price' => $this->total_price,
            'date' => $this->date,
            'preparation_time' => $this->preparation_time,
            'prepared_by' => $this->prepared_by,
            'delivery_time' => $this->delivery_time,
            'delivered_by' => $this->delivered_by,
            'total_time' => $this->total_time,
            'opened_at' => $this->opened_at,
            'orderItems' => $this->orderItems
        ];
    }
}
