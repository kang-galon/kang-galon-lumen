<?php

namespace App\Http\Controllers;

use App\Exceptions\ResponseException;
use GuzzleHttp\Psr7\MimeType;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImageController extends Controller
{
    public function getImage($type, $fileName)
    {
        $filePath = 'app/' . $type . '/' . $fileName . '.*';
        $glob = File::glob(storage_path($filePath));

        if (count($glob) > 0) {
            $path = $glob[0];
            $mime = MimeType::fromFilename($path);
            $headers = ['Content-Type' => $mime];
            $response = new BinaryFileResponse($path, 200, $headers);
            return $response;
        }

        throw new ResponseException('File not found', 404);
    }
}
