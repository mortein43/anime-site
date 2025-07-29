<?php

namespace AnimeSite\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogLastUserActivity
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Щоб не записувати кожну секунду — оновлюємо тільки якщо минуло 1 хвилина
            if (!$user->last_seen_at || now()->diffInMinutes($user->last_seen_at) >= 1) {
                $user->update(['last_seen_at' => now()]);
            }
        }

        return $next($request);
    }
}
