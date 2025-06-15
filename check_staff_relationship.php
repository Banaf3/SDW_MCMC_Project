<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check if AdminID in inquiries corresponds to StaffID in agency_staff
echo "Checking AdminID vs StaffID relationship:\n\n";

$inquiries = DB::table('inquiries')->select('InquiryID', 'AdminID', 'AgencyID')->limit(5)->get();
$staff = DB::table('agency_staff')->select('StaffID', 'StaffName', 'AgencyID')->limit(10)->get();

echo "Sample Inquiries:\n";
foreach ($inquiries as $inquiry) {
    echo "InquiryID: {$inquiry->InquiryID}, AdminID: {$inquiry->AdminID}, AgencyID: {$inquiry->AgencyID}\n";
}

echo "\nSample Staff:\n";
foreach ($staff as $staffMember) {
    echo "StaffID: {$staffMember->StaffID}, StaffName: {$staffMember->StaffName}, AgencyID: {$staffMember->AgencyID}\n";
}

// Check if AdminID matches StaffID
echo "\nChecking if AdminID = StaffID:\n";
foreach ($inquiries as $inquiry) {
    $matchingStaff = $staff->where('StaffID', $inquiry->AdminID)->first();
    if ($matchingStaff) {
        echo "Match found! AdminID {$inquiry->AdminID} = StaffID {$matchingStaff->StaffID}, Staff: {$matchingStaff->StaffName}\n";
    } else {
        echo "No match for AdminID {$inquiry->AdminID}\n";
    }
}
?>
