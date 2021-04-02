<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Kreait\Laravel\Firebase\Facades\Firebase;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return 'assd';
    }

    public function register(Request $request)
    {
        // return $this->invalidValidResponse($request, [
        //     'phone_number' => 'required|numeric|starts_with:628',
        //     'uid' => 'required',
        //     'token' => 'required'
        // ]);

        $auth = Firebase::auth();

        error_log('asdsd');
    }
}
