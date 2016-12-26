<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;

class HenryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        App::bind('henry', function()
        {
            return new App\Henry\HenryAPI;
        });
    }
}
