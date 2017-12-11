<?php

namespace App\Extensions;

use Carbon\Carbon;
use SessionHandlerInterface;
use Illuminate\Session\ExistenceAwareInterface;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Contracts\Container\Container;

class SecurityHandler  implements SessionHandlerInterface, ExistenceAwareInterface
{
    /**
     * The database connection instance.
     *
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $connection;

    /**
     * The name of the session table.
     *
     * @var string
     */
    protected $table;

    /*
     * The number of minutes the session should be valid.
     *
     * @var int
     */
    protected $minutes;

    /*
     * The number of minutes remember_me should be valid.
     *
     * @var int
     */
    protected $remember_minutes;

    /**
     * The container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * The existence state of the session.
     *
     * @var bool
     */
    protected $exists;

    /**
     * Create a new database session handler instance.
     *
     * @param  \Illuminate\Database\ConnectionInterface  $connection
     * @param  string  $table
     * @param  string  $minutes
     * @param  \Illuminate\Contracts\Container\Container|null  $container
     * @return void
     */
    public function __construct(ConnectionInterface $connection, $table,$remember_minutes, $minutes, Container $container = null)
    {
        $this->table = $table;
        $this->minutes = $minutes;
        $this->remember_minutes = $remember_minutes;
        $this->container = $container;
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function open($savePath, $sessionName)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        $session =  $this->getQuery()->where('sid', $sessionId)->first();
        if (null === $session) {
            return;
        }

        if ( 1 === $session->remember_me) {
            if ($session->logged_in < Carbon::now()->subMinutes($this->remember_minutes)->getTimestamp()) {
                $this->exists = false;

                return;
            }
        } else {
            if ($session->activity < Carbon::now()->subMinutes($this->minutes)->getTimestamp()) {
                $this->exists = false;
                return;
            }
        }

        if (isset($session->value)) {
            $this->exists = true;
            return base64_decode($session->value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        $payload = $this->getDefaultPayload($data);

        if (! $this->exists) {
            $this->read($sessionId);
        }

        if ($this->exists) {
            $this->getQuery()->where('sid', $sessionId)->update($payload);
        } else {
            $payload['sid'] = $sessionId;

            $this->getQuery()->insert($payload);
        }

        $this->exists = true;
    }

    /**
     * Get the default payload for the session.
     *
     * @param  string  $data
     * @return array
     */
    protected function getDefaultPayload($data)
    {
        $payload = ['value' => base64_encode($data), 'activity' => time()];

        if (! $container = $this->container) {
            return $payload;
        }

        if ($container->bound('request')) {
            $payload['ip'] = $container->make('request')->ip();
        }

        return $payload;
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        $this->getQuery()->where('sid', $sessionId)->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function gc($lifetime)
    {
        $this->getQuery()->where('remember_me', 0)->where('activity', '<=', time() - $lifetime)->delete();
        $this->getQuery()->where('remember_me', 1)->where('logged_in', '<=', time() - 86400 * 14)->delete();
    }

    /**
     * Get a fresh query builder instance for the table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getQuery()
    {
        return $this->connection->table($this->table);
    }

    /**
     * Set the existence state for the session.
     *
     * @param  bool  $value
     * @return $this
     */
    public function setExists($value)
    {
        $this->exists = $value;

        return $this;
    }
}
