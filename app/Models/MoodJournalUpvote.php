<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodJournalUpvote extends Model
{
    protected $fillable = ['user_id', 'mood_journal_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function moodJournal()
    {
        return $this->belongsTo(MoodJournal::class);
    }
}
