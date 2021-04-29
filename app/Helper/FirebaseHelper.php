<?php

namespace App\Helper;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseHelper
{
    static function sendNotification(string $deviceId, string $title, string $body)
    {
        $message = CloudMessage::withTarget('token', $deviceId)
            ->withNotification([
                'title' => $title,
                'body' => $body
            ]);

        Firebase::messaging()->send($message);
    }
}
