<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
class User extends Authenticatable  implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'logo',
        'bio',
        'specialty',
        'location',
        'topic_id',
        'status',
        'job_title',
        'company_name',
        'company_number',
        'full_mailing_address',
        'profile_picture',
        'cv',
        'country',
        'city',
        'linkedin',
        ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function topic() {
        return $this->belongsTo(Topic::class);
    }

    public function eventsCreated() {
        return $this->hasMany(Event::class, 'created_by');
    }
    public function workshops() {
        return $this->hasMany(Workshop::class, 'speaker_id');
    }
    public function bookings() {
        return $this->hasMany(Booking::class);
    }
    public function favorites() {
        return $this->hasMany(Favorite::class);
    }
    public function workshopFavorites() {
        return $this->hasMany(WorkshopFavorite::class);
    }
    public function messages() {
        return $this->hasMany(Message::class, 'sender_id');
    }
    protected $appends = ['status_text'];
    public function getStatusTextAttribute()
{
    return match ((int) $this->status)  {
        0 => 'pending',
        1 => 'approved',
        2 => 'rejected',
        default => 'unknown',
    };
}
}

