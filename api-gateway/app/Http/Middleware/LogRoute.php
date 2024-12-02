<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\RouteLog;

class LogRoute
{
    public function handle(Request $request, Closure $next)
    {
        // Chama a próxima requisição
        $response = $next($request);

        // Registra o log
        RouteLog::create([
            'route' => $request->path(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return $response;
    }
}
