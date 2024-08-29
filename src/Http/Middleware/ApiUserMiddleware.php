<?php

namespace Takshak\Ashop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class ApiUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        $accessToken = PersonalAccessToken::findToken($token);
        $user = $accessToken ? $accessToken->tokenable : null;
        if($user)
        {
            Auth::login($user);
        }

        return $next($request);
    }
}