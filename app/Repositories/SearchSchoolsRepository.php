<?php

namespace App\Repositories;

use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;

use DB;
use Log;
use App\Utils\HttpStatus;

class SearchSchoolsRepository extends Repository implements RepositoryInterface
{

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
        $schoolname = "'%".$this->input['q']."%'";

        if ($this->passes()) {

                $tags = DB::select('SELECT id, name FROM cv_school WHERE cityid = '.$this->input['cityid'].' and name like '.$schoolname.'  limit 5');

                foreach ($tags as $value) {
                    $this->biz[] = [
                        'source_id' => 'tag',
                        'id' => $value->id,
                        'name' => $value->name,
                    ];
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
