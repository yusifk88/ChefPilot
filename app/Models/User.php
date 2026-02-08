<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        "image_url",
        "bio",
        "google_user_id",
        "device_name",
        "device_model",
        "ip_address",
        "timezone",
        "country",
        "device_os",
        "theme"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_user_id',
        "device_name",
        "device_model",
        "ip_address",
        "timezone",
        "device_os"
    ];

    public function routeNotificationForOneSignal(): array
    {
        return ['include_external_user_ids' => [(string)$this->id]];
    }

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


    public function follow(): HasOne|User
    {

        return $this->hasOne(Follow::class, 'follower_id', 'id');

    }

}
