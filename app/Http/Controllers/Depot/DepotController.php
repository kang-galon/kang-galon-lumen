<?php

namespace App\Http\Controllers\Depot;

use App\Http\Controllers\Controller;
use App\Http\Resources\Depot\DetailCollection;
use App\Models\Depot;
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
            'image' => $depot->image,
            'price' => $depot->price,
            'price_description' => 'Rp. ' . number_format($depot->price),
            'is_open' => $depot->is_open,
            'is_open_description' => $depot->is_open ? 'Buka' : 'Tutup',
            'status' => $user->status,
            'status_description' => 'Depot'
        ];

        return $this->response($data);
    }

    public function openDepot()
    {
        $user = Auth::user();
        $depot = Depot::where('phone_number', $user->phone_number)->first();
        $depot->is_open = true;

        $depot->save();
        return $this->response(new DetailCollection($depot), 'Success change depot to open', 201);
    }

    public function closeDepot()
    {
        $user = Auth::user();
        $depot = Depot::where('phone_number', $user->phone_number)->first();
        $depot->is_open = false;

        $depot->save();
        return $this->response(new DetailCollection($depot), 'Success change depot to close', 201);
    }
}
