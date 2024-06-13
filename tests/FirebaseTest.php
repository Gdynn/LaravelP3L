<?php

require 'vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\MessagingException;

$serviceAccountPath = 'storage/app/firebase/pushhnotif-7e106-firebase-adminsdk-7fe9h-1ae7f33070.json';

try {
    $factory = (new Factory)->withServiceAccount($serviceAccountPath);
    $messaging = $factory->createMessaging();

    // Ganti 'YOUR_VALID_FCM_TOKEN' dengan token FCM yang valid
    $token = 'd6eOsNmeTKiSYOlLVE3UNY:APA91bHNnQ5-eQQH9k-K4CjDDBJBt-qY2J-SaM2l4TZcsxgf6gdi1q5z9X05_EUg8pyKX0TPP2XdhVaywetBJpYoK52ZY4a61sENgJVo_PuV3GdXbrmYduL5JCQ6Dabd53uxqYzo2-6G';

    $message = CloudMessage::withTarget('token', $token)
        ->withNotification(Notification::create('Test Notification', 'This is a test notification'));

    $messaging->send($message);

    echo "Notification sent successfully!\n";
} catch (MessagingException $e) {
    echo 'Error sending notification: ' . $e->getMessage() . "\n";
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
