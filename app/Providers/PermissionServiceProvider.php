<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        Route::macro('permission', function ($permission) {
            dd($permission);
//            if (!auth()->user()->hasPermission($permission)) {
//                abort(403, 'Unauthorized');
//            }
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
