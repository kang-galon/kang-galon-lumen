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
    public function register(Request $request)
    {
        $this->invalidValidResponse($request, [
            'phone_number' => 'required|numeric|starts_with:+628|unique:user,phone_number',
            'name' => 'required',
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
        }

        User::create([
            'phone_number' => $request->phone_number,
            'name' => $request->name,
            'uid' => $request->uid,
            'status' => 2,
        ]);

        return $this->response(null, 'Registration success', 201);
    }
}
