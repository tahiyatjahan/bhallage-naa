<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreativePostLike extends Model
{
    protected $fillable = [
        'user_id',
        'creative_post_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creativePost(): BelongsTo
    {
        return $this->belongsTo(CreativePost::class);
    }
} 