<?php

namespace App\Http\Controllers\Depot;

use App\Exceptions\ResponseException;
use App\Http\Controllers\Controller;
use App\Models\Depot;
use App\Models\User;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Laravel\Firebase\FirebaseProject;
use Lcobucci\JWT\UnencryptedToken;

class AuthController extends Controller
{
    public function checkUser(Request $request)
    {
        $this->invalidValidResponse($request, [
            'phone_number' => 'required|numeric|starts_with:+628',
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();
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
            'image' => 'required|image|mimes:jpg,jpeg,png',
            'uid' => 'required',
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
        $mime = $file->getClientOriginalExtension();
        $fileName = $request->phone_number . '.' . $mime;
        $path = Storage::putFileAs('depot', $request->file('image'), $fileName);

        // upload to firebase
        $storage = Firebase::storage();
        $bucket = $storage->getBucket();
        $path = Storage::path('depot/' . $fileName);
        $result = $bucket->upload(fopen($path, 'r'), [
            'resumable' => true,
            'name' => $fileName,
            'predefinedAcl' => 'publicRead',
        ]);
        $imageUrl = 'https://storage.googleapis.com/' . $result->info()['bucket'] . '/' . $fileName;

        // delete
        Storage::delete('depot/' . $fileName);

        User::create([
            'phone_number' => $request->phone_number,
            'name' => $request->name,
            'uid' => $request->uid,
            'status' => 2,
        ]);

        Depot::create([
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'image' => $imageUrl,
            'location' => $request->location,
            'is_open' => false
        ]);

        return $this->response(null, 'Registration depot success', 201);
    }
}
