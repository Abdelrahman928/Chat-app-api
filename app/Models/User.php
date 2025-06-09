<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Contracts\MustVerifyPhone;
use App\Traits\VerifiesPhone;

class User extends Authenticatable implements MustVerifyPhone
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, VerifiesPhone;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
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
            'verification_code_sent_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function routeNotificationForVonage($notification)
    {
        return $this->phone;
    }

    public function chats()
    {
        return $this->belongsToMany(Chat::class, 'chat_user', 'user_id', 'chat_id')
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}