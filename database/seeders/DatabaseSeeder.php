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
        // Create default agency
        Agency::create([
            'AgencyName' => 'Default Agency',
            'AgencyEmail' => 'contact@default-agency.com',
            'AgencyPhoneNum' => '0123456789',
            'AgencyType' => 'Default', // This matches the actual column from the migration
        ]);

        // Create additional agencies for testing
        Agency::create([
            'AgencyName' => 'Malaysian Maritime Enforcement Agency',
            'AgencyEmail' => 'info@mmea.gov.my',
            'AgencyPhoneNum' => '0321234567',
            'AgencyType' => 'Government',
        ]);

        Agency::create([
            'AgencyName' => 'Port Klang Authority',
            'AgencyEmail' => 'contact@pka.gov.my',
            'AgencyPhoneNum' => '0387654321',
            'AgencyType' => 'Government',
        ]);

        Agency::create([
            'AgencyName' => 'Johor Port Authority',
            'AgencyEmail' => 'admin@jpa.gov.my',
            'AgencyPhoneNum' => '0712345678',
            'AgencyType' => 'Government',
        ]);

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
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
