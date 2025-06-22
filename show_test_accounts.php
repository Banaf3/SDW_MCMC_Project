<?php
// Show summary of existing test accounts

require_once 'vendor/autoload.php';

try {
    // Bootstrap Laravel application
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "=== EXISTING TEST ACCOUNTS SUMMARY ===\n";
    
    // Get all administrators
    echo "\n📋 ADMINISTRATORS:\n";
    $admins = App\Models\Administrator::all();
    foreach ($admins as $admin) {
        echo "   ✅ {$admin->AdminName}\n";
        echo "      Username: {$admin->Username}\n";
        echo "      Email: {$admin->AdminEmail}\n";
        echo "      Role: {$admin->AdminRole}\n";
        echo "      Default Password: admin123\n\n";
    }
    
    // Get all agencies
    echo "\n🏢 AGENCIES:\n";
    $agencies = App\Models\Agency::all();
    foreach ($agencies as $agency) {
        echo "   ✅ {$agency->AgencyName}\n";
        echo "      Email: {$agency->AgencyEmail}\n";
        echo "      Phone: {$agency->AgencyPhoneNum}\n";
        echo "      Type: {$agency->AgencyType}\n\n";
    }
    
    // Get all agency staff
    echo "\n👥 AGENCY STAFF:\n";
    $staff = App\Models\AgencyStaff::with('agency')->get();
    foreach ($staff as $staffMember) {
        echo "   ✅ {$staffMember->StaffName}\n";
        echo "      Username: {$staffMember->Username}\n";
        echo "      Email: " . ($staffMember->staffEmail ?? 'Not set') . "\n";
        echo "      Agency: " . ($staffMember->agency->AgencyName ?? 'Unknown') . "\n";
        echo "      Default Password: staff123\n\n";
    }
    
    // Get all public users
    echo "\n👤 PUBLIC USERS:\n";
    $users = App\Models\PublicUser::all();
    foreach ($users as $user) {
        echo "   ✅ {$user->UserName}\n";
        echo "      Email: {$user->UserEmail}\n";
        echo "      Phone: {$user->UserPhoneNum}\n";
        echo "      Default Password: user123\n\n";
    }
    
    // Get all inquiries
    echo "\n📝 INQUIRIES:\n";
    $inquiries = App\Models\Inquiry::with(['user', 'agency'])->get();
    foreach ($inquiries as $inquiry) {
        echo "   ✅ {$inquiry->InquiryTitle}\n";
        echo "      Status: {$inquiry->InquiryStatus}\n";
        echo "      User: " . ($inquiry->user->UserName ?? 'Unknown') . "\n";
        echo "      Agency: " . ($inquiry->agency->AgencyName ?? 'Unassigned') . "\n";
        echo "      Date: {$inquiry->SubmitionDate}\n\n";
    }
    
    echo "\n=== QUICK LOGIN CREDENTIALS ===\n";
    
    echo "\n🔑 ADMINISTRATOR LOGIN:\n";
    if ($admins->count() > 0) {
        $admin = $admins->first();
        echo "   Email: {$admin->AdminEmail}\n";
        echo "   Username: {$admin->Username}\n";
        echo "   Password: admin123\n";
    }
    
    echo "\n🔑 AGENCY STAFF LOGIN:\n";
    if ($staff->count() > 0) {
        $staffMember = $staff->first();
        echo "   Email: " . ($staffMember->staffEmail ?? 'Use username instead') . "\n";
        echo "   Username: {$staffMember->Username}\n";
        echo "   Password: staff123\n";
    }
    
    echo "\n🔑 PUBLIC USER LOGIN:\n";
    if ($users->count() > 0) {
        $user = $users->first();
        echo "   Email: {$user->UserEmail}\n";
        echo "   Password: user123\n";
    }
    
    echo "\n=== STATISTICS ===\n";
    echo "Administrators: " . $admins->count() . "\n";
    echo "Agencies: " . $agencies->count() . "\n";
    echo "Agency Staff: " . $staff->count() . "\n";
    echo "Public Users: " . $users->count() . "\n";
    echo "Inquiries: " . $inquiries->count() . "\n";
    
    echo "\n✅ TEST ACCOUNTS READY FOR USE!\n";
    echo "Login page: http://localhost:8000/login\n";
    echo "Password recovery: http://localhost:8000/password/forgot\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
