<?php

namespace App\Providers;

use App\Services\ContactService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Faker\Factory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ContactService::class, function ($app) {
            return new ContactService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::prefix('api')
            ->namespace('App\Http\Controllers')
            ->group(base_path('routes/api.php'));

        // Fix for the url() method in Storage facade
        Storage::macro('url', function ($path) {
            return url(Storage::path($path));
        });
    }
}
