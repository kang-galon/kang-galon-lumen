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
            'phone_number' => $this->phone_number,
            'name' => $this->user->name,
            'image' => $this->image,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'address' => $this->address,
            'price' => $this->price,
            'price_description' => 'Rp. ' . number_format($this->price),
            'is_open' => $this->is_open,
            'is_open_description' => $this->is_open ? 'Buka' : 'Tutup',
        ];
    }
}
