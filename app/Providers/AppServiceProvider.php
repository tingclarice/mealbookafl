<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // For SSL Certificate (without this google login wont work)
        // if (app()->environment('local')) {
        //     putenv('CURL_CA_BUNDLE=' . base_path('certs/cacert.pem'));
        // }
        if(config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
