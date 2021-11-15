<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class APIGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $guard = config('services.API_GUARD');

        if ($request->header('app') !== $guard['app'] || $request->header('token') !== $guard['token']) {
            return response()->json(['ok' => false, 'body' => 'unauthorized'], 401);
        }

        return $next($request);
    }
}
