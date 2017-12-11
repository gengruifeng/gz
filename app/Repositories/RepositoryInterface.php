<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Contract a repository serves as business logic with multi entities to implement.
     *
     * @param void
     *
     * @return void
     */
    public function contract();

    /**
     * Wrap the contract result to JSON object.
     *
     * @param void
     *
     * @return array
     */
    public function wrap();
}
