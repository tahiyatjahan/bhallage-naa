<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportReport extends Model
{
    protected $fillable = [
        'user_id',
        'mood_journal_id',
        'keywords_detected',
        'support_resources',
        'message',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'keywords_detected' => 'array',
        'support_resources' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function moodJournal(): BelongsTo
    {
        return $this->belongsTo(MoodJournal::class);
    }

    /**
     * Get predefined keywords that trigger support reports
     */
    public static function getTriggerKeywords(): array
    {
        return [
            'suicide' => [
                'keywords' => ['suicide', 'kill myself', 'end my life', 'want to die', 'better off dead'],
                'category' => 'suicide',
                'severity' => 'high',
                'resources' => [
                    'National Mental Health Helpline (Bangladesh)' => 'https://www.icddrb.org/what-we-do/research/mental-health',
                    'Kaan Pete Roi (Crisis Helpline)' => 'https://www.kaanpeteroi.com/',
                    'Emergency Services (Bangladesh)' => '999'
                ]
            ],
            'self_harm' => [
                'keywords' => ['self harm', 'cut myself', 'hurt myself', 'self injury'],
                'category' => 'self_harm',
                'severity' => 'high',
                'resources' => [
                    'Kaan Pete Roi (Crisis Helpline)' => 'https://www.kaanpeteroi.com/',
                    'National Mental Health Helpline' => 'https://www.icddrb.org/what-we-do/research/mental-health',
                    'Emergency Services (Bangladesh)' => '999'
                ]
            ],
            'abuse' => [
                'keywords' => ['abuse', 'abused', 'abusive', 'domestic violence', 'physical abuse'],
                'category' => 'abuse',
                'severity' => 'high',
                'resources' => [
                    'Bangladesh National Women Lawyers Association' => 'https://www.bnwla.org/',
                    'Ain o Salish Kendra (ASK)' => 'https://www.askbd.org/',
                    'Emergency Services (Bangladesh)' => '999'
                ]
            ],
            'sexual_assault' => [
                'keywords' => ['rape', 'sexual assault', 'sexual abuse', 'molestation'],
                'category' => 'sexual_assault',
                'severity' => 'high',
                'resources' => [
                    'Bangladesh National Women Lawyers Association' => 'https://www.bnwla.org/',
                    'Ain o Salish Kendra (ASK)' => 'https://www.askbd.org/',
                    'Emergency Services (Bangladesh)' => '999'
                ]
            ],
            'eating_disorder' => [
                'keywords' => ['anorexia', 'bulimia', 'eating disorder', 'starve myself', 'binge eating'],
                'category' => 'eating_disorder',
                'severity' => 'medium',
                'resources' => [
                    'Bangladesh Association of Psychiatrists' => 'https://www.bap-bd.org/',
                    'National Institute of Mental Health (Bangladesh)' => 'https://nimh.gov.bd/',
                    'Kaan Pete Roi (Crisis Helpline)' => 'https://www.kaanpeteroi.com/'
                ]
            ],
            'substance_abuse' => [
                'keywords' => ['drugs', 'alcohol', 'overdose', 'addiction', 'substance abuse'],
                'category' => 'substance_abuse',
                'severity' => 'medium',
                'resources' => [
                    'National Drug Abuse Prevention Center' => 'https://www.icddrb.org/what-we-do/research/mental-health',
                    'Bangladesh Association of Psychiatrists' => 'https://www.bap-bd.org/',
                    'Kaan Pete Roi (Crisis Helpline)' => 'https://www.kaanpeteroi.com/'
                ]
            ],
            'depression' => [
                'keywords' => ['depression', 'depressed', 'hopeless', 'worthless', 'no reason to live'],
                'category' => 'depression',
                'severity' => 'medium',
                'resources' => [
                    'Bangladesh Association of Psychiatrists' => 'https://www.bap-bd.org/',
                    'National Institute of Mental Health (Bangladesh)' => 'https://nimh.gov.bd/',
                    'Kaan Pete Roi (Crisis Helpline)' => 'https://www.kaanpeteroi.com/'
                ]
            ],
            'anxiety' => [
                'keywords' => ['anxiety', 'panic attack', 'anxious', 'overwhelmed', 'can\'t breathe'],
                'category' => 'anxiety',
                'severity' => 'medium',
                'resources' => [
                    'Bangladesh Association of Psychiatrists' => 'https://www.bap-bd.org/',
                    'National Institute of Mental Health (Bangladesh)' => 'https://nimh.gov.bd/',
                    'Kaan Pete Roi (Crisis Helpline)' => 'https://www.kaanpeteroi.com/'
                ]
            ]
        ];
    }

    /**
     * Check if content contains triggering keywords
     */
    public static function detectKeywords(string $content): array
    {
        $content = strtolower($content);
        $triggerKeywords = self::getTriggerKeywords();
        $detected = [];

        foreach ($triggerKeywords as $key => $data) {
            foreach ($data['keywords'] as $keyword) {
                if (str_contains($content, strtolower($keyword))) {
                    $detected[$key] = $data;
                    break;
                }
            }
        }

        return $detected;
    }

    /**
     * Generate support message based on detected keywords
     */
    public static function generateSupportMessage(array $detectedKeywords): string
    {
        if (empty($detectedKeywords)) {
            return '';
        }

        $message = "We noticed some concerning content in your journal entry. ";
        $message .= "Please know that you're not alone and help is available. ";
        $message .= "Here are some resources that might be helpful:\n\n";

        foreach ($detectedKeywords as $category => $data) {
            $message .= "**" . ucfirst(str_replace('_', ' ', $category)) . " Support:**\n";
            foreach ($data['resources'] as $name => $url) {
                $message .= "• {$name}: {$url}\n";
            }
            $message .= "\n";
        }

        $message .= "If you're in immediate danger, please call 999 (Bangladesh Emergency Services) or your local emergency services.\n\n";
        $message .= "Remember: It's okay to ask for help, and you deserve support.";

        return $message;
    }

    /**
     * Get all support resources for detected keywords
     */
    public static function getSupportResources(array $detectedKeywords): array
    {
        $resources = [];
        
        foreach ($detectedKeywords as $category => $data) {
            $resources = array_merge($resources, $data['resources']);
        }

        return array_unique($resources, SORT_REGULAR);
    }
}
