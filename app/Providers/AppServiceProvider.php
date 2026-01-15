<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Xefreh\Judge0PhpClient\Cache\ArrayCache;
use Xefreh\Judge0PhpClient\Judge0Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Judge0Client::class, function () {
            return new Judge0Client(
                apiHost: config('judge0.api_host'),
                apiKey: config('judge0.api_key'),
                cache: new ArrayCache,
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
