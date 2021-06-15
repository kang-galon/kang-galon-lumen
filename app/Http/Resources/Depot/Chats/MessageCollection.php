<?php

namespace App\Http\Resources\Depot\Chats;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MessageCollection extends JsonResource
{
    public function toArray($request)
    {
        $depot = Auth::user();
        return [
            'message' => $this->message,
            'is_me' => $this->sender == $depot->phone_number,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
