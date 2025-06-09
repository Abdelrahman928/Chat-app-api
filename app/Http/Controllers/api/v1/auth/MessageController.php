<?php

namespace App\Http\Controllers\api\v1\auth;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Http\Requests\v1\MessageRequest;
use App\Events\MessageSent;
use App\Models\Message;
use App\Http\Resources\v1\MessageResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MessageController extends Controller
{
    public function sendMessage(MessageRequest $request, Chat $chat){
        $user = Auth::user();

        Gate::authorize('view', $chat);
        
        $message = $chat->messages()->create([
            'body' => $request->body,
            'user_id' => $user->id,
        ]);
        
        broadcast(new MessageSent($message, $user))->toOthers();
        
        return response()->json([
            'code' => 201,
            'message' => 'Message sent successfully',
            'data' => new MessageResource($message),
        ], 201);
    }

    public function destroy(Chat $chat, Message $message){
        Gate::authorize('delete', $message);
        Gate::authorize('view', $chat);
        
        if ($message->chat_id !== $chat->id) {
            return response()->json([
                'code' => 404,
                'message' => 'Message not found',
            ], 404);
        }

        $message->delete();

        return response()->json([
            'code' => 200,
            'message' => 'Message deleted successfully',
        ], 200);
    }
}
