<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsorship extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'package_id',
        'price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
public function package()
{
    return $this->belongsTo(Package::class);
}
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
