<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        app('router')->aliasMiddleware('role', \App\Http\Middleware\CheckRoles::class);


        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }
}
