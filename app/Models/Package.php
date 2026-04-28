<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
     protected $fillable = [
        'name',
        'logo_size',
        'booth',
        'speaking_slot',
        'tickets',
        'price'
    ];
    public function sponsorships()
    {
        return $this->hasMany(Sponsorship::class);
    }
}
