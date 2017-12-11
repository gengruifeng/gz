<?php

namespace App\Repositories;

use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;

use App\Entity\Tag;

use DB;
use Log;
use App\Utils\HttpStatus;

class TagsCreateRepository extends Repository implements RepositoryInterface
{
    /**
     * 认证登录
     *
     * {@inheritdoc}
     */
    public function contract()
    {
    }

    /**
     * Wrap the contract result to JSON object.
     *
     * {@inheritdoc}
     */
    public function wrap()
    {
    }
}
