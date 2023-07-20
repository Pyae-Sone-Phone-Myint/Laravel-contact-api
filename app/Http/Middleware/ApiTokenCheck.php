<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!$request->hasHeader('token')) {
            return response()->json([
                "message" => "Api token is required"
            ]);
        }
        if ($request->header('token') != "pspm") {
            return response()->json([
                "message" => "token invalid"
            ]);
        }

        return $next($request);
    }
}
