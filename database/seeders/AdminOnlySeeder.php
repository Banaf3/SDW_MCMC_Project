<?php

namespace Database\Seeders;

use App\Models\Administrator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class AdminOnlySeeder extends Seeder
{
    public function run(): void
    {
        // Sample admin user
        $admin1 = Administrator::firstOrCreate([
            'AdminEmail' => 'admin@admin.com'
        ], [
            'AdminName' => 'Admin User',
            'Username' => 'admin',
            'Password' => Hash::make('password123'),
            'AdminRole' => 'Administrator',
            'AdminPhoneNum' => '0123456789',
            'AdminAddress' => '123 Admin St, Kuala Lumpur',
        ]);

        // Additional admin for testing
        $admin2 = Administrator::firstOrCreate([
            'AdminEmail' => 'mcmc.admin@admin.com'
        ], [
            'AdminName' => 'MCMC Administrator',
            'Username' => 'mcmc_admin',
            'Password' => Hash::make('password123'),
            'AdminRole' => 'Administrator',
            'AdminPhoneNum' => '0123456790',
            'AdminAddress' => '456 MCMC Building, Cyberjaya',
        ]);

        $this->command->info('✅ Admin accounts created successfully!');
        $this->command->info('Admin 1: admin@admin.com (username: admin) / password123');
        $this->command->info('Admin 2: mcmc.admin@admin.com (username: mcmc_admin) / password123');
    }
}
