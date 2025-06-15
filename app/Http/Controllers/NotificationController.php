<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get notifications for the authenticated user
     */
    public function index(Request $request)
    {
        // For now, we'll use a mock user ID. In a real app, this would come from authentication
        $userId = $request->get('user_id', 1); // Default to user ID 1 for demo
        
        $notifications = $this->notificationService->getUserNotifications($userId);
        $formattedNotifications = $this->notificationService->formatNotificationsForFrontend($notifications);
        
        return response()->json([
            'notifications' => $formattedNotifications,
            'unread_count' => $this->notificationService->getUnreadCount($userId)
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        $userId = $request->get('user_id', 1); // Default to user ID 1 for demo
        
        $this->notificationService->markAllAsReadForUser($userId);
        
        return response()->json(['success' => true]);
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $userId = $request->get('user_id', 1); // Default to user ID 1 for demo
        
        $this->notificationService->markAsRead($id, $userId);
        
        return response()->json(['success' => true]);
    }

    /**
     * Get unread notification count
     */
    public function getUnreadCount(Request $request)
    {
        $userId = $request->get('user_id', 1); // Default to user ID 1 for demo
        
        return response()->json([
            'unread_count' => $this->notificationService->getUnreadCount($userId)
        ]);
    }
}
