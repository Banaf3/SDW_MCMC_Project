<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Administrator;
use App\Models\AgencyStaff;
use App\Models\PublicUser;

echo "=== TESTING PRIORITIZED AUTHENTICATION LOGIC ===\n\n";

// Test data from our existing test accounts
$testCases = [
    // Admin tests
    [
        'type' => 'Administrator',
        'input' => 'admin1',  // Username
        'expected' => 'admin1 (via username)',
        'method' => 'findByUsername'
    ],
    [
        'type' => 'Administrator', 
        'input' => 'admin1@admin.com',  // Email
        'expected' => 'admin1@admin.com (via email)',
        'method' => 'findByEmail'
    ],
    
    // Agency Staff tests
    [
        'type' => 'AgencyStaff',
        'input' => 'staff1',  // Username
        'expected' => 'staff1 (via username)',
        'method' => 'findByUsername'
    ],
    [
        'type' => 'AgencyStaff',
        'input' => 'staff1@agency.com',  // Email
        'expected' => 'staff1@agency.com (via email)',
        'method' => 'findByEmail'
    ],
    
    // Public User tests
    [
        'type' => 'PublicUser',
        'input' => 'user1@public.com',  // Email only
        'expected' => 'user1@public.com (email only)',
        'method' => 'findForLogin'
    ]
];

foreach ($testCases as $test) {
    echo "Testing {$test['type']} with input: '{$test['input']}'\n";
    echo "Method: {$test['method']}\n";
    
    try {
        $model = "App\\Models\\{$test['type']}";
        $method = $test['method'];
        
        $result = $model::$method($test['input']);
        
        if ($result) {
            if ($test['type'] === 'Administrator') {
                echo "✓ Found: {$result->Username} ({$result->AdminEmail})\n";
            } elseif ($test['type'] === 'AgencyStaff') {
                echo "✓ Found: {$result->Username} ({$result->staffEmail})\n";
            } elseif ($test['type'] === 'PublicUser') {
                echo "✓ Found: {$result->UserName} ({$result->UserEmail})\n";
            }
        } else {
            echo "✗ Not found\n";
        }
        
    } catch (Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
    
    echo "---\n";
}

echo "\n=== AUTHENTICATION PRIORITY SIMULATION ===\n\n";

// Simulate the new authentication logic
function simulateAuth($loginField) {
    $isEmail = filter_var($loginField, FILTER_VALIDATE_EMAIL);
    echo "Input: '$loginField' (Email format: " . ($isEmail ? 'Yes' : 'No') . ")\n";
    
    // Admin authentication
    echo "1. Trying Administrator:\n";
    $admin = null;
    if (!$isEmail) {
        echo "   - Trying username first...\n";
        $admin = Administrator::findByUsername($loginField);
        if ($admin) echo "   ✓ Found via username: {$admin->Username}\n";
    }
    if (!$admin && $isEmail) {
        echo "   - Trying email...\n";
        $admin = Administrator::findByEmail($loginField);
        if ($admin) echo "   ✓ Found via email: {$admin->AdminEmail}\n";
    }
    if (!$admin) echo "   ✗ Not found in Administrator\n";
    
    // Agency Staff authentication  
    echo "2. Trying AgencyStaff:\n";
    $staff = null;
    if (!$isEmail) {
        echo "   - Trying username first...\n";
        $staff = AgencyStaff::findByUsername($loginField);
        if ($staff) echo "   ✓ Found via username: {$staff->Username}\n";
    }
    if (!$staff && $isEmail) {
        echo "   - Trying email...\n";
        $staff = AgencyStaff::findByEmail($loginField);
        if ($staff) echo "   ✓ Found via email: {$staff->staffEmail}\n";
    }
    if (!$staff) echo "   ✗ Not found in AgencyStaff\n";
    
    // Public User authentication
    echo "3. Trying PublicUser:\n";
    if ($isEmail) {
        echo "   - Trying email (only option)...\n";
        $publicUser = PublicUser::findForLogin($loginField);
        if ($publicUser) echo "   ✓ Found via email: {$publicUser->UserEmail}\n";
        else echo "   ✗ Not found in PublicUser\n";
    } else {
        echo "   - Skipped (not email format)\n";
    }
    
    echo "========================\n";
}

// Test with different login inputs
$testInputs = [
    'admin1',               // Username - should find admin
    'admin1@admin.com',     // Email - should find admin
    'staff1',               // Username - should find agency staff
    'staff1@agency.com',    // Email - should find agency staff  
    'user1@public.com',     // Email - should find public user
    'nonexistent',          // Username - should not find anything
    'fake@email.com'        // Email - should not find anything
];

foreach ($testInputs as $input) {
    simulateAuth($input);
    echo "\n";
}

echo "=== TEST COMPLETE ===\n";
