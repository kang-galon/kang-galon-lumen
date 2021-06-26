<?php

namespace App\Http\Controllers\Client;

use App\Exceptions\ResponseException;
use App\Http\Controllers\Controller;
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

        User::create([
            'phone_number' => $request->phone_number,
            'name' => $request->name,
            'uid' => $request->uid,
            'device_id' => $request->device_id,
            'status' => 2,
        ]);

        return $this->response([
            'phone_number' => $request->phone_number,
            'name' => $request->name,
            'uid' => $request->uid
        ], 'Registration client success', 201);
    }
}
