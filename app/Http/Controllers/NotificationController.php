<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get all notifications for a user from session
     */
    public function index(Request $request)
    {
        $userId = $request->input('user_id');
        if (!$userId) {
            return response()->json(['error' => 'User ID is required'], 400);
        }

        // Get notifications from session
        $notifications = session()->get("user_notifications_{$userId}", []);
        
        // Sort by timestamp (newest first)
        usort($notifications, function($a, $b) {
            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
        });
        
        // Format for frontend
        $formattedNotifications = array_map(function($notification) {
            return [
                'id' => md5($notification['timestamp'] . $notification['inquiry_id']), // Generate a unique ID
                'title' => 'Status Update',
                'message' => $notification['message'],
                'read' => $notification['read'],
                'time' => $this->getTimeAgo($notification['timestamp']),
                'inquiryId' => $notification['inquiry_id'],
                'data' => [
                    'old_status' => $notification['old_status'],
                    'new_status' => $notification['new_status'],
                    'inquiry_title' => $notification['inquiry_title']
                ]
            ];
        }, $notifications);

        $unreadCount = count(array_filter($notifications, function($n) {
            return !$n['read'];
        }));

        return response()->json([
            'notifications' => $formattedNotifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Get unread notification count for a user
     */
    public function getUnreadCount(Request $request)
    {
        $userId = $request->input('user_id');
        if (!$userId) {
            return response()->json(['error' => 'User ID is required'], 400);
        }

        $notifications = session()->get("user_notifications_{$userId}", []);
        $unreadCount = count(array_filter($notifications, function($n) {
            return !$n['read'];
        }));

        return response()->json([
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $userId = $request->input('user_id');
        if (!$userId) {
            return response()->json(['error' => 'User ID is required'], 400);
        }

        $notifications = session()->get("user_notifications_{$userId}", []);
        
        foreach ($notifications as &$notification) {
            $notificationId = md5($notification['timestamp'] . $notification['inquiry_id']);
            if ($notificationId === $id) {
                $notification['read'] = true;
                break;
            }
        }
        
        session()->put("user_notifications_{$userId}", $notifications);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(Request $request)
    {
        $userId = $request->input('user_id');
        if (!$userId) {
            return response()->json(['error' => 'User ID is required'], 400);
        }

        $notifications = session()->get("user_notifications_{$userId}", []);
        
        foreach ($notifications as &$notification) {
            $notification['read'] = true;
        }
        
        session()->put("user_notifications_{$userId}", $notifications);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Get human-readable time ago
     */
    private function getTimeAgo($timestamp)
    {
        $now = time();
        $diff = $now - strtotime($timestamp);

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
            return date('M j, Y', strtotime($timestamp));
        }
    }
}
