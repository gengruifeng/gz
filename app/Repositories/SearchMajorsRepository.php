<?php

namespace App\Repositories;

use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;

use DB;
use Log;
use App\Utils\HttpStatus;

class SearchMajorsRepository extends Repository implements RepositoryInterface
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
                ->from('cv_majors')
                ->match('name', $this->input['q'])
                ->limit(5)
                ->execute();

            if (empty($query)) {
                $this->accepted = false;
            }
        }

        if ($this->passes()) {
            $majorId = [];
            foreach ($query as $value) {
                $majorId[] = $value['id'];
            }

            if (0 < $majorIdCount = count($majorId)) {
                $tags = DB::select('SELECT id, name FROM cv_majors WHERE id IN('.implode(',', $majorId).') LIMIT :limit', [':limit' => $majorIdCount]);

                foreach ($tags as $value) {
                    $this->biz[] = [
                        'source_id' => 'major',
                        'id' => $value->id,
                        'name' => $value->name,
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
