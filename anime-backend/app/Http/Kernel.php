<?php

namespace AnimeSite\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Глобальні middleware, що виконуються для кожного запиту.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [

    ];

    /**
     * Групи middleware для web (сесії, CSRF, cookies тощо).
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \Fruitcake\Cors\HandleCors::class,
            \AnimeSite\Http\Middleware\VerifyCsrfToken::class,
            \AnimeSite\Http\Middleware\LogLastUserActivity::class,
        ],

        'api' => [
            // 'throttle:api' - обмеження кількості запитів
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \AnimeSite\Http\Middleware\UpdateLastSeen::class,
        ],
    ];

    /**
     * Реєстрація middleware, які можна застосовувати окремо (middleware aliases).
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
//        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
//        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // Laravel Sanctum для API авторизації через токени
//        'auth:sanctum' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    ];
}
