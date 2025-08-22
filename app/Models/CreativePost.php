<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class CreativePost extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'media_file',
        'content',
        'external_link',
        'tags',
        'is_featured',
        'is_public'
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_public' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(CreativePostLike::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(CreativePostComment::class);
    }

    /**
     * Get predefined categories with their display info
     */
    public static function getCategories()
    {
        return [
            'music' => [
                'label' => 'Music',
                'emoji' => '🎵',
                'icon' => 'music-note',
                'color' => 'purple',
                'description' => 'Share your musical creations, covers, or compositions'
            ],
            'artwork' => [
                'label' => 'Artwork',
                'emoji' => '🎨',
                'icon' => 'palette',
                'color' => 'pink',
                'description' => 'Paintings, drawings, digital art, and visual creations'
            ],
            'poetry' => [
                'label' => 'Poetry',
                'emoji' => '📝',
                'icon' => 'book-open',
                'color' => 'blue',
                'description' => 'Poems, verses, and lyrical expressions'
            ],
            'photography' => [
                'label' => 'Photography',
                'emoji' => '📸',
                'icon' => 'camera',
                'color' => 'green',
                'description' => 'Photos, portraits, landscapes, and visual stories'
            ],
            'writing' => [
                'label' => 'Writing',
                'emoji' => '✍️',
                'icon' => 'pencil',
                'color' => 'yellow',
                'description' => 'Stories, essays, articles, and written works'
            ],
            'video' => [
                'label' => 'Video',
                'emoji' => '🎬',
                'icon' => 'video-camera',
                'color' => 'red',
                'description' => 'Short films, animations, and video content'
            ],
            'craft' => [
                'label' => 'Craft',
                'emoji' => '🧶',
                'icon' => 'scissors',
                'color' => 'orange',
                'description' => 'Handmade items, DIY projects, and crafts'
            ],
            'other' => [
                'label' => 'Other',
                'emoji' => '✨',
                'icon' => 'star',
                'color' => 'gray',
                'description' => 'Other creative expressions and projects'
            ]
        ];
    }

    /**
     * Get category display info
     */
    public function getCategoryInfo()
    {
        $categories = self::getCategories();
        return $categories[$this->category] ?? $categories['other'];
    }

    /**
     * Get category color class
     */
    public function getCategoryColorClass()
    {
        $info = $this->getCategoryInfo();
        $color = $info['color'];
        
        $colorClasses = [
            'purple' => 'bg-purple-100 text-purple-800',
            'pink' => 'bg-pink-100 text-pink-800',
            'blue' => 'bg-blue-100 text-blue-800',
            'green' => 'bg-green-100 text-green-800',
            'yellow' => 'bg-yellow-100 text-yellow-800',
            'red' => 'bg-red-100 text-red-800',
            'orange' => 'bg-orange-100 text-orange-800',
            'gray' => 'bg-gray-100 text-gray-800'
        ];
        
        return $colorClasses[$color] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get media file URL
     */
    public function getMediaUrlAttribute()
    {
        if ($this->media_file) {
            return Storage::url($this->media_file);
        }
        return null;
    }

    /**
     * Check if post has media
     */
    public function hasMedia()
    {
        return !empty($this->media_file) || !empty($this->content) || !empty($this->external_link);
    }

    /**
     * Get media type
     */
    public function getMediaUrl()
    {
        if (!$this->hasMedia()) {
            return null;
        }
        
        // Check if file exists in storage
        $fullPath = storage_path('app/public/' . $this->media_file);
        if (file_exists($fullPath)) {
            return asset('storage/' . $this->media_file);
        }
        
        // Fallback to asset helper
        return asset('storage/' . $this->media_file);
    }

    public function getMediaType()
    {
        if ($this->media_file) {
            $extension = pathinfo($this->media_file, PATHINFO_EXTENSION);
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv'];
            $audioExtensions = ['mp3', 'wav', 'ogg', 'flac'];
            
            if (in_array(strtolower($extension), $imageExtensions)) {
                return 'image';
            } elseif (in_array(strtolower($extension), $videoExtensions)) {
                return 'video';
            } elseif (in_array(strtolower($extension), $audioExtensions)) {
                return 'audio';
            }
        }
        
        if ($this->content) {
            return 'text';
        }
        
        if ($this->external_link) {
            return 'link';
        }
        
        return 'none';
    }

    /**
     * Scope to filter by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to filter public posts
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope to filter featured posts
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to filter by tags
     */
    public function scopeWithTags($query, $tags)
    {
        if (is_array($tags)) {
            foreach ($tags as $tag) {
                $query->whereJsonContains('tags', $tag);
            }
        } else {
            $query->whereJsonContains('tags', $tags);
        }
        return $query;
    }

    /**
     * Get tags as array
     */
    public function getTagsArrayAttribute()
    {
        if (is_array($this->tags)) {
            return $this->tags;
        }
        
        if (is_string($this->tags)) {
            return json_decode($this->tags, true) ?? [];
        }
        
        return [];
    }

    /**
     * Set tags from array or comma-separated string
     */
    public function setTagsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['tags'] = json_encode($value);
        } elseif (is_string($value)) {
            // Convert comma-separated string to array
            $tags = array_map('trim', explode(',', $value));
            $tags = array_filter($tags); // Remove empty values
            $this->attributes['tags'] = json_encode($tags);
        } else {
            $this->attributes['tags'] = json_encode([]);
        }
    }
} 