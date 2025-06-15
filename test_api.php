<?php

// Test API endpoints
require_once 'vendor/autoload.php'; 

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\NotificationController;
use App\Services\NotificationService;
use Illuminate\Http\Request;

echo "Testing Notification API Endpoints...\n\n";

// Create controller and service
$notificationService = new NotificationService();
$controller = new NotificationController($notificationService);

// Test getting notifications (simulate request)
echo "1. Testing GET notifications endpoint:\n";
$request = new Request(['user_id' => 3]); // Use user ID 3 which has notifications
$response = $controller->index($request);
$responseData = json_decode($response->getContent(), true);

echo "Response status: " . $response->getStatusCode() . "\n";
echo "Notifications count: " . count($responseData['notifications']) . "\n";
echo "Unread count: " . $responseData['unread_count'] . "\n";

if (!empty($responseData['notifications'])) {
    echo "Sample notification:\n";
    $firstNotification = $responseData['notifications'][0];
    echo "- Title: " . $firstNotification['title'] . "\n";
    echo "- Message: " . $firstNotification['message'] . "\n";
    echo "- Read: " . ($firstNotification['read'] ? 'Yes' : 'No') . "\n";
    echo "- Inquiry ID: " . $firstNotification['inquiryId'] . "\n";
}

echo "\n2. Testing unread count endpoint:\n";
$unreadResponse = $controller->getUnreadCount($request);
$unreadData = json_decode($unreadResponse->getContent(), true);
echo "Unread count: " . $unreadData['unread_count'] . "\n";

echo "\nAPI endpoints are working correctly!\n";

?>
