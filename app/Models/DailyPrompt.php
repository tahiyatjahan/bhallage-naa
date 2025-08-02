<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DailyPrompt extends Model
{
    protected $fillable = [
        'prompt',
        'category',
        'prompt_date',
        'is_active'
    ];

    protected $casts = [
        'prompt_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get today's prompt
     */
    public static function getTodayPrompt()
    {
        return self::where('prompt_date', Carbon::today())
                   ->where('is_active', true)
                   ->first();
    }

    /**
     * Get prompt for a specific date
     */
    public static function getPromptForDate($date)
    {
        return self::where('prompt_date', $date)
                   ->where('is_active', true)
                   ->first();
    }

    /**
     * Get recent prompts
     */
    public static function getRecentPrompts($limit = 7)
    {
        return self::where('is_active', true)
                   ->orderBy('prompt_date', 'desc')
                   ->limit($limit)
                   ->get();
    }

    /**
     * Create a prompt for today if it doesn't exist
     */
    public static function createTodayPrompt()
    {
        $today = Carbon::today();
        
        // Check if prompt already exists for today
        $existingPrompt = self::where('prompt_date', $today)->first();
        
        if ($existingPrompt) {
            return $existingPrompt;
        }

        // Generate a random prompt
        $prompt = self::generateRandomPrompt();
        
        return self::create([
            'prompt' => $prompt['text'],
            'category' => $prompt['category'],
            'prompt_date' => $today,
            'is_active' => true,
        ]);
    }

    /**
     * Generate a random prompt from predefined options
     */
    private static function generateRandomPrompt()
    {
        $prompts = [
            [
                'text' => 'What made you smile today?',
                'category' => 'gratitude'
            ],
            [
                'text' => 'Describe a challenge you faced and how you handled it.',
                'category' => 'reflection'
            ],
            [
                'text' => 'What are you looking forward to tomorrow?',
                'category' => 'goals'
            ],
            [
                'text' => 'How are you feeling right now, and why?',
                'category' => 'general'
            ],
            [
                'text' => 'What\'s something you learned about yourself today?',
                'category' => 'reflection'
            ],
            [
                'text' => 'Describe a moment of joy you experienced.',
                'category' => 'gratitude'
            ],
            [
                'text' => 'What would you like to improve about today?',
                'category' => 'goals'
            ],
            [
                'text' => 'How did you take care of yourself today?',
                'category' => 'self-care'
            ],
            [
                'text' => 'What\'s something you\'re grateful for right now?',
                'category' => 'gratitude'
            ],
            [
                'text' => 'Describe your energy level today and what influenced it.',
                'category' => 'general'
            ],
            [
                'text' => 'What\'s a small win you had today?',
                'category' => 'gratitude'
            ],
            [
                'text' => 'How did you connect with others today?',
                'category' => 'relationships'
            ],
            [
                'text' => 'What\'s something that challenged your perspective today?',
                'category' => 'reflection'
            ],
            [
                'text' => 'Describe your ideal day and what makes it special.',
                'category' => 'goals'
            ],
            [
                'text' => 'What\'s something you\'re proud of accomplishing?',
                'category' => 'gratitude'
            ]
        ];

        return $prompts[array_rand($prompts)];
    }

    /**
     * Get the category display name
     */
    public function getCategoryDisplayNameAttribute()
    {
        $categories = [
            'general' => 'General',
            'gratitude' => 'Gratitude',
            'reflection' => 'Reflection',
            'goals' => 'Goals',
            'self-care' => 'Self-Care',
            'relationships' => 'Relationships'
        ];

        return $categories[$this->category] ?? ucfirst($this->category);
    }

    /**
     * Get the category color for display
     */
    public function getCategoryColorAttribute()
    {
        $colors = [
            'general' => 'blue',
            'gratitude' => 'green',
            'reflection' => 'purple',
            'goals' => 'yellow',
            'self-care' => 'pink',
            'relationships' => 'indigo'
        ];

        return $colors[$this->category] ?? 'gray';
    }
} 