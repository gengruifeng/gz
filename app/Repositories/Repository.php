<?php

namespace App\Repositories;

use App\Support\ErrorBag;
use App\Utils\HttpStatus;

abstract class Repository
{
    /**
     * The input parameters.
     *
     * @var array
     */
    protected $input;

    /**
     * The contract accepted status.
     *
     * @var boolean
     */
    public $accepted = true;

    /**
     * The contract status.
     *
     * @var integer
     */
    public $status = HttpStatus::HTTP_OK;

    /**
     * The contract description.
     *
     * @var string
     */
    public $description;

    /**
     * The message bag instance.
     *
     * @var array
     */
    public $errors;

    /**
     * The business data under contraction.
     *
     * @var array
     */
    public $biz = [];

    /**
     * Create a new repository
     *
     * @param array $input Request Input
     *
     * @return void
     */
    public function __construct(array $input = [])
    {
        $this->input = $input;

        $this->errors = new ErrorBag();
    }

    /**
     * Detemine if the input satisfies the repository
     *
     * @param void
     *
     * @return boolean
     */
    public function passes()
    {
        return $this->accepted;
    }
}
