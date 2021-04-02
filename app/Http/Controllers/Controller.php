<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function invalidValidResponse(Request $request, array $validate, string $message = 'Invalid validate')
    {
        $validator = Validator::make($request->all(), $validate);

        if ($validator->fails()) {
            $data = [];

            // get first error message
            $errors = json_decode($validator->errors(), true);
            $keys = array_keys($errors);

            foreach ($keys as $key) {
                array_push($data, [
                    $key => $errors[$key][0]
                ]);
                error_log(json_encode([$key => $errors[$key][0]]));
            }

            return response()->json([
                'success' => false,
                'message' => $message,
                'data' => $data
            ], 422);
        }
    }
}
