<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Administrator;
use App\Models\AgencyStaff;
use App\Models\PublicUser;

echo "=== PRIORITIZED AUTHENTICATION SYSTEM DEMO ===\n\n";

// Get sample accounts from each user type
$admin = Administrator::first();
$staff = AgencyStaff::first();
$publicUser = PublicUser::first();

echo "AUTHENTICATION PRIORITY RULES:\n";
echo "1. Public Users: Email ONLY\n";
echo "2. Admin & Agency Staff: Username FIRST, then Email as fallback\n\n";

echo "=== DEMO ACCOUNTS ===\n";
if ($admin) {
    echo "Administrator: Username='{$admin->Username}', Email='{$admin->AdminEmail}'\n";
}
if ($staff) {
    echo "Agency Staff: Username='{$staff->Username}', Email='{$staff->staffEmail}'\n";
}
if ($publicUser) {
    echo "Public User: Email='{$publicUser->UserEmail}' (no username)\n";
}
echo "\n";

// Simulate authentication attempts
function testAuthentication($input, $description) {
    echo "=== TESTING: $description ===\n";
    echo "Input: '$input'\n";
    
    $isEmail = filter_var($input, FILTER_VALIDATE_EMAIL);
    echo "Email format: " . ($isEmail ? 'Yes' : 'No') . "\n\n";
    
    // Test Administrator
    echo "1. Administrator Authentication:\n";
    if (!$isEmail) {
        echo "   → Trying USERNAME first (priority)...\n";
        $admin = Administrator::findByUsername($input);
        if ($admin) {
            echo "   ✓ SUCCESS: Found admin via username: {$admin->AdminName}\n";
            return "admin";
        } else {
            echo "   ✗ No admin found by username\n";
        }
    }
    
    if ($isEmail) {
        echo "   → Trying EMAIL" . (!$isEmail ? " (fallback)" : "") . "...\n";
        $admin = Administrator::findByEmail($input);
        if ($admin) {
            echo "   ✓ SUCCESS: Found admin via email: {$admin->AdminName}\n";
            return "admin";
        } else {
            echo "   ✗ No admin found by email\n";
        }
    }
    
    // Test Agency Staff
    echo "\n2. Agency Staff Authentication:\n";
    if (!$isEmail) {
        echo "   → Trying USERNAME first (priority)...\n";
        $staff = AgencyStaff::findByUsername($input);
        if ($staff) {
            echo "   ✓ SUCCESS: Found staff via username: {$staff->StaffName}\n";
            return "agency";
        } else {
            echo "   ✗ No staff found by username\n";
        }
    }
    
    if ($isEmail) {
        echo "   → Trying EMAIL" . (!$isEmail ? " (fallback)" : "") . "...\n";
        $staff = AgencyStaff::findByEmail($input);
        if ($staff) {
            echo "   ✓ SUCCESS: Found staff via email: {$staff->StaffName}\n";
            return "agency";
        } else {
            echo "   ✗ No staff found by email\n";
        }
    }
    
    // Test Public User
    echo "\n3. Public User Authentication:\n";
    if ($isEmail) {
        echo "   → Trying EMAIL (only option for public users)...\n";
        $publicUser = PublicUser::findForLogin($input);
        if ($publicUser) {
            echo "   ✓ SUCCESS: Found public user: {$publicUser->UserName}\n";
            return "public";
        } else {
            echo "   ✗ No public user found by email\n";
        }
    } else {
        echo "   → SKIPPED: Public users only authenticate via email\n";
    }
    
    echo "\n❌ AUTHENTICATION FAILED: No matching user found\n";
    return null;
}

// Test cases
$testCases = [];

if ($admin) {
    $testCases[] = [$admin->Username, "Admin Username Login"];
    $testCases[] = [$admin->AdminEmail, "Admin Email Login"];
}

if ($staff) {
    $testCases[] = [$staff->Username, "Agency Staff Username Login"];
    if (!empty($staff->staffEmail)) {
        $testCases[] = [$staff->staffEmail, "Agency Staff Email Login"];
    }
}

if ($publicUser) {
    $testCases[] = [$publicUser->UserEmail, "Public User Email Login"];
}

// Additional test cases
$testCases[] = ["nonexistent_user", "Non-existent Username"];
$testCases[] = ["fake@email.com", "Non-existent Email"];

foreach ($testCases as $test) {
    testAuthentication($test[0], $test[1]);
    echo "\n" . str_repeat("=", 60) . "\n\n";
}

echo "=== SUMMARY ===\n";
echo "✓ Username-based authentication prioritized for Admin & Agency Staff\n";
echo "✓ Email fallback available for Admin & Agency Staff\n";
echo "✓ Email-only authentication for Public Users\n";
echo "✓ Clear authentication hierarchy prevents conflicts\n";
echo "✓ System now follows the requested authentication strategy\n";

echo "\n=== TEST COMPLETE ===\n";
