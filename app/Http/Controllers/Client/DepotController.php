<?php

namespace App\Http\Controllers\Client;

use App\Helper\Util;
use App\Http\Controllers\Controller;
use App\Models\Depot;
use Illuminate\Http\Request;

class DepotController extends Controller
{
    public function getDepot(Request $request)
    {
        $this->invalidValidResponse($request, [
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $myLat = $request->latitude;
        $myLong = $request->longitude;
        $depots = Depot::all();

        $data = [];
        foreach ($depots as $depot) {
            $locationArray = explode(',', $depot->location);
            $latitude = (float)$locationArray[0];
            $longitude = (float)$locationArray[1];
            $distance = Util::distance($myLat, $myLong, $latitude, $longitude);

            // if depot distance below 1 km
            if ($distance < 1) {
                array_push($data, [
                    'phone_number' => $depot->phone_number,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'address' => $depot->address,
                    'rating' => $depot->rating,
                    'price' => $depot->price,
                    'price_description' => 'Rp. ' . number_format($depot->price),
                    'is_open' => $depot->is_open,
                    'is_open_description' => $depot->is_open ? 'Buka' : 'Tutup',
                    'distance' => round($distance, 2),
                    'image' => $depot->image,
                ]);
            }
        }

        // sort distance asc
        usort($data, function ($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

        return $this->response($data);
    }
}
