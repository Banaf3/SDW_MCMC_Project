<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\Inquiry;
use App\Models\PublicUser;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        // Get some sample inquiries and users for testing
        $inquiries = Inquiry::limit(3)->get();
        $users = PublicUser::limit(2)->get();

        if ($inquiries->count() > 0 && $users->count() > 0) {
            // Create sample notifications
            Notification::create([
                'user_id' => $users->first()->UserID,
                'inquiry_id' => $inquiries->first()->InquiryID,
                'type' => 'status_update',
                'title' => 'Status Update',
                'message' => 'Your inquiry "' . $inquiries->first()->InquiryTitle . '" has been updated to "Under Investigation"',
                'data' => [
                    'old_status' => null,
                    'new_status' => 'Under Investigation',
                    'inquiry_title' => $inquiries->first()->InquiryTitle
                ],
                'created_at' => now()->subMinutes(2)
            ]);

            if ($inquiries->count() > 1) {
                Notification::create([
                    'user_id' => $users->first()->UserID,
                    'inquiry_id' => $inquiries->skip(1)->first()->InquiryID,
                    'type' => 'status_update',
                    'title' => 'Status Update',
                    'message' => 'Your inquiry "' . $inquiries->skip(1)->first()->InquiryTitle . '" has been updated to "Verified as True"',
                    'data' => [
                        'old_status' => 'Under Investigation',
                        'new_status' => 'Verified as True',
                        'inquiry_title' => $inquiries->skip(1)->first()->InquiryTitle
                    ],
                    'read_at' => now()->subMinutes(30), // This one is read
                    'created_at' => now()->subHour(1)
                ]);
            }

            if ($inquiries->count() > 2) {
                Notification::create([
                    'user_id' => $users->first()->UserID,
                    'inquiry_id' => $inquiries->skip(2)->first()->InquiryID,
                    'type' => 'status_update',
                    'title' => 'Status Update',
                    'message' => 'Your inquiry "' . $inquiries->skip(2)->first()->InquiryTitle . '" has been updated to "Identified as Fake"',
                    'data' => [
                        'old_status' => 'Under Investigation',
                        'new_status' => 'Identified as Fake',
                        'inquiry_title' => $inquiries->skip(2)->first()->InquiryTitle
                    ],
                    'created_at' => now()->subHours(3)
                ]);
            }
        }
    }
}
