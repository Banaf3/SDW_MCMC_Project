<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AgencyStaff;

class UpdateAgencyStaffPasswordRequiredSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update all existing agency staff to require password change
        AgencyStaff::whereNull('password_change_required')
                  ->orWhere('password_change_required', false)
                  ->update([
                      'password_change_required' => true,
                      'password_changed_at' => null
                  ]);

        $this->command->info('Updated all existing agency staff to require password change on next login.');
    }
}
