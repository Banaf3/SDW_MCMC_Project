<?php

// Test the AgencyController to verify user names are working
require_once 'vendor/autoload.php'; 

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\AgencyController;
use App\Services\NotificationService;
use Illuminate\Http\Request;

echo "Testing AgencyController user name display...\n\n";

// Create controller
$notificationService = new NotificationService();
$controller = new AgencyController($notificationService);

// Create a mock request
$request = new Request();

// Get the assigned inquiries
ob_start();
$view = $controller->assignedInquiries($request);
$viewData = $view->getData();
ob_end_clean();

echo "Results from AgencyController:\n";
echo "- Total inquiries: " . count($viewData['inquiries']) . "\n\n";

echo "Inquiry details with user names:\n";
foreach ($viewData['inquiries'] as $inquiry) {
    echo "- ID: {$inquiry['InquiryID']}\n";
    echo "  Title: {$inquiry['title']}\n";
    echo "  Submitted By: {$inquiry['submittedBy']}\n";
    echo "  Status: {$inquiry['status']}\n";
    echo "  Date: {$inquiry['submittedDate']}\n\n";
}

echo "Test completed! User names should now display correctly.\n";

?>
