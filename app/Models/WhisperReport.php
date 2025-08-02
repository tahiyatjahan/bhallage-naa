<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhisperReport extends Model
{
    protected $fillable = ['whisper_id', 'reason'];

    public function whisper()
    {
        return $this->belongsTo(Whisper::class);
    }
}
