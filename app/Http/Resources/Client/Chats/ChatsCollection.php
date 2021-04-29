<?php

namespace App\Http\Resources\Client\Chats;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ChatsCollection extends JsonResource
{
    public function toArray($request)
    {
        $client = Auth::user();

        return [
            'message' => $this->message,
            'is_me' => $this->sender == $client->phone_number,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
