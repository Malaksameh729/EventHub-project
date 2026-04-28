<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'location',
        'start_time',
        'end_time',
        'capacity',
        'type',
        'venue_name',
        'address',
        'image',
        'price_type',
        'price',
        'latitude',
        'longitude',
        'created_by',
        'category_id',
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    public function favorites() {
        return $this->hasMany(Favorite::class);
    }

    public function workshops() {
        return $this->hasMany(Workshop::class);
    }
    public function category() {
        return $this->belongsTo(Category::class);
    }
   public function scopeWithIsFavorite($query, $userId)
{
    return $query->withCount([
        'favorites as is_favorite' => function ($q) use ($userId) {
            $q->where('user_id', $userId);
        }
    ]);
}
}
