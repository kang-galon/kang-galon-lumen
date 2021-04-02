<?php

namespace App\Http\Controllers\Depot;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DepotController extends Controller
{
    public function getProfile()
    {
        $user = Auth::user();
        $depot = $user->depot;
        $data = [
            'name' => $user->name,
            'uid' => $user->uid,
            'phone_number' => $user->phone_number,
            'location' => $depot->location,
            'address' => $depot->address,
            'is_open' => $depot->is_open,
            'is_open_description' => $depot->is_open ? 'Buka' : 'Tutup',
            'status' => $user->status,
            'status_description' => 'Depot'
        ];

        return $this->response($data);
    }
}
