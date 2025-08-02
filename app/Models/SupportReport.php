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
                    'National Suicide Prevention Lifeline' => 'https://988lifeline.org/',
                    'Crisis Text Line' => 'https://www.crisistextline.org/',
                    'Emergency Services' => '911'
                ]
            ],
            'self_harm' => [
                'keywords' => ['self harm', 'cut myself', 'hurt myself', 'self injury'],
                'category' => 'self_harm',
                'severity' => 'high',
                'resources' => [
                    'Crisis Text Line' => 'https://www.crisistextline.org/',
                    'Self-Harm Hotline' => '1-800-273-8255',
                    'Emergency Services' => '911'
                ]
            ],
            'abuse' => [
                'keywords' => ['abuse', 'abused', 'abusive', 'domestic violence', 'physical abuse'],
                'category' => 'abuse',
                'severity' => 'high',
                'resources' => [
                    'National Domestic Violence Hotline' => 'https://www.thehotline.org/',
                    'RAINN (Sexual Assault)' => 'https://www.rainn.org/',
                    'Emergency Services' => '911'
                ]
            ],
            'sexual_assault' => [
                'keywords' => ['rape', 'sexual assault', 'sexual abuse', 'molestation'],
                'category' => 'sexual_assault',
                'severity' => 'high',
                'resources' => [
                    'RAINN (Sexual Assault)' => 'https://www.rainn.org/',
                    'National Sexual Assault Hotline' => '1-800-656-4673',
                    'Emergency Services' => '911'
                ]
            ],
            'eating_disorder' => [
                'keywords' => ['anorexia', 'bulimia', 'eating disorder', 'starve myself', 'binge eating'],
                'category' => 'eating_disorder',
                'severity' => 'medium',
                'resources' => [
                    'National Eating Disorders Association' => 'https://www.nationaleatingdisorders.org/',
                    'Eating Disorder Hotline' => '1-800-931-2237',
                    'Crisis Text Line' => 'https://www.crisistextline.org/'
                ]
            ],
            'substance_abuse' => [
                'keywords' => ['drugs', 'alcohol', 'overdose', 'addiction', 'substance abuse'],
                'category' => 'substance_abuse',
                'severity' => 'medium',
                'resources' => [
                    'SAMHSA National Helpline' => 'https://www.samhsa.gov/find-help/national-helpline',
                    'Alcoholics Anonymous' => 'https://www.aa.org/',
                    'Narcotics Anonymous' => 'https://www.na.org/'
                ]
            ],
            'depression' => [
                'keywords' => ['depression', 'depressed', 'hopeless', 'worthless', 'no reason to live'],
                'category' => 'depression',
                'severity' => 'medium',
                'resources' => [
                    'Depression and Bipolar Support Alliance' => 'https://www.dbsalliance.org/',
                    'Mental Health America' => 'https://www.mhanational.org/',
                    'Crisis Text Line' => 'https://www.crisistextline.org/'
                ]
            ],
            'anxiety' => [
                'keywords' => ['anxiety', 'panic attack', 'anxious', 'overwhelmed', 'can\'t breathe'],
                'category' => 'anxiety',
                'severity' => 'medium',
                'resources' => [
                    'Anxiety and Depression Association' => 'https://adaa.org/',
                    'Mental Health America' => 'https://www.mhanational.org/',
                    'Crisis Text Line' => 'https://www.crisistextline.org/'
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

        $message .= "If you're in immediate danger, please call 911 or your local emergency services.\n\n";
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
