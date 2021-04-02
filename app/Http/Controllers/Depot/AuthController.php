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
    public function register(Request $request)
    {
        $this->invalidValidResponse($request, [
            'phone_number' => 'required|numeric|starts_with:+628|unique:users,phone_number',
            'name' => 'required',
            'location' => 'required',
            'address' => 'required',
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

        User::create([
            'phone_number' => $request->phone_number,
            'name' => $request->name,
            'uid' => $request->uid,
            'status' => 2,
        ]);

        Depot::create([
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'location' => $request->location,
            'is_open' => false
        ]);

        return $this->response(null, 'Registration depot success', 201);
    }
}
