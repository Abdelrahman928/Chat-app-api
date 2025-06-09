<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\RegisterRequest;
use App\Http\Requests\v1\VerifyPhoneRequest;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Services\PhoneVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request){
        $attributes = $request->validated();

        $user = User::create($attributes);

        $device = substr($request->userAgent() ?? ' ', 0, 255);
        $token = $user->createToken($device)->plainTextToken;

        event(new Registered($user));

        return response()->json([
            'code' => 201,
            'message' => 'User created successfully',
            'token' => $token,
        ], 201);
    }

    public function verifyPhone(VerifyPhoneRequest $request){
        $code = $request->validated();

        $user = Auth::user();

        $phoneVerificationService = new PhoneVerificationService($user);

        $result = $phoneVerificationService->verifyPhone($code['code']);

        if (!$result['success']) {
            return response()->json([
                'code' => $result['code'],
                'message' => $result['message'],
            ], $result['code']);
        }

        $user->markPhoneAsVerified();

        return response()->json([
            'code' => 200,
            'message' => 'Phone verified successfully',
        ], 200);
    }

    public function resendVerificationCode(){

        $user = Auth::user();

        if (!$user || $user->hasVerifiedPhone()) {
            return response()->json([
                'code' => 400,
                'message' => 'Invalid request or phone already verified',
            ], 400);
        }

        if ($user->verification_code_sent_at && $user->verification_code_sent_at->addMinutes(2) > now()) {
            return response()->json([
                'code' => 429,
                'message' => 'Please wait before requesting a new code',
            ], 429);
        }

        $user->sendPhoneVerificationNotification();

        return response()->json([
            'code' => 200,
            'message' => 'Verification code sent successfully',
        ], 200);
    }
}
