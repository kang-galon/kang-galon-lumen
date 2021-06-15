<?php

namespace App\Http\Resources\Client\Transaction;

use App\Helper\Util;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailCollection extends JsonResource
{
    public function toArray($request)
    {
        $locationArray = explode(',', $this->depot->location);
        $latitude = (float)$locationArray[0];
        $longitude = (float)$locationArray[1];

        return [
            'id' => $this->id,
            'depot_phone_number' => $this->depot_phone_number,
            'depot_name' => $this->depot->user->name,
            'client_phone_number' => $this->client_phone_number,
            'client_location' => $this->client_location,
            'total_price' => $this->total_price,
            'total_price_description' => 'Rp. ' . number_format($this->total_price),
            'status' => $this->status,
            'status_description' => Util::transactionStatus($this->status),
            'gallon' => $this->gallon,
            'rating' => $this->rating,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'depot' => [
                'name' => $this->depot->user->name,
                'phone_number' => $this->depot->phone_number,
                'image' => $this->depot->image,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'address' => $this->depot->address,
                'rating' => $this->depot->rating,
                'price' => $this->depot->price,
                'price_description' => $this->depot->price_description,
                'is_open' => $this->depot->is_open,
                'is_open_description' => $this->depot->is_open ? 'Buka' : 'Tutup',
                'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            ],
        ];
    }
}
