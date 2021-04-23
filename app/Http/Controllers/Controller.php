<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;
use phpDocumentor\Reflection\Types\Boolean;

class Controller extends BaseController
{
    protected function invalidValidResponse(Request $request, array $validate)
    {
        $validator = Validator::make($request->all(), $validate);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return null;
    }

    protected function response(mixed $data, string $message = 'Success', int $statusCode = 200)
    {
        return response()->json([
            'success' => ($statusCode >= 400) ? false : true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}
