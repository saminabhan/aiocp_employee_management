<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserActivity;
use App\Models\PageVisit;

class ActivityLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

public function handle($request, Closure $next)
{
    $response = $next($request);

    if ($request->hasSession()) {

    \App\Models\UserActivity::create([
        'user_id'        => auth()->id(),
        'url'            => $request->fullUrl(),
        'method'         => $request->method(),
        'ip'             => $request->ip(),
        'user_agent'     => $request->header('User-Agent'),

        'action_type'    => $request->route()?->getName() ?? 'unknown',

        'additional_data' => json_encode([
            'query'   => $request->query(),
            'payload' => $request->except(['password', 'password_confirmation']),
            'session' => $request->session()?->all() ?? null,
        ]),
    ]);
    }

    return $response;
}
}
