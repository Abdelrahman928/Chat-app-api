<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\RegisterController;
use App\Http\Controllers\api\v1\LoginController;
use App\Http\Controllers\api\v1\auth\ChatController;
use App\Http\Controllers\api\v1\auth\MessageController;

Route::prefix('v1')
    ->name('api.v1.')
    ->group(function () {
        Route::post('register', [RegisterController::class, 'register'])->middleware('throttle:api');
        Route::post('login', [LoginController::class, 'login'])->middleware('throttle:api');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('verify-phone', [RegisterController::class, 'verifyPhone'])->middleware('throttle:api');
            Route::post('resend-verification-code', [RegisterController::class, 'resendVerificationCode'])->middleware('throttle:api');
        });

        Route::middleware('auth:sanctum', 'verified')->group(function () {
            Route::post('chats', [ChatController::class, 'store']);
            Route::get('chats', [ChatController::class, 'index']);
            Route::get('chats/{chat}', [ChatController::class, 'show']);
            Route::delete('chats/{chat}', [ChatController::class, 'destroy']);
            Route::post('chats/{chat}/messages', [MessageController::class, 'sendMessage']);
            Route::delete('chats/{chat}/messages/{message}', [MessageController::class, 'destroy']);
            Route::post('logout', [LoginController::class, 'logout']);
        });
    });
