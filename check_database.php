<?php

// Check database content and create test notification
require_once 'vendor/autoload.php'; 

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PublicUser;
use App\Models\Inquiry;
use App\Models\Notification;
use App\Services\NotificationService;

echo "Checking database content...\n\n";

// Check users
$users = PublicUser::all();
echo "Users in database: " . $users->count() . "\n";
foreach ($users as $user) {
    echo "- User ID: " . $user->UserID . ", Name: " . $user->UserName . "\n";
}

// Check inquiries
echo "\nInquiries in database: " . Inquiry::count() . "\n";
$inquiries = Inquiry::limit(3)->get();
foreach ($inquiries as $inquiry) {
    echo "- Inquiry ID: " . $inquiry->InquiryID . ", Title: " . $inquiry->InquiryTitle . ", User ID: " . $inquiry->UserID . "\n";
}

// Create a test notification if we have users and inquiries
if ($users->count() > 0 && $inquiries->count() > 0) {
    echo "\nCreating test notification...\n";
    
    $notificationService = new NotificationService();
    $notification = $notificationService->createStatusUpdateNotification(
        $inquiries->first()->InquiryID,
        'Under Investigation',
        null
    );
    
    if ($notification) {
        echo "Test notification created successfully!\n";
        echo "Notification ID: " . $notification->id . "\n";
        echo "User ID: " . $notification->user_id . "\n";
        echo "Message: " . $notification->message . "\n";
    } else {
        echo "Failed to create notification\n";
    }
}

// Check all notifications
echo "\nAll notifications in database: " . Notification::count() . "\n";
$allNotifications = Notification::all();
foreach ($allNotifications as $notification) {
    echo "- ID: " . $notification->id . ", User: " . $notification->user_id . ", Read: " . ($notification->read_at ? 'Yes' : 'No') . "\n";
}

?>
