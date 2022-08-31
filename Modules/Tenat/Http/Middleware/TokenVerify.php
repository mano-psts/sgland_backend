<?php

namespace Modules\Tenat\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TokenVerify
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
        $token = $request->header('access_token');
    if ($token != 'XXXX') {
        return response()->json(['message' => 'API Key not found!'], 401);
    }
    return $next($request);
    }
}
