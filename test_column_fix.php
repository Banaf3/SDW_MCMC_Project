<?php
// Test password recovery with correct column names

require_once 'vendor/autoload.php';

try {
    // Bootstrap Laravel application
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "=== PASSWORD RECOVERY COLUMN FIX TEST ===\n";
    
    // Test 1: Create a test public user with email
    echo "\n1. Creating test public user...\n";
    
    $publicUser = App\Models\PublicUser::where('UserEmail', 'abdullah@gmail.com')->first();
    
    if (!$publicUser) {
        $publicUser = App\Models\PublicUser::create([
            'UserName' => 'Abdullah Test',
            'UserEmail' => 'abdullah@gmail.com',
            'Password' => Hash::make('public123'),
            'UserPhoneNum' => '1234567890',
            'Useraddress' => 'Test Address',
        ]);
        echo "   ✅ Public user created: Email = abdullah@gmail.com\n";
    } else {
        echo "   ✅ Public user exists: Email = {$publicUser->UserEmail}\n";
    }
    
    // Test 2: Test finding user by email (the query that was failing)
    echo "\n2. Testing user lookup by email...\n";
    
    $foundUser = App\Models\PublicUser::where('UserEmail', 'abdullah@gmail.com')->first();
    if ($foundUser) {
        echo "   ✅ PublicUser found by UserEmail: {$foundUser->UserName}\n";
    } else {
        echo "   ❌ PublicUser NOT found by UserEmail\n";
    }
    
    // Test 3: Test all user types email lookup
    echo "\n3. Testing all user types email lookup...\n";
    
    $testEmails = [
        'testadmin@admin.com' => 'Administrator',
        'abdullah@gmail.com' => 'PublicUser'
    ];
    
    foreach ($testEmails as $email => $expectedType) {
        echo "   Testing {$email} ({$expectedType}):\n";
        
        // Check administrators
        $admin = App\Models\Administrator::where('AdminEmail', $email)->first();
        if ($admin) {
            echo "     ✅ Found as Administrator: {$admin->AdminName}\n";
            continue;
        }
        
        // Check agency staff
        $staff = App\Models\AgencyStaff::where('staffEmail', $email)->first();
        if ($staff) {
            echo "     ✅ Found as AgencyStaff: {$staff->StaffName}\n";
            continue;
        }
        
        // Check public users
        $publicUser = App\Models\PublicUser::where('UserEmail', $email)->first();
        if ($publicUser) {
            echo "     ✅ Found as PublicUser: {$publicUser->UserName}\n";
            continue;
        }
        
        echo "     ❌ User not found in any table\n";
    }
    
    echo "\n=== TEST CREDENTIALS ===\n";
    echo "Administrator: testadmin@admin.com / admin123\n";
    echo "Public User: abdullah@gmail.com / public123\n";
    
    echo "\n✅ COLUMN FIX TEST COMPLETED!\n";
    echo "Password recovery should now work with: abdullah@gmail.com\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
