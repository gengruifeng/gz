<?php

namespace App\Extensions;

use Illuminate\Support\Manager;

class SecurityManager extends Manager
{
    /**
     * Call a custom driver creator.
     *
     * @param  string  $driver
     * @return mixed
     */
    protected function callCustomCreator($driver)
    {
        return $this->buildSession(parent::callCustomCreator($driver));
    }

    /**
     * Create an instance of the database session driver.
     *
     * @return \Illuminate\Session\Store
     */
    protected function createDatabaseDriver()
    {
        $connection = $this->getDatabaseConnection();

        $table = $this->app['config']['security.table'];

        $lifetime = $this->app['config']['security.lifetime'];
        
        $remembertime = $this->app['config']['security.remembertime'];

        return $this->buildSession(new SecurityHandler($connection, $table,$remembertime, $lifetime, $this->app));
    }

    /**
     * Get the database connection for the database driver.
     *
     * @return \Illuminate\Database\Connection
     */
    protected function getDatabaseConnection()
    {
        $connection = $this->app['config']['security.connection'];

        return $this->app['db']->connection($connection);
    }

    /**
     * Create an instance of the Memcached session driver.
     *
     * @return \Illuminate\Session\Store
     */
    protected function createMemcachedDriver()
    {
        return $this->createCacheBased('memcached');
    }

    /**
     * Create an instance of the Redis session driver.
     *
     * @return \Illuminate\Session\Store
     */
    protected function createRedisDriver()
    {
        $handler = $this->createCacheHandler('redis');

        $handler->getCache()->getStore()->setConnection($this->app['config']['security.connection']);

        return $this->buildSession($handler);
    }

    /**
     * Build the session instance.
     *
     * @param  \SessionHandlerInterface  $handler
     * @return \Illuminate\Session\Store
     */
    protected function buildSession($handler)
    {
        if ($this->app['config']['security.encrypt']) {
            return new EncryptedSecurityStore(
                $this->app['config']['security.cookie'], $handler, $this->app['encrypter']
            );
        } else {
            return new SecurityStore($this->app['config']['security.cookie'], $handler);
        }
    }

    /**
     * Get the session configuration.
     *
     * @return array
     */
    public function getSecurityConfig()
    {
        return $this->app['config']['security'];
    }

    /**
     * Get the default session driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['security.driver'];
    }

    /**
     * Set the default session driver name.
     *
     * @param  string  $name
     *
     * @return void
     */
    public function setDefaultDriver($name)
    {
        $this->app['config']['security.driver'] = $name;
    }
}
