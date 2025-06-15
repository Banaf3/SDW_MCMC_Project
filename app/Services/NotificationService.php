<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Inquiry;
use App\Models\PublicUser;

class NotificationService
{
    /**
     * Create a status update notification for an inquiry
     */
    public function createStatusUpdateNotification($inquiryId, $newStatus, $oldStatus = null)
    {
        $inquiry = Inquiry::with('user')->findOrFail($inquiryId);
        
        if (!$inquiry->user) {
            return; // No user to notify
        }

        $title = 'Status Update';
        $message = sprintf(
            'Your inquiry "%s" has been updated to "%s"',
            $inquiry->InquiryTitle,
            $newStatus
        );

        if ($oldStatus) {
            $message = sprintf(
                'Your inquiry "%s" status has been changed from "%s" to "%s"',
                $inquiry->InquiryTitle,
                $oldStatus,
                $newStatus
            );
        }

        return Notification::create([
            'user_id' => $inquiry->UserID,
            'inquiry_id' => $inquiryId,
            'type' => 'status_update',
            'title' => $title,
            'message' => $message,
            'data' => [
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'inquiry_title' => $inquiry->InquiryTitle
            ]
        ]);
    }

    /**
     * Get notifications for a specific user
     */
    public function getUserNotifications($userId, $limit = 10, $unreadOnly = false)
    {
        $query = Notification::forUser($userId)
            ->with('inquiry')
            ->orderBy('created_at', 'desc')
            ->limit($limit);

        if ($unreadOnly) {
            $query->unread();
        }

        return $query->get();
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsReadForUser($userId)
    {
        return Notification::forUser($userId)
            ->unread()
            ->update(['read_at' => now()]);
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead($notificationId, $userId = null)
    {
        $query = Notification::where('id', $notificationId);
        
        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->update(['read_at' => now()]);
    }

    /**
     * Get unread notification count for a user
     */
    public function getUnreadCount($userId)
    {
        return Notification::forUser($userId)->unread()->count();
    }

    /**
     * Format notifications for frontend
     */
    public function formatNotificationsForFrontend($notifications)
    {
        return $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'inquiryId' => $notification->inquiry_id,
                'title' => $notification->title,
                'message' => $notification->message,
                'time' => $this->getTimeAgo($notification->created_at),
                'read' => $notification->isRead(),
                'type' => $notification->type,
                'data' => $notification->data
            ];
        });
    }

    /**
     * Get human-readable time ago
     */
    private function getTimeAgo($datetime)
    {
        $now = now();
        $diff = $now->diffInSeconds($datetime);

        if ($diff < 60) {
            return 'Just now';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } else {
            return $datetime->format('M j, Y');
        }
    }
}
