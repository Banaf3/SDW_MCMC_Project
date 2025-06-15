<?php

// Test script for notification system
require_once 'vendor/autoload.php'; 

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\NotificationService;
use App\Models\Notification;
use App\Models\Inquiry;

// Test the notification service
$notificationService = new NotificationService();

echo "Testing Notification System...\n\n";

// Test getting notifications for user ID 1
echo "1. Getting notifications for user ID 1:\n";
$notifications = $notificationService->getUserNotifications(1);
echo "Found " . $notifications->count() . " notifications\n";

foreach ($notifications as $notification) {
    echo "- " . $notification->title . ": " . $notification->message . "\n";
}

echo "\n2. Getting unread count for user ID 1:\n";
$unreadCount = $notificationService->getUnreadCount(1);
echo "Unread notifications: " . $unreadCount . "\n";

echo "\n3. Formatting notifications for frontend:\n";
$formatted = $notificationService->formatNotificationsForFrontend($notifications);
echo "Formatted notifications: " . json_encode($formatted->toArray(), JSON_PRETTY_PRINT) . "\n";

echo "\nTest completed successfully!\n";

?>
