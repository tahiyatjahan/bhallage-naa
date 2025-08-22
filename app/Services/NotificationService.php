<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create a notification for a user
     */
    public static function create(
        int $userId,
        string $type,
        string $title,
        string $message,
        string $notifiableType,
        int $notifiableId,
        ?int $fromUserId = null,
        ?string $actionUrl = null,
        ?array $metadata = null
    ): Notification {
        try {
            return Notification::create([
                'user_id' => $userId,
                'from_user_id' => $fromUserId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'notifiable_type' => $notifiableType,
                'notifiable_id' => $notifiableId,
                'action_url' => $actionUrl,
                'metadata' => $metadata,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create notification', [
                'user_id' => $userId,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create a comment notification
     */
    public static function comment(
        int $postOwnerId,
        int $commenterId,
        string $postType,
        int $postId,
        string $postTitle,
        ?string $actionUrl = null
    ): void {
        if ($postOwnerId === $commenterId) {
            return; // Don't notify user of their own comment
        }

        $commenter = User::find($commenterId);
        if (!$commenter) return;

        self::create(
            userId: $postOwnerId,
            type: Notification::TYPE_COMMENT,
            title: 'New Comment',
            message: "{$commenter->name} commented on your {$postType}",
            notifiableType: $postType,
            notifiableId: $postId,
            fromUserId: $commenterId,
            actionUrl: $actionUrl,
            metadata: [
                'post_title' => $postTitle,
                'commenter_name' => $commenter->name
            ]
        );
    }

    /**
     * Create a like notification
     */
    public static function like(
        int $postOwnerId,
        int $likerId,
        string $postType,
        int $postId,
        string $postTitle,
        ?string $actionUrl = null
    ): void {
        if ($postOwnerId === $likerId) {
            return; // Don't notify user of their own like
        }

        $liker = User::find($likerId);
        if (!$liker) return;

        self::create(
            userId: $postOwnerId,
            type: Notification::TYPE_LIKE,
            title: 'New Like',
            message: "{$liker->name} liked your {$postType}",
            notifiableType: $postType,
            notifiableId: $postId,
            fromUserId: $likerId,
            actionUrl: $actionUrl,
            metadata: [
                'post_title' => $postTitle,
                'liker_name' => $liker->name
            ]
        );
    }

    /**
     * Create a reply notification
     */
    public static function reply(
        int $commentOwnerId,
        int $replierId,
        string $postType,
        int $postId,
        string $postTitle,
        ?string $actionUrl = null
    ): void {
        if ($commentOwnerId === $replierId) {
            return; // Don't notify user of their own reply
        }

        $replier = User::find($replierId);
        if (!$replier) return;

        self::create(
            userId: $commentOwnerId,
            type: Notification::TYPE_REPLY,
            title: 'New Reply',
            message: "{$replier->name} replied to your comment on {$postTitle}",
            notifiableType: $postType,
            notifiableId: $postId,
            fromUserId: $replierId,
            actionUrl: $actionUrl,
            metadata: [
                'post_title' => $postTitle,
                'replier_name' => $replier->name
            ]
        );
    }

    /**
     * Create a report notification (for admins)
     */
    public static function report(
        string $reportableType,
        int $reportableId,
        string $reportReason,
        ?string $actionUrl = null
    ): void {
        // Get all admin users
        $admins = User::where('is_admin', true)->get();
        
        foreach ($admins as $admin) {
            self::create(
                userId: $admin->id,
                type: Notification::TYPE_REPORT,
                title: 'New Report',
                message: "A new report has been submitted for {$reportableType} #{$reportableId}",
                notifiableType: $reportableType,
                notifiableId: $reportableId,
                actionUrl: $actionUrl,
                metadata: [
                    'report_reason' => $reportReason,
                    'reportable_type' => $reportableType,
                    'reportable_id' => $reportableId
                ]
            );
        }
    }

    /**
     * Create a system notification
     */
    public static function system(
        int $userId,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?array $metadata = null
    ): void {
        self::create(
            userId: $userId,
            type: Notification::TYPE_SYSTEM,
            title: $title,
            message: $message,
            notifiableType: 'system',
            notifiableId: 0,
            actionUrl: $actionUrl,
            metadata: $metadata
        );
    }

    /**
     * Mark notification as read
     */
    public static function markAsRead(int $notificationId, int $userId): bool
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Mark all notifications as read for a user
     */
    public static function markAllAsRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }

    /**
     * Get unread count for a user
     */
    public static function getUnreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }
}
