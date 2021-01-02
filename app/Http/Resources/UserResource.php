<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'photo_url' => $this->photo_url,
            'type' => $this->type,
            'blocked' => $this->blocked,
            'logged_at' => $this->logged_at,
            'available_at' => $this->available_at
        ];
    }
}
