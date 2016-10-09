<?php

namespace App\Providers;

use App\Foundation\Contracts\DatabaseMapper;
use Illuminate\Support\ServiceProvider;

class MapperServiceProvider extends ServiceProvider
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
        /*$this->app->bind('App\Foundation\Contracts\Mapper', 'App\Foundation\Contracts\DatabaseMapper');*/

        $this->app->singleton('map', function ($app) {
            return new DatabaseMapper();
        });
    }
}
