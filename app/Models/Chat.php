<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'title',
    ];

    public static function createChat($user, $user1)
    {
        if (!$user1) {
            return ['success' => false, 'code' => 404, 'message' => 'User not found'];
        }

        if ($user->id === $user1->id) {
            return ['success' => false, 'code' => 400, 'message' => 'Cannot create chat with yourself'];
        }

        $existingChat = Chat::whereHas('users', function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        })
                        ->whereHas('users', function ($query) use ($user1) {
                            $query->where('user_id', $user1->id);
                        })
                        ->whereDoesntHave('users', function ($query) use ($user, $user1) {
                            $query->whereNotIn('user_id', [$user->id, $user1->id]);
                        })
                        ->first();

        if ($existingChat) {
            return ['success' => false, 'code' => 409, 'message' => 'Chat already exists', 'chat' => $existingChat];
        }

        $chat = Chat::create();
    
        $chat->users()->attach([$user->id, $user1->id]);
    
        return ['success' => true, 'chat' => $chat];
    }
        
    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_user', 'chat_id', 'user_id')
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}
