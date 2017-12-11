<?php

namespace App\Repositories;

use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;

use DB;
use Log;
use Validator;

use App\Utils\HttpStatus;
use App\Utils\Computing;
use App\Utils\Pagination;

class CVTemplateSearchRepository extends Repository implements RepositoryInterface
{
    /**
     * 简历模版搜索
     *
     * {@inheritdoc}
     */
    public function contract()
    {
        $page = 1 < (int) $this->input['page'] ? $this->input['page'] : 1;

        $connection = new Connection();
        $connection->setParams(['host' => env('SPHINX_HOST'), 'port' => env('SPHINX_PORT')]);

        $count = SphinxQL::create($connection)->select('count(*) count')
            ->from('cv_templates')
            ->match('subject', $this->input['q'])
            ->execute();

        $total = (int) $count[0]['count'];

        if (1 > $total) {
            $this->accepted = false;
        }

        $this->biz = [
            'professions' => DB::select('SELECT id, title FROM cv_professions'),
            'templates' => [],
            'input' => $this->input,
            'total' => $total,
            'page' => $page,
            'query' => []
        ];

        if ($this->passes()) {
            $sphinx = SphinxQL::create($connection)->select('id')
                ->from('cv_templates')
                ->match('subject', $this->input['q'])
                ->limit(1 < $page ? ($page - 1) * 10 : 0, 10)
                ->execute();

            $templateId = [];
            foreach ($sphinx as $value) {
                $templateId[] = $value['id'];
            }

            // 附加已选 Query String
            $query = [
                'q' => '&q='.$this->input['q']
            ];

            $templates = DB::table('cv_templates')->orderBy('updated_at', 'desc');

            $templates->whereIn('id', $templateId);

            if (1 < $page) {
                $templates->skip(($page - 1) * 10);
            }

            $templates->take(10);

            if (! empty($this->input['profession'])) {
                $templates->where('profession_id', $this->input['profession']);
                $query['profession'] = '&profession='.$this->input['profession'];
            }

            if (! empty($this->input['language'])) {
                $templates->where('language', $this->input['language']);
                $query['language'] = '&language='.$this->input['language'];
            }

            if ('' !== $this->input['colorscheme'] && null !== $this->input['colorscheme']) {
                $templates->where('colorscheme', $this->input['colorscheme']);
                $query['colorscheme'] = '&colorscheme='.$this->input['colorscheme'];
            }

            if ('trending' === $this->input['tab']) {
                $templates->orderBy('downloaded', 'desc');
                $query['tab'] = '&tab='.$this->input['tab'];
            } else {
                $templates->orderBy('id', 'desc');
                $query['tab'] = '&tab='.'latest';
            }
            if($this->input['async']){
                $this->biz['templates'] = $templates->get(['id', 'subject', 'feature', 'preview', 'downloaded']);
            }
            $this->biz['query'] = $query;
        }
        if(empty($this->biz['templates'])){
            $this->accepted = false;
            $this->status = 404;
            $this->description = '暂无数据';
        }
    }

    /**
     * Wrap the contract result to JSON object.
     *
     * {@inheritdoc}
     */
    public function wrap()
    {
        $wrapper = [
        ];

        if (! $this->passes()) {
            $wrapper = [
                'error_id' => $this->status,
                'description' => $this->description,
                'error_name' => HttpStatus::$statusTexts[$this->status],
            ];
        }

        return $wrapper;
    }
    /**
     * Validate the input
     *
     * @param void
     *
     * @return void
     */
    private function validate()
    {
    }
}
