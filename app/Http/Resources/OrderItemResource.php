<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'name' => $this->name,
            'quantity' => $this->quantity,
            'photo_url' => $this->photo_url,
            'sub_total_price' => $this->sub_total_price,
        ];
    }
}
