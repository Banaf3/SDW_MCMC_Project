<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Administrator;
use App\Models\AgencyStaff;
use App\Models\PublicUser;

echo "=== CURRENT DATABASE ACCOUNTS ===\n\n";

echo "ADMINISTRATORS:\n";
$admins = Administrator::all();
foreach ($admins as $admin) {
    echo "- ID: {$admin->AdminID}, Username: '{$admin->Username}', Email: '{$admin->AdminEmail}', Name: '{$admin->AdminName}'\n";
}

echo "\nAGENCY STAFF:\n";
$staff = AgencyStaff::all();
foreach ($staff as $s) {
    echo "- ID: {$s->StaffID}, Username: '{$s->Username}', Email: '{$s->staffEmail}', Name: '{$s->StaffName}'\n";
}

echo "\nPUBLIC USERS:\n";
$users = PublicUser::all();
foreach ($users as $user) {
    echo "- ID: {$user->UserID}, Email: '{$user->UserEmail}', Name: '{$user->UserName}'\n";
}

echo "\n=== AUTHENTICATION TESTS WITH ACTUAL DATA ===\n\n";

// Test with actual data
if ($admins->count() > 0) {
    $testAdmin = $admins->first();
    echo "Testing Administrator with username: '{$testAdmin->Username}'\n";
    $found = Administrator::findByUsername($testAdmin->Username);
    echo $found ? "✓ Found via username\n" : "✗ Not found\n";
    
    echo "Testing Administrator with email: '{$testAdmin->AdminEmail}'\n";
    $found = Administrator::findByEmail($testAdmin->AdminEmail);
    echo $found ? "✓ Found via email\n" : "✗ Not found\n";
    echo "---\n";
}

if ($staff->count() > 0) {
    $testStaff = $staff->first();
    echo "Testing AgencyStaff with username: '{$testStaff->Username}'\n";
    $found = AgencyStaff::findByUsername($testStaff->Username);
    echo $found ? "✓ Found via username\n" : "✗ Not found\n";
    
    if (!empty($testStaff->staffEmail)) {
        echo "Testing AgencyStaff with email: '{$testStaff->staffEmail}'\n";
        $found = AgencyStaff::findByEmail($testStaff->staffEmail);
        echo $found ? "✓ Found via email\n" : "✗ Not found\n";
    } else {
        echo "AgencyStaff has no email to test\n";
    }
    echo "---\n";
}

if ($users->count() > 0) {
    $testUser = $users->first();
    echo "Testing PublicUser with email: '{$testUser->UserEmail}'\n";
    $found = PublicUser::findForLogin($testUser->UserEmail);
    echo $found ? "✓ Found via email\n" : "✗ Not found\n";
}

echo "\n=== TEST COMPLETE ===\n";
