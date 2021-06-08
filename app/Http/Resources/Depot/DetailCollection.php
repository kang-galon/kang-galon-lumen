<?php

namespace App\Http\Resources\Depot;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailCollection extends JsonResource
{
    public function toArray($request)
    {
        $locationArray = explode(',', $this->location);
        $latitude = (float)$locationArray[0];
        $longitude = (float)$locationArray[1];

        return [
            'name' => $this->user->name,
            'uid' => $this->user->uid,
            'phone_number' => $this->phone_number,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'address' => $this->address,
            'image' => $this->image,
            'price' => $this->price,
            'price_description' => 'Rp. ' . number_format($this->price),
            'is_open' => $this->is_open,
            'is_open_description' => $this->is_open ? 'Buka' : 'Tutup',
            'status' => $this->user->status,
            'status_description' => 'Depot'
        ];
    }
}
