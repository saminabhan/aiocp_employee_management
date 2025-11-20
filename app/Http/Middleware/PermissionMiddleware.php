<?php

namespace App\Http\Middleware;

use Closure;

class PermissionMiddleware
{
    public function handle($request, Closure $next, $permission)
    {
        if (!auth()->check()) {
            abort(403);
        }

        if (!auth()->user()->hasPermission($permission)) {
            abort(403, 'Access Denied');
        }

        return $next($request);
    }
}
