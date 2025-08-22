<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreativePostComment extends Model
{
    protected $fillable = [
        'user_id',
        'creative_post_id',
        'content'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creativePost(): BelongsTo
    {
        return $this->belongsTo(CreativePost::class);
    }

    /**
     * Get all replies to this comment.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(CommentReply::class, 'comment_id')
                    ->where('comment_type', 'creative_post');
    }
} 