<?php

namespace App\Helpers;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class FirebaseHelper
{
    protected $messaging;

    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(config('firebase.credentials'));

        $this->messaging = $firebase->createMessaging();
    }

    public function sendNotification($token, $title, $body)
    {
        try {
            $message = CloudMessage::withTarget('token', $token)
                ->withNotification(Notification::create($title, $body));

            $this->messaging->send($message);
            Log::info("Notifikasi berhasil dikirim ke token: $token");
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi: ' . $e->getMessage());
        }
    }
}
