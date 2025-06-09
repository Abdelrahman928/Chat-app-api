<?php

use App\Http\Middleware\EnsurePhoneIsVerifiedMiddleware;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->throttleWithRedis();
        $middleware->alias([
            'verified' => EnsurePhoneIsVerifiedMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                if ($e instanceof ValidationException) {
                    return response()->json([
                        'message' => 'Validation Error',
                        'errors' => $e->errors(),
                    ], 422);
                }

                if ($e instanceof NotFoundHttpException) {
                    return response()->json([
                        'message' => 'Not Found',
                        'error' => 'The requested resource could not be found.',
                    ], 404);
                }

                if ($e instanceof ModelNotFoundException) {
                    return response()->json([
                        'message' => 'Not Found',
                        'error' => 'The requested resource could not be found.',
                    ], 404);
                }

                return response()->json([
                    'message' => 'Internal Server Error',
                    'error' => 'An unexpected error occurred.',
                ], 500);
            }
        });
    })->create();
