<?php

namespace App\Providers;

use Session;
use App\Extensions\KingSessionHandler;
use Illuminate\Support\ServiceProvider;

class SessionServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services..
     *
     * @param void
     *
     * @return void
     */
    public function boot()
    {
        Session::extend('king', function($app) {
            // Return implementation of SessionHandlerInterface...
            $connection = $app['db']->connection($this->app['config']['session.connection']);

            return new KingSessionHandler($connection, $app['config']['session.table'], $app['config']['session.lifetime'], $app);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}
