<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\v1\LoginRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request){
        $attributes = $request->validated();

        $user = User::where('phone', $attributes['phone'])->first();

        if (!$user) {
            return response()->json([
                'code' => 404,
                'message' => 'User not found',
            ], 404);
        }

        if (!$user->hasVerifiedPhone()) {
            return response()->json([
                'code' => 401,
                'message' => 'please verify your phone number.',
            ], 401);
        }

        $user->sendPhoneVerificationNotification();

        $device = substr($request->userAgent() ?? ' ', 0, 255);
        $token = $user->createToken($device)->plainTextToken;

        return response()->json([
            'code' => 200,
            'message' => 'Enter the code sent to your phone number via SMS to login',
            'token' => $token,
        ], 200);
    }

    public function logout(){
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return response()->json([
            'code' => 200,
            'message' => 'User logged out successfully',
        ], 200);
    }
}
