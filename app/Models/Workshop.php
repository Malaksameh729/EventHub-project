<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
        protected $fillable = [
            'event_id',
            'title',
            'description',
            'location',
            'start_time',
            'end_time',
            'speaker_id',
            'capacity'
        ];
        public function speaker()
        {
            return $this->belongsTo(User::class, 'speaker_id');
        }
         public function event()
         {
             return $this->belongsTo(Event::class);
         }

         public function workshop()
          {
        return $this->belongsTo(Workshop::class);
    }
}
