<?php

namespace App\Http\Controllers\Depot;

use App\Http\Controllers\Controller;
use App\Http\Resources\Depot\DetailCollection;
use App\Models\Depot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Laravel\Firebase\Facades\Firebase;

class DepotController extends Controller
{
    public function getProfile()
    {
        $user = Auth::user();
        $depot = $user->depot;

        return $this->response(new DetailCollection($depot), 'Success update profile', 201);
    }

    public function updateProfile(Request $request)
    {
        $this->invalidValidResponse($request, [
            'name' => 'required',
            'location' => 'required',
            'address' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpg,jpeg,png',
        ]);

        $phoneNumber = Auth::user()->phone_number;
        $depot = Depot::where('phone_number', $phoneNumber)->first();
        $user = User::where('phone_number', $phoneNumber)->first();

        // save image to storage
        $file = $request->file('image');
        $path = $file->path();
        $ext = $file->extension();
        $fileName = $phoneNumber . '.' . $ext;

        // upload to firebase
        $storage = Firebase::storage();
        $bucket = $storage->getBucket();
        $result = $bucket->upload(fopen($path, 'r'), [
            'resumable' => true,
            'name' => $fileName,
            'predefinedAcl' => 'publicRead',
        ]);
        $imageUrl = 'https://storage.googleapis.com/' . $result->info()['bucket'] . '/' . $fileName;

        $user->name = $request->name;
        $depot->location = $request->location;
        $depot->image = $imageUrl;
        $depot->price = $request->price;
        $depot->address = $request->address;

        $user->save();
        $depot->save();

        return $this->response(new DetailCollection($depot), 'Success update profile', 201);
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
