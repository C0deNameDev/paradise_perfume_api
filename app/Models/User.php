<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @var bool
     * */
    public $timestamps = true;

    public $default_profile_picture_name = 'default';

    public $default_profile_picture = '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';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $types = [
        'client' => Client::class,
        'admin' => Admin::class,
    ];

    protected $fillable = [

        'email',
        'password',
        'profile_picture',
        'one_time_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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

    public function person(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'person_type', 'person_id');

    }
}
