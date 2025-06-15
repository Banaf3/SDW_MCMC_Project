<?php

// Check inquiry database content and filtering
require_once 'vendor/autoload.php'; 

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inquiry;
use App\Models\PublicUser;

echo "=== INQUIRY DATABASE ANALYSIS ===\n\n";

// 1. Check total inquiries in database
$totalInquiries = Inquiry::count();
echo "1. Total inquiries in database: {$totalInquiries}\n\n";

// 2. Check inquiries by user
echo "2. Inquiries by user:\n";
$userCounts = Inquiry::selectRaw('UserID, COUNT(*) as count')
    ->groupBy('UserID')
    ->orderBy('count', 'desc')
    ->get();

foreach ($userCounts as $userCount) {
    $user = PublicUser::find($userCount->UserID);
    $userName = $user ? $user->UserName : 'Unknown';
    echo "   - User ID {$userCount->UserID} ({$userName}): {$userCount->count} inquiries\n";
}

// 3. Check what the controller is currently doing
echo "\n3. Current controller filtering (User ID 3 only):\n";
$currentUserInquiries = Inquiry::where('UserID', 3)->count();
echo "   - Inquiries for User ID 3: {$currentUserInquiries}\n";

// 4. Show all inquiries details
echo "\n4. All inquiries in database:\n";
$allInquiries = Inquiry::with('user')->orderBy('InquiryID')->get();
foreach ($allInquiries as $inquiry) {
    $userName = $inquiry->user ? $inquiry->user->UserName : 'Unknown';
    echo "   - ID: {$inquiry->InquiryID}, Title: '{$inquiry->InquiryTitle}', User: {$userName} (ID: {$inquiry->UserID}), Status: {$inquiry->InquiryStatus}\n";
}

// 5. Check what happens without user filtering
echo "\n5. Without user filtering (showing all inquiries):\n";
$allInquiriesCount = Inquiry::count();
echo "   - Total would show: {$allInquiriesCount} inquiries\n";

echo "\n=== ANALYSIS COMPLETE ===\n";
echo "The issue is likely that the controller is filtering by UserID = 3,\n";
echo "which limits the results to only that user's inquiries.\n";
echo "If you want to show all inquiries, remove the user filter.\n";

?>
