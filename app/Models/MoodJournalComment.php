<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MoodJournalComment extends Model
{
    protected $fillable = ['user_id', 'mood_journal_id', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function moodJournal()
    {
        return $this->belongsTo(MoodJournal::class);
    }

    /**
     * Get all replies to this comment.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(CommentReply::class, 'comment_id')
                    ->where('comment_type', 'mood_journal');
    }
}
