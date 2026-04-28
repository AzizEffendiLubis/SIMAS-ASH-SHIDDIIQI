<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckMenuAccess;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Register the custom middleware alias
        app('router')->aliasMiddleware('menu', CheckMenuAccess::class);
    }
}
