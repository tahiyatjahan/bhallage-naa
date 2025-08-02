<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Whisper extends Model
{
    protected $fillable = ['content', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reports()
    {
        return $this->hasMany(WhisperReport::class);
    }
}
