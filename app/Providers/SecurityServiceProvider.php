<?php

namespace App\Providers;

use App\Extensions\SecurityManager;
use Illuminate\Support\ServiceProvider;

class SecurityServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSecurityManager();

        $this->registerSecurityDriver();
    }

    /**
     * Register the session manager instance.
     *
     * @return void
     */
    protected function registerSecurityManager()
    {
        $this->app->singleton(SecurityManager::class, function ($app) {
            return new SecurityManager($app);
        });
    }

    /**
     * Register the session driver instance.
     *
     * @return void
     */
    protected function registerSecurityDriver()
    {
        $this->app->singleton(SecurityStore::class, function ($app) {
            // First, we will create the session manager which is responsible for the
            // creation of the various session drivers when they are needed by the
            // application instance, and will resolve them on a lazy load basis.
            $manager = $app->make(SecurityManager::class);

            return $manager->driver();
        });
    }
}
