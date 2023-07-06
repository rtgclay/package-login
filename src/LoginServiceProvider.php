<?php

namespace Smpl\Login;

use Illuminate\Support\ServiceProvider;

class LoginServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Smpl\Login\Http\Controllers\LoginController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'login');

        $this->publishes([
            __DIR__.'/../resources/config/login.php' => config_path('login.php')
        ],'config');
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/login')
        ],'views');
    }
}
