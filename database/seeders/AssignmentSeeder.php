<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssignmentSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Clear existing assignments
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('assigned_inquiries')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get existing inquiries and agencies
        $inquiries = DB::table('inquiries')->pluck('InquiryID')->take(4); // First 4 inquiries
        $agencies = DB::table('agencies')->pluck('AgencyID')->take(3); // First 3 agencies
        
        if ($inquiries->isEmpty() || $agencies->isEmpty()) {
            $this->command->info('No inquiries or agencies found. Please run TempInquirySeeder and AgencySeeder first.');
            return;
        }

        // Assign first 3 inquiries to agencies (leaving some unassigned for testing)
        $assignments = [
            [
                'AdminID' => 1, // Assuming admin ID 1 exists
                'AgencyID' => $agencies[0],
                'InquiryID' => $inquiries[0],
                'AssignedDate' => Carbon::now()->subDays(5)->format('Y-m-d'),
            ],
            [
                'AdminID' => 1,
                'AgencyID' => $agencies[1],
                'InquiryID' => $inquiries[1],
                'AssignedDate' => Carbon::now()->subDays(3)->format('Y-m-d'),
            ],
            [
                'AdminID' => 1,
                'AgencyID' => $agencies[2],
                'InquiryID' => $inquiries[2],
                'AssignedDate' => Carbon::now()->subDays(1)->format('Y-m-d'),
            ],
            // Inquiry 4 will remain unassigned to test "Not Assigned Yet" display
        ];

        foreach ($assignments as $assignment) {
            DB::table('assigned_inquiries')->insert($assignment);
        }

        $this->command->info('Assignment seeder completed! Created ' . count($assignments) . ' assignments.');
        $this->command->info('Total inquiries: ' . $inquiries->count() . ', Assigned: ' . count($assignments) . ', Unassigned: ' . ($inquiries->count() - count($assignments)));
    }
}
