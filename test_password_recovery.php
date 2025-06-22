<?php
// Test the password recovery system

require_once 'vendor/autoload.php';

try {
    // Bootstrap Laravel application
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "=== PASSWORD RECOVERY SYSTEM TEST ===\n";
    
    // Test 1: Ensure we have a test user with email
    echo "\n1. Setting up test administrator with email...\n";
    
    $admin = App\Models\Administrator::where('AdminEmail', 'testadmin@admin.com')->first();
    
    if (!$admin) {
        $username = App\Models\Administrator::generateUniqueUsername('Test Admin');
        $admin = App\Models\Administrator::create([
            'AdminName' => 'Test Admin',
            'Username' => $username,
            'AdminEmail' => 'testadmin@admin.com',
            'Password' => Hash::make('admin123'),
            'AdminRole' => 'Admin',
            'AdminPhoneNum' => '1234567890',
            'AdminAddress' => 'Test Address',
        ]);
        echo "   ✅ Administrator created: Username = {$username}, Email = testadmin@admin.com\n";
    } else {
        echo "   ✅ Administrator exists: Username = {$admin->Username}, Email = {$admin->AdminEmail}\n";
    }
    
    // Test 2: Test password reset token generation
    echo "\n2. Testing password reset token generation...\n";
    
    $email = 'testadmin@admin.com';
    $token = Str::random(64);
    
    // Delete existing password reset records
    DB::table('password_resets')->where('email', $email)->delete();
    
    // Insert new password reset record
    DB::table('password_resets')->insert([
        'email' => $email,
        'token' => Hash::make($token),
        'created_at' => now(),
    ]);
    
    echo "   ✅ Password reset token generated and stored\n";
    echo "   Token: {$token}\n";
    
    // Test 3: Verify token exists in database
    $passwordReset = DB::table('password_resets')->where('email', $email)->first();
    if ($passwordReset) {
        echo "   ✅ Token verified in database\n";
        echo "   Created at: {$passwordReset->created_at}\n";
    } else {
        echo "   ❌ Token not found in database\n";
    }
    
    // Test 4: Test token validation
    echo "\n3. Testing token validation...\n";
    
    if (Hash::check($token, $passwordReset->token)) {
        echo "   ✅ Token validation successful\n";
    } else {
        echo "   ❌ Token validation failed\n";
    }
    
    // Test 5: Test password reset process
    echo "\n4. Testing password reset process...\n";
    
    $originalPassword = $admin->Password;
    $newPassword = 'newpassword123';
    
    // Update password
    $admin->update(['Password' => Hash::make($newPassword)]);
    
    // Verify password was updated
    $admin->refresh();
    if (Hash::check($newPassword, $admin->Password)) {
        echo "   ✅ Password updated successfully\n";
    } else {
        echo "   ❌ Password update failed\n";
    }
    
    // Reset password back to original for future tests
    $admin->update(['Password' => $originalPassword]);
    
    echo "\n=== PASSWORD RECOVERY TEST CREDENTIALS ===\n";
    echo "Administrator for password recovery testing:\n";
    echo "  Email: {$admin->AdminEmail}\n";
    echo "  Current Password: admin123\n";
    echo "  Test Reset Token: {$token}\n";
    echo "  Reset URL: http://localhost/SDW_MCMC_Project/public/password/reset/{$token}\n";
    
    echo "\n=== TEST URLs ===\n";
    echo "Password Recovery Form: http://localhost/SDW_MCMC_Project/public/password/forgot\n";
    echo "Login Page: http://localhost/SDW_MCMC_Project/public/login\n";
    
    echo "\n✅ PASSWORD RECOVERY SYSTEM TEST COMPLETED!\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
