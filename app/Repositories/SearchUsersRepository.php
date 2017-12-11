<?php

namespace App\Repositories;

use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;

use DB;
use Log;
use App\Utils\HttpStatus;

class SearchUsersRepository extends Repository implements RepositoryInterface
{
    /**
     * Sphinx Connection
     *
     * @var Connection
     */
    private $connection;

    /**
     * Construct Search Articles Instance
     *
     * @param array $input
     */
    public function __construct(array $input = [])
    {
        parent::__construct($input);

        $this->connection = new Connection();
        $this->connection->setParams(['host' => env('SPHINX_HOST'), 'port' => env('SPHINX_PORT')]);
    }

    /**
     * 认证登录
     *
     * {@inheritdoc}
     */
    public function contract()
    {
        if (null === $this->input['q']) {
            $this->accepted = false;
        }

        if ($this->passes()) {
            $query = SphinxQL::create($this->connection)->select()
                ->from('users')
                ->match('display_name', $this->input['q'])
                ->limit(10)
                ->execute();

            if (empty($query)) {
                $this->accepted = false;
            }
        }
        if ($this->passes()) {
            $userId = [];
            foreach ($query as $value) {
                $userId[] = $value['id'];
            }

            if (0 < $userIdCount = count($userId)) {
                $users = DB::select('SELECT id, display_name FROM users WHERE id IN('.implode(',', $userId).') LIMIT :limit', ['limit' => $userIdCount]);

                foreach ($users as $value) {
                    $this->biz[] = [
                        'source_id' => 'user',
                        'id' => $value->id,
                        'display_name' => $value->display_name,
                    ];
                }
            }
        }
    }

    /**
     * Wrap the contract result to JSON object.
     *
     * {@inheritdoc}
     */
    public function wrap()
    {
        $wrapper = [];

        if ($this->passes()) {
            return $this->biz;
        }

        return $wrapper;
    }
}
