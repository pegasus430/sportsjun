<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \App\Http\Middleware\TeamMiddleware::class,
        \App\Http\Middleware\MarketplaceMiddleware::class,
        \App\Http\Middleware\LogActivitiesMiddleware::class,
        \App\Http\Middleware\OrganizationMiddleware::class,
        // \App\Http\Middleware\PhotosMiddleware::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        // 'marketplace' => 'App\Http\Middleware\MarketplaceMiddleware',
        'matchschedule' => 'App\Http\Middleware\MatchScheduleMiddleware',
		'photos'=>'App\Http\Middleware\PhotosMiddleware',
        'role'=>'App\Http\Middleware\Role',
        // 'team' => 'App\Http\Middleware\TeamMiddleware',
        'jwt.auth' => 'Tymon\JWTAuth\Middleware\GetUserFromToken',
        'jwt.refresh' => 'Tymon\JWTAuth\Middleware\RefreshToken',
    ];
}
