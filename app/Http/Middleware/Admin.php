<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;


class Admin extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {

            $user = JWTAuth::parseToken()->authenticate();
            if ($user->role_id == 1) {

                return $next($request)
                    ->header('Access-Control-Allow-Origin', '*')
                    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            }
            else {
                return abort(401);
            }
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['status' => 'Token is Invalid']);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['status' => 'Token is Expired']);
            } else {
                throw new AccessDeniedException('Not have access', 403);
            }
        }

    }
}
