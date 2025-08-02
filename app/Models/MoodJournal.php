<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MoodJournal extends Model
{
    protected $fillable = [
        'user_id',
        'daily_prompt_id',
        'content',
        'hashtags',
        'mood_rating'
    ];

    protected $casts = [
        'hashtags' => 'array',
        'mood_rating' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dailyPrompt(): BelongsTo
    {
        return $this->belongsTo(DailyPrompt::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(MoodJournalComment::class);
    }

    public function supportReports()
    {
        return $this->hasMany(SupportReport::class);
    }

    public function upvotes(): HasMany
    {
        return $this->hasMany(MoodJournalUpvote::class);
    }

    /**
     * Get predefined hashtag options
     */
    public static function getPredefinedHashtags()
    {
        return [
            'gratitude' => [
                'label' => 'Gratitude',
                'color' => 'green',
                'icon' => 'heart'
            ],
            'selflove' => [
                'label' => 'Self Love',
                'color' => 'pink',
                'icon' => 'star'
            ],
            'happy' => [
                'label' => 'Happy',
                'color' => 'yellow',
                'icon' => 'sun'
            ],
            'sad' => [
                'label' => 'Sad',
                'color' => 'blue',
                'icon' => 'cloud'
            ],
            'anxious' => [
                'label' => 'Anxious',
                'color' => 'orange',
                'icon' => 'alert'
            ],
            'excited' => [
                'label' => 'Excited',
                'color' => 'purple',
                'icon' => 'zap'
            ],
            'tired' => [
                'label' => 'Tired',
                'color' => 'gray',
                'icon' => 'moon'
            ],
            'motivated' => [
                'label' => 'Motivated',
                'color' => 'red',
                'icon' => 'fire'
            ],
            'peaceful' => [
                'label' => 'Peaceful',
                'color' => 'teal',
                'icon' => 'leaf'
            ],
            'stressed' => [
                'label' => 'Stressed',
                'color' => 'red',
                'icon' => 'alert-triangle'
            ],
            'grateful' => [
                'label' => 'Grateful',
                'color' => 'green',
                'icon' => 'heart'
            ],
            'confident' => [
                'label' => 'Confident',
                'color' => 'purple',
                'icon' => 'crown'
            ],
            'lonely' => [
                'label' => 'Lonely',
                'color' => 'gray',
                'icon' => 'user'
            ],
            'energetic' => [
                'label' => 'Energetic',
                'color' => 'yellow',
                'icon' => 'zap'
            ],
            'calm' => [
                'label' => 'Calm',
                'color' => 'blue',
                'icon' => 'droplets'
            ]
        ];
    }

    /**
     * Get hashtag display info
     */
    public function getHashtagInfo($hashtag)
    {
        $predefined = self::getPredefinedHashtags();
        return $predefined[$hashtag] ?? [
            'label' => ucfirst($hashtag),
            'color' => 'gray',
            'icon' => 'hash'
        ];
    }

    /**
     * Get hashtags as array
     */
    public function getHashtagsArrayAttribute()
    {
        if (is_array($this->hashtags)) {
            return $this->hashtags;
        }
        
        if (is_string($this->hashtags)) {
            return json_decode($this->hashtags, true) ?? [];
        }
        
        return [];
    }

    /**
     * Set hashtags from array
     */
    public function setHashtagsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['hashtags'] = json_encode($value);
        } else {
            $this->attributes['hashtags'] = $value;
        }
    }

    /**
     * Scope to filter by hashtag
     */
    public function scopeWithHashtag($query, $hashtag)
    {
        return $query->whereJsonContains('hashtags', $hashtag);
    }

    /**
     * Get hashtag color class
     */
    public function getHashtagColorClass($hashtag)
    {
        $info = $this->getHashtagInfo($hashtag);
        $color = $info['color'];
        
        $colorClasses = [
            'green' => 'bg-green-100 text-green-800',
            'pink' => 'bg-pink-100 text-pink-800',
            'yellow' => 'bg-yellow-100 text-yellow-800',
            'blue' => 'bg-blue-100 text-blue-800',
            'orange' => 'bg-orange-100 text-orange-800',
            'purple' => 'bg-purple-100 text-purple-800',
            'gray' => 'bg-gray-100 text-gray-800',
            'red' => 'bg-red-100 text-red-800',
            'teal' => 'bg-teal-100 text-teal-800'
        ];
        
        return $colorClasses[$color] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get hashtag icon
     */
    public function getHashtagIcon($hashtag)
    {
        $info = $this->getHashtagInfo($hashtag);
        return $info['icon'] ?? 'hash';
    }
}
