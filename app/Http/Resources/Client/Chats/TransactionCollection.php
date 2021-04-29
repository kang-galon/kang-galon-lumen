<?php

namespace App\Http\Resources\Client\Chats;

use App\Models\Chats;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionCollection extends JsonResource
{
    public function toArray($request)
    {
        $userDepot = User::find($this->depot_phone_number);
        $locationArray = explode(',', $userDepot->depot->location);
        $latitude = (float)$locationArray[0];
        $longitude = (float)$locationArray[1];

        $userClient = User::find($this->client_phone_number);
        $chats = Chats::where('transaction_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'depot' => [
                'phone_number' => $userDepot->phone_number,
                'name' => $userDepot->name,
                'image' => $userDepot->depot->image,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'address' => $userDepot->depot->address,
                'rating' => $userDepot->depot->rating,
                'price' => $userDepot->depot->price,
                'price_description' => $userDepot->depot->price_description,
                'is_open' => $userDepot->depot->is_open,
                'is_open_description' => $userDepot->depot->is_open ? 'Buka' : 'Tutup',
                'created_at' => $userDepot->created_at ? $userDepot->created_at->format('Y-m-d H:i:s') : null,
            ],
            'client' => [
                'phone_number' => $userClient->phone_number,
                'name' => $userClient->name,
                'created_at' => $userClient->created_at ? $userClient->created_at->format('Y-m-d H:i:s') : null,
            ],
            'chats' => ChatsCollection::collection($chats),
        ];
    }
}
