<?php
require_once 'vendor/autoload.php';

use App\Models\Inquiry;

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get all inquiries and their statuses
$inquiries = Inquiry::all();

echo "Total inquiries found: " . $inquiries->count() . "\n\n";

foreach ($inquiries as $inquiry) {
    echo "ID: " . $inquiry->InquiryID . "\n";
    echo "Title: " . $inquiry->InquiryTitle . "\n";
    echo "Status: '" . $inquiry->InquiryStatus . "'\n";
    echo "Transformed: '" . strtolower(str_replace(' ', '-', $inquiry->InquiryStatus)) . "'\n";
    echo "---\n";
}
?>
