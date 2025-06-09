<?php

namespace App\Http\Controllers\api\v1\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\ChatRequest;
use App\Http\Resources\v1\ChatResource;
use App\Http\Resources\v1\MessageResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Chat;

class ChatController extends Controller
{
    public function store(ChatRequest $request){
        $user = Auth::user();
        $user1 = User::where('phone', $request->phone)->orWhere('username', $request->username)->first();

        $chat = Chat::createChat($user, $user1);

        if ($chat['success'] === false) {
            return response()->json([
                'code' => $chat['code'],
                'message' => $chat['message']
            ], $chat['code']);
        }

        return response()->json([
            'code' => 200,
            'chat' => $chat['chat']
        ], 200);
    }

    public function index(){
        $user = Auth::user();

        $chats = $user->chats()
            ->with('users', 'latestMessage.user')
            ->orderBy('updated_at', 'desc')
            ->cursorPaginate(10);

        if ($chats->isEmpty()) {
            return response()->json([
                'code' => 200,
                'message' => 'Start a new chat.'
            ], 200);
        }

        return response()->json([
            'code' => 200,
            'chats' => ChatResource::collection($chats)
        ], 200);
    }

    public function show(Chat $chat){
        Gate::authorize('view', $chat);

        $messages = $chat->messages()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->cursorPaginate(10);

        if ($messages->isEmpty()) {
            return response()->json([
                'code' => 200,
                'message' => 'This chat is empty',
            ], 200);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Chat retrieved successfully',
            'data' => [
                'chat' => new ChatResource($chat),
                'messages' => MessageResource::collection($messages),
            ],
        ], 200);
    }

    public function destroy(Chat $chat){
        Gate::authorize('delete', $chat);

        $chat->delete();

        return response()->json([
            'code' => 200,
            'message' => 'Chat deleted successfully',
        ], 200);
    }
}
