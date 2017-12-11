<?php

namespace App\Support;

use Countable;

class ErrorBag implements Countable
{
    /**
     * All of the registered errors.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Create a new message bag instance.
     *
     * @param  array  $errors
     * @return void
     */
    public function __construct(array $errors = [])
    {
        foreach ($errors as $key => $value) {
            $this->errors[$key] = $value;
        }
    }

    /**
     * Get the keys present in the message bag.
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->errors);
    }

    /**
     * Add a message to the bag.
     *
     * @param  string  $key
     * @param  string  $message
     * @return $this
     */
    public function add($key, $message)
    {
        if ($this->isUnique($key, $message)) {
            $this->errors[$key] = $message;
        }

        return $this;
    }

    /**
     * Determine if a key and message combination already exists.
     *
     * @param  string  $key
     * @param  string  $message
     * @return bool
     */
    protected function isUnique($key, $message)
    {
        return ! isset($errors[$key]) || ! in_array($message, $errors[$key]);
    }

    /**
     * Get the raw errors in the container.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Determine if the message bag has any errors.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return ! $this->any();
    }

    /**
     * Determine if the message bag has any errors.
     *
     * @return bool
     */
    public function any()
    {
        return $this->count() > 0;
    }

    /**
     * Get the number of errors in the container.
     *
     * @return int
     */
    public function count()
    {
        return count($this->errors);
    }
}
