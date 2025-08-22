<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_user_id',
        'type',
        'title',
        'message',
        'notifiable_type',
        'notifiable_id',
        'action_url',
        'metadata',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime'
    ];

    // Notification types
    const TYPE_COMMENT = 'comment';
    const TYPE_LIKE = 'like';
    const TYPE_REPLY = 'reply';
    const TYPE_REPORT = 'report';
    const TYPE_MENTION = 'mention';
    const TYPE_FOLLOW = 'follow';
    const TYPE_SYSTEM = 'system';

    /**
     * Get the user receiving the notification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who triggered the notification
     */
    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Get the related model (polymorphic)
     */
    public function notifiable()
    {
        return $this->morphTo('notifiable', 'notifiable_type', 'notifiable_id');
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope for notifications of a specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        if ($this->is_read) {
            return;
        }
        $this->is_read = true;
        $this->read_at = now();
        $this->save();
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null
        ]);
    }

    /**
     * Get notification icon based on type
     */
    public function getIconAttribute()
    {
        return match($this->type) {
            self::TYPE_COMMENT => '💬',
            self::TYPE_LIKE => '❤️',
            self::TYPE_REPLY => '↩️',
            self::TYPE_REPORT => '⚠️',
            self::TYPE_MENTION => '📢',
            self::TYPE_FOLLOW => '👥',
            self::TYPE_SYSTEM => '🔔',
            default => '📌'
        };
    }

    /**
     * Get notification color class based on type
     */
    public function getColorClassAttribute()
    {
        return match($this->type) {
            self::TYPE_COMMENT => 'text-blue-600',
            self::TYPE_LIKE => 'text-red-500',
            self::TYPE_REPLY => 'text-green-600',
            self::TYPE_REPORT => 'text-orange-600',
            self::TYPE_MENTION => 'text-purple-600',
            self::TYPE_FOLLOW => 'text-indigo-600',
            self::TYPE_SYSTEM => 'text-gray-600',
            default => 'text-gray-600'
        };
    }

    /**
     * Get time ago in human readable format
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
