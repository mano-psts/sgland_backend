<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TokenVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        $token = $request->header('access_token');
        if (!$token) {
            return response()->json([
                'status' => 401,
                'message' => 'Access Token not found!']
            );
        }
        return $next($request);
    }
}
