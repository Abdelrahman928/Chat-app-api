<?php

namespace App\Http\Middleware;

use App\Contracts\MustVerifyPhone;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePhoneIsVerifiedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user() instanceof MustVerifyPhone && !$request->user()->hasVerifiedPhone()) {
            return response()->json([
                'success' => false,
                'code' => 403,
                'message' => 'Your phone number must be verified before proceeding.',
            ], 403);
        }
        return $next($request);
    }
}
