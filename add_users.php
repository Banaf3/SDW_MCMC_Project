<?php

require_once 'vendor/autoload.php'; 

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PublicUser;
use Illuminate\Support\Facades\Hash;

echo "Adding additional public users...\n";

// Check if UserID 2 and 3 already exist
$user2 = PublicUser::find(2);
$user3 = PublicUser::find(3);

if (!$user2) {
    PublicUser::create([
        'UserName' => 'Ahmad Rahman',
        'UserEmail' => 'ahmad.rahman@gmail.com',
        'Password' => Hash::make('password123'),
        'UserPhoneNum' => '0123456790',
        'Useraddress' => '456 Jalan Ampang, Kuala Lumpur',
    ]);
    echo "Created user ID 2: Ahmad Rahman\n";
} else {
    echo "User ID 2 already exists\n";
}

if (!$user3) {
    PublicUser::create([
        'UserName' => 'Siti Aminah',
        'UserEmail' => 'siti.aminah@yahoo.com',
        'Password' => Hash::make('password123'),
        'UserPhoneNum' => '0123456791',
        'Useraddress' => '789 Jalan Tun Razak, Kuala Lumpur',
    ]);
    echo "Created user ID 3: Siti Aminah\n";
} else {
    echo "User ID 3 already exists\n";
}

echo "Done!\n";

?>
