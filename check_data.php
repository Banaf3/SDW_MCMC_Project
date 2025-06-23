<?php

require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\Inquiry;
use App\Models\Agency;

echo "=== Database Status Check ===\n";
echo "Total inquiries: " . Inquiry::count() . "\n";
echo "Total agencies: " . Agency::count() . "\n";
echo "Assigned inquiries: " . Inquiry::whereNotNull('AgencyID')->count() . "\n";
echo "Unassigned inquiries: " . Inquiry::whereNull('AgencyID')->count() . "\n";

echo "\n=== Sample Agencies ===\n";
$agencies = Agency::take(5)->get();
foreach ($agencies as $agency) {
    echo "- {$agency->AgencyName} (ID: {$agency->AgencyID})\n";
}

echo "\n=== Sample Inquiries ===\n";
$inquiries = Inquiry::with('assignedAgency')->take(5)->get();
foreach ($inquiries as $inquiry) {
    $agencyName = $inquiry->assignedAgency ? $inquiry->assignedAgency->AgencyName : 'Unassigned';
    echo "- Inquiry #{$inquiry->InquiryID}: {$inquiry->InquiryTitle} (Agency: {$agencyName})\n";
}

// Create some test assignments if needed
$unassignedCount = Inquiry::whereNull('AgencyID')->count();
$agencyCount = Agency::count();

if ($unassignedCount > 0 && $agencyCount > 0) {
    echo "\n=== Creating Test Assignments ===\n";
    $unassigned = Inquiry::whereNull('AgencyID')->take(min(10, $unassignedCount))->get();
    $agencies = Agency::all();
    
    foreach ($unassigned as $index => $inquiry) {
        $agency = $agencies[$index % $agencies->count()];
        $inquiry->AgencyID = $agency->AgencyID;
        $inquiry->InquiryStatus = ['Under Investigation', 'Verified as True', 'Identified as Fake'][rand(0, 2)];
        $inquiry->save();
        echo "Assigned Inquiry #{$inquiry->InquiryID} to {$agency->AgencyName}\n";
    }
}

echo "\n=== Final Count ===\n";
echo "Assigned inquiries: " . Inquiry::whereNotNull('AgencyID')->count() . "\n";
