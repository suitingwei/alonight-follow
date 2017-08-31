<?php

namespace Alonight\Follow;

use Illuminate\Support\ServiceProvider;

class AlonightFollowServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        $this->publishes([
            __DIR__ . '/Models/Followables.php'  => app_path('Models/Followables.php'),
            __DIR__ . '/Models/Likeable.php'     => app_path('Models/Likeable.php'),
            __DIR__ . '/Models/Favorable.php'    => app_path('Models/Favorable.php'),
            __DIR__ . '/Models/Subscribable.php' => app_path('Models/Subscribable.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}