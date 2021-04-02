<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function getProfile()
    {
        $user = Auth::user();
        $data = [
            'name' => $user->name,
            'uid' => $user->uid,
            'phone_number' => $user->phone_number,
            'status' => $user->status,
            'status_description' => 'Client'
        ];

        return $this->response($data);
    }
}
