<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use Illuminate\Support\Facades\Log;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        Log::info("", [""=> $request]);
        try 
        {
            $user = JWTAuth::parseToken()->authenticate();
            Log::info("", ["user"=> $user]);
        } 
        catch (Exception $e) 
        {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) 
            {
                return response()->json(['status' => 'Token is Invalid']);
            } 
            else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) 
            {
                try
                {
                    $newToken = JWTAuth::refresh(JWTAuth::getToken());
                    $request->headers->set('Authorization', 'Bearer ' . $newToken);
                    $user = JWTAuth::setToken($newToken)->toUser();
                } 
                catch (Exception $e) 
                {
                    return response()->json(['status' => 'Token could not be refreshed'], 401);
                }
            } 
            else 
            {
                return response()->json(['status' => 'Authorization Token not found']);
            }
        }
        return $next($request);
    }
}