<?php

namespace AnimeSite\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UpdateLastSeen
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (
                is_null($user->last_seen_at) ||
                now()->diffInMinutes($user->last_seen_at) >= 1
            ) {
                $user->forceFill(['last_seen_at' => now()])->save();
            }
        }

        return $next($request);
    }
}
