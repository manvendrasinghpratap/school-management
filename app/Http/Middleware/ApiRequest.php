<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->expectsJson() && $request->header('Accept') !== null && ! str_contains((string) $request->header('Accept'), 'application/json')) {
            return response()->json(['status' => false, 'message' => 'JSON responses are required.'], 406);
        }

        $response = $next($request);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
