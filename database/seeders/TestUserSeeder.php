<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test public user (if not exists)
        if (!DB::table('public_users')->where('UserID', 1)->exists()) {
            DB::table('public_users')->insert([
                'UserID' => 1,
                'UserName' => 'Test User',
                'UserEmail' => 'test@example.com',
                'Password' => Hash::make('password'),
                'UserPhoneNum' => '1234567890',
                'Useraddress' => '123 Test Street',
                'ProfilePic' => null,
                'LoginHistory' => null,
            ]);
            $this->command->info('✅ Test public user created');
        } else {
            $this->command->warn('⚠️ Test public user already exists');
        }

        // Create a test administrator (if not exists)
        if (!DB::table('administrators')->where('AdminID', 1)->exists()) {
            DB::table('administrators')->insert([
                'AdminID' => 1,
                'AdminName' => 'Test Admin',
                'Password' => Hash::make('password'),
                'AdminEmail' => 'admin@example.com',
                'AdminRole' => 'Super Admin',
                'AdminPhoneNum' => '1234567890',
                'AdminAddress' => '123 Admin Street',
                'LoginHistory' => null,
            ]);
            $this->command->info('✅ Test admin user created');
        } else {
            $this->command->warn('⚠️ Test admin user already exists');
        }

        // Create a test agency (if not exists)
        if (!DB::table('agencies')->where('AgencyID', 1)->exists()) {
            DB::table('agencies')->insert([
                'AgencyID' => 1,
                'AgencyName' => 'Test Verification Agency',
                'AgencyEmail' => 'agency@example.com',
                'AgencyPhoneNum' => '1234567890',
                'AgencyType' => 'Government',
            ]);
            $this->command->info('✅ Test agency created');
        } else {
            $this->command->warn('⚠️ Test agency already exists');
        }

        $this->command->info('📊 Test users seeding completed!');
        $this->command->info('🔐 Admin Login: admin@example.com / password');
    }
}
