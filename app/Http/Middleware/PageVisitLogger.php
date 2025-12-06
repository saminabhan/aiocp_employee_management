<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\PageVisit;

class PageVisitLogger
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($request->isMethod('GET')) {

            $visit = PageVisit::firstOrCreate(
                [
                    'user_id' => auth()->id(),
                    'url'     => $request->path(),
                ],
                [
                    'count' => 0
                ]
            );

            $visit->increment('count');
            $visit->update(['last_visited_at' => now()]);
        }

        return $response;
    }
}
