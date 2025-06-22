<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function createStatusUpdateNotification($inquiry, $oldStatus, $newStatus)
    {
        // Simple notification implementation
        // In a real application, this might send emails, push notifications, etc.
        // For now, we'll just log it
        Log::info('Status update notification created', [
            'inquiry_id' => $inquiry->InquiryID ?? 'unknown',
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ]);
        
        return true;
    }
}
