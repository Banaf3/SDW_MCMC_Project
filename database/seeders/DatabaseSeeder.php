<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Agency;
use App\Models\Administrator;
use App\Models\AgencyStaff;
use App\Models\PublicUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the AgencySeeder
        $this->call(AgencySeeder::class);

        // Sample admin user
        Administrator::create([
            'AdminName' => 'Admin User',
            'AdminEmail' => 'admin@admin.com',
            'Password' => Hash::make('password123'),
            'AdminRole' => 'Administrator',
            'AdminPhoneNum' => '0123456789',
            'AdminAddress' => '123 Admin St, Kuala Lumpur',
        ]);

        // Additional admin for testing
        Administrator::create([
            'AdminName' => 'MCMC Administrator',
            'AdminEmail' => 'mcmc.admin@admin.com',
            'Password' => Hash::make('password123'),
            'AdminRole' => 'Administrator',
            'AdminPhoneNum' => '0123456790',
            'AdminAddress' => '456 MCMC Building, Cyberjaya',
        ]);

        // Sample agency staff
        AgencyStaff::create([
            'StaffName' => 'Agency Staff',
            'staffEmail' => 'staff@agency.com',
            'Password' => Hash::make('password123'),
            'staffPhoneNum' => '0123456789',
            'AgencyID' => 1,
        ]);

        // Sample public user
        PublicUser::create([
            'UserName' => 'Public User',
            'UserEmail' => 'user@example.com',
            'Password' => Hash::make('password123'),
            'UserPhoneNum' => '0123456789',
            'Useraddress' => '123 User St, Kuala Lumpur',
        ]);

        // Regular user for Laravel auth
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
    }
}
