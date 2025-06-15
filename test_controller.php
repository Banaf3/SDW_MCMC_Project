<?php

// Test the updated inquiry controller
require_once 'vendor/autoload.php'; 

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\InquiryController;

echo "Testing updated InquiryController...\n\n";

$controller = new InquiryController();

// Capture the view data
ob_start();
$view = $controller->index();
$viewData = $view->getData();
ob_end_clean();

echo "Results from updated controller:\n";
echo "- Total inquiries: " . count($viewData['inquiries']) . "\n";
echo "- Current User ID: " . $viewData['currentUserId'] . "\n\n";

echo "Inquiry list:\n";
foreach ($viewData['inquiries'] as $inquiry) {
    echo "- ID: {$inquiry['id']}, Title: '{$inquiry['title']}', Status: {$inquiry['status']}\n";
}

echo "\nSuccess! Now showing all " . count($viewData['inquiries']) . " inquiries instead of just 4.\n";

?>
