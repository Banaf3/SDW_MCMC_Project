<?php
// Create comprehensive test accounts for all user types

require_once 'vendor/autoload.php';

try {
    // Bootstrap Laravel application
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "=== CREATING TEST ACCOUNTS ===\n";
    
    // Test 1: Create Administrator Accounts
    echo "\n1. Creating Administrator Accounts...\n";
    
    $adminAccounts = [
        [
            'name' => 'Main Administrator',
            'email' => 'admin@mcmc.com',
            'password' => 'admin123',
            'role' => 'Super Admin',
            'phone' => '03-1234-5678',
            'address' => 'MCMC Headquarters, Cyberjaya'
        ],
        [
            'name' => 'System Administrator',
            'email' => 'sysadmin@mcmc.com',
            'password' => 'sysadmin123',
            'role' => 'System Admin',
            'phone' => '03-2345-6789',
            'address' => 'MCMC Regional Office, KL'
        ],
        [
            'name' => 'Content Administrator',
            'email' => 'contentadmin@mcmc.com',
            'password' => 'content123',
            'role' => 'Content Admin',
            'phone' => '03-3456-7890',
            'address' => 'MCMC Content Division'
        ]
    ];
      foreach ($adminAccounts as $adminData) {
        // Check if admin already exists
        $existingAdmin = App\Models\Administrator::where('AdminEmail', $adminData['email'])->first();
        
        if ($existingAdmin) {
            echo "   ⚠️  Admin already exists: {$adminData['name']} ({$adminData['email']})\n";
            continue;
        }
        
        $username = App\Models\Administrator::generateUniqueUsername($adminData['name']);
        
        $admin = App\Models\Administrator::create([
            'AdminName' => $adminData['name'],
            'Username' => $username,
            'AdminEmail' => $adminData['email'],
            'Password' => Hash::make($adminData['password']),
            'AdminRole' => $adminData['role'],
            'AdminPhoneNum' => $adminData['phone'],
            'AdminAddress' => $adminData['address'],
        ]);
        
        echo "   ✅ Created Admin: {$adminData['name']}\n";
        echo "      Username: {$username}\n";
        echo "      Email: {$adminData['email']}\n";
        echo "      Password: {$adminData['password']}\n";
    }
    
    // Test 2: Create Agency Records
    echo "\n2. Creating Agency Records...\n";
      $agencies = [
        [
            'name' => 'Telekom Malaysia Berhad',
            'email' => 'inquiry@tm.com.my',
            'phone' => '100',
            'type' => 'Telecommunications'
        ],
        [
            'name' => 'Maxis Berhad',
            'email' => 'support@maxis.com.my',
            'phone' => '123',
            'type' => 'Mobile Network'
        ],
        [
            'name' => 'Celcom Axiata Berhad',
            'email' => 'help@celcom.com.my',
            'phone' => '013',
            'type' => 'Mobile Network'
        ],
        [
            'name' => 'U Mobile Sdn Bhd',
            'email' => 'care@u.com.my',
            'phone' => '018',
            'type' => 'Mobile Network'
        ]
    ];
      $createdAgencies = [];
    foreach ($agencies as $agencyData) {
        // Check if agency already exists
        $existingAgency = App\Models\Agency::where('AgencyEmail', $agencyData['email'])->first();
        
        if ($existingAgency) {
            echo "   ⚠️  Agency already exists: {$agencyData['name']}\n";
            $createdAgencies[] = $existingAgency;
            continue;
        }
        
        $agency = App\Models\Agency::create([
            'AgencyName' => $agencyData['name'],
            'AgencyEmail' => $agencyData['email'],
            'AgencyPhoneNum' => $agencyData['phone'],
            'AgencyType' => $agencyData['type'],
        ]);
        
        $createdAgencies[] = $agency;
        echo "   ✅ Created Agency: {$agencyData['name']}\n";
        echo "      ID: {$agency->AgencyID}\n";
        echo "      Email: {$agencyData['email']}\n";
    }
    
    // Test 3: Create Agency Staff Accounts
    echo "\n3. Creating Agency Staff Accounts...\n";        $staffAccounts = [
        [
            'name' => 'John Smith',
            'email' => 'john.smith@tm.com.my',
            'password' => 'staff123',
            'phone' => '012-345-6789',
            'agency_index' => 0  // TM
        ],
        [
            'name' => 'Sarah Johnson',
            'email' => 'sarah.johnson@tm.com.my',
            'password' => 'staff123',
            'phone' => '012-456-7890',
            'agency_index' => 0  // TM
        ],
        [
            'name' => 'Ahmad Rahman',
            'email' => 'ahmad.rahman@maxis.com.my',
            'password' => 'staff123',
            'phone' => '013-567-8901',
            'agency_index' => 1  // Maxis
        ],
        [
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.nurhaliza@celcom.com.my',
            'password' => 'staff123',
            'phone' => '014-678-9012',
            'agency_index' => 2  // Celcom
        ],
        [
            'name' => 'David Tan',
            'email' => null, // Testing optional email
            'password' => 'staff123',
            'phone' => '015-789-0123',
            'agency_index' => 3  // U Mobile
        ]
    ];
      foreach ($staffAccounts as $staffData) {
        // Check if staff already exists
        $existingStaff = App\Models\AgencyStaff::where('staffEmail', $staffData['email'])->first();
        
        if ($existingStaff) {
            echo "   ⚠️  Staff already exists: {$staffData['name']}\n";
            continue;
        }
          $username = App\Models\AgencyStaff::generateUniqueUsername($staffData['name'], $createdAgencies[$staffData['agency_index']]->AgencyID);
        
        $staff = App\Models\AgencyStaff::create([
            'StaffName' => $staffData['name'],
            'Username' => $username,
            'Password' => Hash::make($staffData['password']),
            'staffEmail' => $staffData['email'],
            'staffPhoneNum' => $staffData['phone'],
            'AgencyID' => $createdAgencies[$staffData['agency_index']]->AgencyID,
            'password_change_required' => false,
        ]);
        
        echo "   ✅ Created Staff: {$staffData['name']}\n";
        echo "      Username: {$username}\n";
        echo "      Email: " . ($staffData['email'] ?? 'Not set') . "\n";
        echo "      Password: {$staffData['password']}\n";
        echo "      Agency: {$createdAgencies[$staffData['agency_index']]->AgencyName}\n";
    }
    
    // Test 4: Create Public User Accounts
    echo "\n4. Creating Public User Accounts...\n";
    
    $publicAccounts = [
        [
            'name' => 'Abdullah Mohammed',
            'email' => 'abdullah@gmail.com',
            'password' => 'user123',
            'phone' => '012-111-2222',
            'address' => 'Taman Tun Dr Ismail, Kuala Lumpur'
        ],
        [
            'name' => 'Lisa Wong',
            'email' => 'lisa.wong@yahoo.com',
            'password' => 'user123',
            'phone' => '013-222-3333',
            'address' => 'Subang Jaya, Selangor'
        ],
        [
            'name' => 'Raj Patel',
            'email' => 'raj.patel@hotmail.com',
            'password' => 'user123',
            'phone' => '014-333-4444',
            'address' => 'Petaling Jaya, Selangor'
        ],
        [
            'name' => 'Fatimah Ali',
            'email' => 'fatimah.ali@gmail.com',
            'password' => 'user123',
            'phone' => '015-444-5555',
            'address' => 'Shah Alam, Selangor'
        ],
        [
            'name' => 'Michael Chen',
            'email' => 'michael.chen@outlook.com',
            'password' => 'user123',
            'phone' => '016-555-6666',
            'address' => 'Mont Kiara, Kuala Lumpur'
        ]
    ];
      foreach ($publicAccounts as $userData) {
        // Check if public user already exists
        $existingUser = App\Models\PublicUser::where('UserEmail', $userData['email'])->first();
        
        if ($existingUser) {
            echo "   ⚠️  Public user already exists: {$userData['name']}\n";
            continue;
        }
        
        $user = App\Models\PublicUser::create([
            'UserName' => $userData['name'],
            'UserEmail' => $userData['email'],
            'Password' => Hash::make($userData['password']),
            'UserPhoneNum' => $userData['phone'],
            'Useraddress' => $userData['address'],
        ]);
        
        echo "   ✅ Created Public User: {$userData['name']}\n";
        echo "      Email: {$userData['email']}\n";
        echo "      Password: {$userData['password']}\n";
    }
    
    // Test 5: Create Sample Inquiries
    echo "\n5. Creating Sample Inquiries...\n";
      $inquiries = [
        [
            'title' => 'Internet Speed Issue',
            'description' => 'My internet connection is very slow despite paying for high-speed package.',
            'user_id' => 1,
            'agency_index' => 0  // TM
        ],
        [
            'title' => 'Billing Discrepancy',
            'description' => 'I was charged extra fees that were not explained in my contract.',
            'user_id' => 2,
            'agency_index' => 1  // Maxis
        ],
        [
            'title' => 'Service Interruption',
            'description' => 'My phone service has been interrupted for 3 days without notice.',
            'user_id' => 3,
            'agency_index' => 2  // Celcom
        ]
    ];    foreach ($inquiries as $inquiryData) {
        $inquiry = App\Models\Inquiry::create([
            'InquiryTitle' => $inquiryData['title'],
            'InquiryDescription' => $inquiryData['description'],
            'InquiryStatus' => 'Open',
            'SubmitionDate' => now(),
            'UserID' => $inquiryData['user_id'],
            'AgencyID' => $createdAgencies[$inquiryData['agency_index']]->AgencyID,
            'AdminID' => 1, // Assign to first admin
        ]);
        
        echo "   ✅ Created Inquiry: {$inquiryData['title']}\n";
    }
    
    echo "\n=== TEST ACCOUNT CREATION COMPLETE ===\n";
    
    echo "\n=== LOGIN CREDENTIALS SUMMARY ===\n";
    
    echo "\n📋 ADMINISTRATORS:\n";
    echo "   admin@mcmc.com / admin123 (Super Admin)\n";
    echo "   sysadmin@mcmc.com / sysadmin123 (System Admin)\n";
    echo "   contentadmin@mcmc.com / content123 (Content Admin)\n";
    
    echo "\n👥 AGENCY STAFF:\n";
    echo "   john.smith@tm.com.my / staff123 (TM)\n";
    echo "   sarah.johnson@tm.com.my / staff123 (TM)\n";
    echo "   ahmad.rahman@maxis.com.my / staff123 (Maxis)\n";
    echo "   siti.nurhaliza@celcom.com.my / staff123 (Celcom)\n";
    echo "   [Username only] / staff123 (U Mobile)\n";
    
    echo "\n👤 PUBLIC USERS:\n";
    echo "   abdullah@gmail.com / user123\n";
    echo "   lisa.wong@yahoo.com / user123\n";
    echo "   raj.patel@hotmail.com / user123\n";
    echo "   fatimah.ali@gmail.com / user123\n";
    echo "   michael.chen@outlook.com / user123\n";
    
    echo "\n🏢 AGENCIES CREATED:\n";
    echo "   Telekom Malaysia Berhad\n";
    echo "   Maxis Berhad\n";
    echo "   Celcom Axiata Berhad\n";
    echo "   U Mobile Sdn Bhd\n";
    
    echo "\n📝 SAMPLE INQUIRIES:\n";
    echo "   3 sample inquiries created for testing\n";
    
    echo "\n✅ READY FOR TESTING!\n";
    echo "You can now log in with any of the accounts above.\n";
    echo "Login page: http://localhost:8000/login\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
