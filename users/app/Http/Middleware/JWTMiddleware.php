<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTMiddleware
{
    public function handle($request, Closure $next)
    {
        Log::info('sdlfjslçd');
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            Log::info($e->getMessage());
            return response()->json(['error' => 'Token not valid'], 401);
        }

        return $next($request);
    }
}
