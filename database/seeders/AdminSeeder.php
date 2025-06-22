<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Administrator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if administrators table exists
        if (!Schema::hasTable('administrators')) {
            $this->command->error('Error: administrators table does not exist!');
            $this->command->error('Please run migrations first: php artisan migrate');
            return;
        }

        // Customize these values as needed
        $username = 'Mr.Majed';  // Change this to your desired username
        $password = '11223344';  // Change this to your desired password
        $email = 'majed@admin.com';  // Change this to your desired email
          // Check for duplicate admin by username or email
        $existingAdmin = Administrator::where('Username', $username)
                                    ->orWhere('AdminEmail', $email)
                                    ->first();        if ($existingAdmin) {
            $this->command->warn('Admin account already exists!');
            if ($existingAdmin->Username === $username) {
                $this->command->warn('Username "' . $username . '" is already taken.');
            }
            if ($existingAdmin->AdminEmail === $email) {
                $this->command->warn('Email "' . $email . '" is already taken.');
            }
            $this->command->info('Skipping admin creation to prevent duplicates.');
            return;
        }        // Create the admin account
        try {
            Administrator::create([
                'AdminName' => $username,  // Full name
                'Username' => $username,   // Username for login
                'AdminEmail' => $email,    // Admin email
                'Password' => Hash::make($password),
                'AdminRole' => 'Super Admin',
                'AdminPhoneNum' => '+1234567890',
                'AdminAddress' => 'Admin Headquarters, Main Office',
                'LoginHistory' => null,
            ]);

            // Display the created admin credentials
            $this->command->info('✅ Admin account created successfully!');
            $this->command->info('Username: ' . $username);
            $this->command->info('Email: ' . $email);
            $this->command->info('Password: ' . $password);
            $this->command->info('Role: Super Admin');
            
        } catch (\Exception $e) {
            $this->command->error('❌ Failed to create admin account: ' . $e->getMessage());
            $this->command->error('Make sure all required fields are properly configured.');
        }
    }
}