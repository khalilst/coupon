<?php

namespace App\Http\Middleware;

use Closure;

class Admin
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
        if (!optional(auth()->user())->isAdmin) {
            return json(['message' => __('auth.unauthorized')], 403);
        }

        return $next($request);
    }
}
