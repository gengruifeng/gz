<?php

namespace App\Http\Requests;

use RuntimeException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RiddleRequest extends Request
{
    /**
     * Get the security associated with the request.
     *
     * @return \Illuminate\Session\Store
     *
     * @throws \RuntimeException
     */
    public function security()
    {
        if (! $this->hasSecurity()) {
            throw new RuntimeException('Security store not set on request.');
        }

        return $this->getSecurity();
    }

    /**
     * Get the credential making the request.
     *
     * @param  string|null  $guard
     *
     * @return mixed
     */
    public function credential($guard = null)
    {
        return call_user_func($this->getUserResolver(), $guard);
    }

    /**
     * Whether the request contains a Session object.
     *
     * This method does not give any information about the state of the session object,
     * like whether the session is started or not. It is just a way to check if this Request
     * is associated with a Session instance.
     *
     * @return bool true when the Request contains a Session object, false otherwise
     */
    public function hasSecurity()
    {
        return null !== $this->security;
    }

    /**
     * Sets the Security.
     *
     * @param SessionInterface $session The Session
     */
    public function setSecurity(SessionInterface $security)
    {
        $this->security = $security;
    }

    /**
     * Gets the Session.
     *
     * @return SessionInterface|null The session
     */
    public function getSecurity()
    {
        return $this->security;
    }
}
