<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\MorphTo;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /** 
     * @var bool
     * */

    public $timestamps = true;

    public $default_profile ;

    public function __construct() {
        $this->default_profile = storage_path() . '/app/public/profile_pictures/default.png' ;
    }
    /**
     * 
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first name',
        'last name',
        'phoneNumber',
        'email',
        'password',
        'image',
        'reg_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function person(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'person_type', 'person_id');
    }
}