<?php
// Test the login system with username functionality

require_once 'vendor/autoload.php';

try {
    // Bootstrap Laravel application
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "=== LOGIN SYSTEM TEST ===\n";
      // Test 1: Create a test administrator with username
    echo "\n1. Creating test administrator...\n";
    
    $admin = App\Models\Administrator::where('Username', 'admin_testadmin')->first();
    if (!$admin) {
        $admin = App\Models\Administrator::where('AdminEmail', 'testadmin@admin.com')->first();
    }
    
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
        echo "   ✅ Administrator already exists: Username = {$admin->Username}, Email = {$admin->AdminEmail}\n";
    }
    
    // Test 2: Create test agency staff with username
    echo "\n2. Creating test agency staff...\n";
    
    $staff = App\Models\AgencyStaff::where('Username', 'teststaff')->first();
    if (!$staff) {
        $username = App\Models\AgencyStaff::generateUniqueUsername('Test Staff', 1);
        $staff = App\Models\AgencyStaff::create([
            'StaffName' => 'Test Staff',
            'Username' => $username,
            'Password' => Hash::make('staff123'),
            'staffEmail' => null, // Optional email
            'staffPhoneNum' => '0987654321',
            'AgencyID' => 1,
            'password_change_required' => false,
        ]);
        echo "   ✅ Agency Staff created: Username = {$username}, Email = Optional\n";
    } else {
        echo "   ✅ Agency Staff already exists: Username = {$staff->Username}, Email = " . ($staff->staffEmail ?? 'Not set') . "\n";
    }
    
    // Test 3: Test findForLogin functionality
    echo "\n3. Testing login functionality...\n";
    
    // Test admin login by username
    $foundAdmin = App\Models\Administrator::findForLogin($admin->Username);
    echo "   - Admin login by username: " . ($foundAdmin ? "✅ SUCCESS" : "❌ FAILED") . "\n";
    
    // Test admin login by email
    $foundAdminEmail = App\Models\Administrator::findForLogin($admin->AdminEmail);
    echo "   - Admin login by email: " . ($foundAdminEmail ? "✅ SUCCESS" : "❌ FAILED") . "\n";
    
    // Test staff login by username
    $foundStaff = App\Models\AgencyStaff::findForLogin($staff->Username);
    echo "   - Staff login by username: " . ($foundStaff ? "✅ SUCCESS" : "❌ FAILED") . "\n";
    
    echo "\n=== TEST CREDENTIALS FOR LOGIN ===\n";
    echo "Administrator:\n";
    echo "  Username: {$admin->Username}\n";
    echo "  Email: {$admin->AdminEmail}\n";
    echo "  Password: admin123\n";
    echo "\nAgency Staff:\n";
    echo "  Username: {$staff->Username}\n";
    echo "  Email: " . ($staff->staffEmail ?? 'Not set - use username only') . "\n";
    echo "  Password: staff123\n";
    
    echo "\n✅ LOGIN SYSTEM TEST COMPLETED!\n";
    echo "You can now test login at: http://localhost/SDW_MCMC_Project/public/login\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
