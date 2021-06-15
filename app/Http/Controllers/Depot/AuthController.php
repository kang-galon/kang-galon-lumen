<?php

namespace App\Http\Controllers\Depot;

use App\Exceptions\ResponseException;
use App\Http\Controllers\Controller;
use App\Models\Depot;
use App\Models\User;
use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Lcobucci\JWT\UnencryptedToken;

class AuthController extends Controller
{
    public function checkUser(Request $request)
    {
        $this->invalidValidResponse($request, [
            'phone_number' => 'required|numeric|starts_with:+628',
        ]);

        $user = User::where('phone_number', $request->phone_number)
            ->where('status', 1)
            ->first();
        if ($user == null) {
            return $this->response(null, 'Phone number doesn\'t exist', 404);
        }

        return $this->response(null, 'Phone number exist');
    }

    public function register(Request $request)
    {
        $this->invalidValidResponse($request, [
            'phone_number' => 'required|numeric|starts_with:+628|unique:users,phone_number',
            'name' => 'required',
            'location' => 'required',
            'address' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpg,jpeg,png',
            'uid' => 'required',
            'device_id' => 'required',
            'token' => 'required'
        ]);

        // verify if uid exist
        $apiToken = $request->token;
        $auth = Firebase::auth();
        $verifiedToken = $auth->verifyIdToken($apiToken);

        if ($verifiedToken instanceof UnencryptedToken) {
            $uid = $verifiedToken->claims()->get('sub');
            if ($uid != $request->uid) {
                throw new ResponseException('Invalid UID');
            }

            // update name
            $auth->updateUser($uid, ['displayName' => $request->name]);
        }

        // save image to storage
        $file = $request->file('image');
        $path = $file->path();
        $ext = $file->extension();
        $fileName = $request->phone_number . '.' . $ext;

        // upload to firebase
        $storage = Firebase::storage();
        $bucket = $storage->getBucket();
        $result = $bucket->upload(fopen($path, 'r'), [
            'resumable' => true,
            'name' => $fileName,
            'predefinedAcl' => 'publicRead',
        ]);
        $imageUrl = 'https://storage.googleapis.com/' . $result->info()['bucket'] . '/' . $fileName;

        User::create([
            'phone_number' => $request->phone_number,
            'name' => $request->name,
            'uid' => $request->uid,
            'device_id' => $request->device_id,
            'status' => 1,
        ]);

        Depot::create([
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'price' => $request->price,
            'image' => $imageUrl,
            'location' => $request->location,
            'is_open' => true
        ]);

        return $this->response(null, 'Registration depot success', 201);
    }
}
