<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpeakerApplication extends Model
{
    protected $fillable = [
        'user_id',
        'session_title',
        'summary',
        'duration',
        'session_format',
    ];
     public function user()
    {
        return $this->belongsTo(User::class);
    }
}
