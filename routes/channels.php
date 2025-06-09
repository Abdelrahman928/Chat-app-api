<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Chat;

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    return $user && Chat::whereHas('users', function ($query) use ($user, $chatId) {
        $query->where('user_id', $user->id)->where('chat_id', $chatId);
    })->exists();
});
