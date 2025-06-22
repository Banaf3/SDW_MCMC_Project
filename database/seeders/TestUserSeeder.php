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
        // Create a test public user
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

        // Create a test administrator
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

        // Create a test agency
        DB::table('agencies')->insert([
            'AgencyID' => 1,
            'AgencyName' => 'Test Verification Agency',
            'AgencyEmail' => 'agency@agency.com',
            'AgencyPhoneNum' => '1234567890',
            'AgencyType' => 'Government',
        ]);
    }
}
