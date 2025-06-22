<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\Administrator;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'admin:create 
                            {name : The admin username}
                            {email : The admin email}
                            {password : The admin password}
                            {first_name : The admin first name}
                            {last_name : The admin last name}
                            {--phone= : Phone number}
                            {--position= : Job position}
                            {--department= : Department}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new administrator account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $adminName = $this->argument('name');
        $adminEmail = $this->argument('email');
        $password = $this->argument('password');
        $firstName = $this->argument('first_name');
        $lastName = $this->argument('last_name');
        
        // Optional parameters
        $phoneNumber = $this->option('phone');
        $position = $this->option('position');
        $department = $this->option('department');
        
        // Check if admin already exists
        $existingAdmin = Administrator::where('AdminEmail', $adminEmail)
                                    ->orWhere('AdminName', $adminName)
                                    ->first();
        
        if ($existingAdmin) {
            $this->error('An administrator with this email or username already exists.');
            return 1;
        }
        
        try {
            // Create the administrator
            $admin = Administrator::create([
                'AdminName' => $adminName,
                'AdminEmail' => $adminEmail,
                'Password' => Hash::make($password),
                'AdminRole' => 'Administrator', // Default role
                'FirstName' => $firstName,
                'LastName' => $lastName,
                'PhoneNumber' => $phoneNumber,
                'Position' => $position,
                'Department' => $department,
                'AdminPhoneNum' => $phoneNumber ?: '', // Map to database field
                'AdminAddress' => '', // Default empty
                'IsActive' => true,
                'CreatedAt' => now(),
                'UpdatedAt' => now(),
            ]);
            
            $this->info('Administrator account created successfully!');
            $this->line('Admin ID: ' . $admin->AdminID);
            $this->line('Username: ' . $admin->AdminName);
            $this->line('Email: ' . $admin->AdminEmail);
            $this->line('Name: ' . $admin->FirstName . ' ' . $admin->LastName);
            
            if ($admin->Position) {
                $this->line('Position: ' . $admin->Position);
            }
            
            if ($admin->Department) {
                $this->line('Department: ' . $admin->Department);
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Error creating administrator: ' . $e->getMessage());
            return 1;
        }
    }
}
