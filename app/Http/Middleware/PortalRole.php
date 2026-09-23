<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PortalRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if (! $user || ! $user->hasAnyRole($roles)) {
            abort(403, 'You are not authorized to access this portal.');
        }

        if (! $user->is_active || $user->is_deleted || ! $user->school_id) {
            abort(403, 'Your account is inactive or not linked to a school.');
        }

        return $next($request);
    }
}
