<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Laravel\Firebase\Facades\Firebase;

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

    public function updateProfile(Request $request)
    {
        $this->invalidValidResponse($request, [
            'name' => 'required',
        ]);

        // update name in server
        $userPhone = Auth::user()->phone_number;
        $user = User::where('phone_number', $userPhone)->first();
        $user->name = $request->name;
        $user->save();

        // update name in firebase
        $auth = Firebase::auth();
        $uid = $user->uid;
        $auth->updateUser($uid, ['displayName' => $request->name]);

        return $this->response([
            'name' => $request->name
        ], 'Success update profile');
    }
}
