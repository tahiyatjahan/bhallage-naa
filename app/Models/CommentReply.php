<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'comment_id',
        'comment_type',
        'content'
    ];

    /**
     * Get the user who wrote the reply.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment this reply belongs to.
     */
    public function comment()
    {
        if ($this->comment_type === 'mood_journal') {
            return $this->belongsTo(MoodJournalComment::class, 'comment_id');
        } elseif ($this->comment_type === 'creative_post') {
            return $this->belongsTo(CreativePostComment::class, 'comment_id');
        }
        return null;
    }

    /**
     * Scope to get replies for a specific comment type and ID.
     */
    public function scopeForComment($query, $commentId, $commentType)
    {
        return $query->where('comment_id', $commentId)
                    ->where('comment_type', $commentType);
    }
}
