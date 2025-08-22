<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Display a listing of notifications
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->with(['fromUser'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = NotificationService::getUnreadCount(Auth::id());

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Show a specific notification
     */
    public function show($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['fromUser'])
            ->firstOrFail();

        // Mark as read when viewed
        if (!$notification->is_read) {
            $notification->markAsRead();
        }

        return view('notifications.show', compact('notification'));
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $success = NotificationService::markAsRead($id, Auth::id());

        if (request()->expectsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $success ? 'Notification marked as read' : 'Notification not found'
            ]);
        }

        return back()->with('status', $success ? 'Notification marked as read' : 'Notification not found');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $count = NotificationService::markAllAsRead(Auth::id());

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Marked {$count} notifications as read",
                'count' => $count,
            ]);
        }

        return back()->with('status', "Marked {$count} notifications as read");
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted'
            ]);
        }

        return back()->with('status', 'Notification deleted');
    }

    /**
     * Get unread count for AJAX requests
     */
    public function getUnreadCount()
    {
        $count = NotificationService::getUnreadCount(Auth::id());

        return response()->json([
            'count' => $count
        ]);
    }

    /**
     * Get recent notifications for dropdown
     */
    public function getRecent()
    {
        
    }
}
