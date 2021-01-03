<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class OrderToManagerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->delivered_by){
            $currentEmployee=User::find($this->delivered_by);
        }else{
            $currentEmployee=User::find($this->prepared_by);
        }
        return [
            'id' => $this->id,
            'status' => $this->status,
            'customer_id' => $this->customer_id,
            'customer_name' => $this->customer_name,
            'notes' => $this->notes,
            'total_price' => $this->total_price,
            'date' => $this->date,
            'preparation_time' => $this->preparation_time,
            'current_status_at' => $this->current_status_at->format('Y-m-d H:i:s'),
            'prepared_by' => $this->prepared_by,
            'delivery_time' => $this->delivery_time,
            'delivered_by' => $this->delivered_by,
            'total_time' => $this->total_time,
            'opened_at' => $this->opened_at,
            'orderItems' => $this->orderItems,
            'current_employee' => $currentEmployee
        ];
    }
}
