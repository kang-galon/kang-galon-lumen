<?php

namespace App\Http\Resources\Depot\Transaction;

use App\Helper\Util;
use Illuminate\Http\Resources\Json\JsonResource;

class AllCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'depot_phone_number' => $this->depot_phone_number,
            'client_phone_number' => $this->client_phone_number,
            'client_location' => $this->client_location,
            'total_price' => $this->total_price,
            'total_price_description' => 'Rp. ' . number_format($this->total_price),
            'status' => $this->status,
            'status_description' => Util::transactionStatus($this->status),
            'gallon' => $this->gallon,
            'rating' => $this->rating,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
