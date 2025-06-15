<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check if agency_staff table exists and has data
try {
    echo "Checking agency_staff table:\n";
    $staffCount = DB::table('agency_staff')->count();
    echo "Total staff records: $staffCount\n\n";
    
    if ($staffCount > 0) {
        $staff = DB::table('agency_staff')->limit(5)->get();
        foreach ($staff as $s) {
            echo "StaffID: {$s->StaffID}, StaffName: {$s->StaffName}, AgencyID: {$s->AgencyID}\n";
        }
    } else {
        echo "No staff records found. Let's check what AdminID refers to in administrators table:\n";
        $admins = DB::table('administrators')->select('AdminID', 'AdminName')->limit(10)->get();
        foreach ($admins as $admin) {
            echo "AdminID: {$admin->AdminID}, AdminName: {$admin->AdminName}\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
