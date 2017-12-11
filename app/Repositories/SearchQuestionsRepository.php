<?php

namespace App\Repositories;

use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;

use DB;
use Log;
use Validator;
use App\Utils\Computing;

use App\Utils\HttpStatus;
use App\Utils\Pagination;

class SearchQuestionsRepository extends Repository implements RepositoryInterface
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
        $page = 1 < (int) $this->input['page'] ? $this->input['page'] : 1;
        $tab = '';
        switch ($this->input['tab']) {
            case 'trending':
                $tab = 'trending';
                break;

            case 'unanswered':
                $tab = 'unanswered';
                break;

            default:
                $tab = 'newest';
        }
        $this->biz = [
            'q' => $this->input['q'],
            'tab' => $tab,
            'page' => $page,
            'total' => 0,
            'page_total' => Pagination::total(0),
            'pagination' => Pagination::generate($page, 0),
            'questions' => []
        ];

        $this->validate();

        if ($this->passes()) {
            $count = SphinxQL::create($this->connection)->select('count(*) count')
                ->from('questions')
                ->match('subject', $this->input['q'])
                ->execute();

            $total = (int) $count[0]['count'];
            if (1 > $total) {
                $this->accepted = false;
            }
        }
        if ($this->passes()) {
            $this->biz['total'] = $total;
            $this->biz['page_total'] = Pagination::total($total);
            $this->biz['pagination'] = Pagination::generate($page, $total);

            $query = SphinxQL::create($this->connection)->select('id')
                ->from('questions')
                ->match('subject', $this->input['q'])
                ->limit((1 < $page ? ($page - 1) * 10 : 0), 10)
                ->execute();

            $questionId = [];
            foreach ($query as $value) {
                $questionId[] = $value['id'];
            }

            if (0 < $questionIdCount = count($questionId)) {
                $table = DB::table('questions')->where('created_at','<=',date('Y-m-d H:i:s',time()))->whereIn('id', $questionId)->take($questionIdCount);
                if($this->input['async'] === 1){
                    $questions = true;
                }
                if ($this->input['async'] === 2) {
                    $questions = $table->get(['id', 'subject']);
                    foreach ($questions as &$value) {
                        $value->source_id = 'question';
                        $value->subject = (mb_strlen($value->subject, 'utf-8') > 30 ? mb_substr($value->subject, 0, 30, 'utf-8').'...' : $value->subject);
                    }
                }
                if($this->input['async'] === 3) {
                    $questionToTags = DB::table('question_tags')->whereIn('question_id', $questionId)->get(['question_id', 'tag_id']);

                    $questionTagId = [];
                    foreach ($questionToTags as $questionToTag) {
                        $questionTagId[] = $questionToTag->tag_id;
                    }
                    //var_dump($questionTagId);die;
                    $tagsTable = DB::table('tags')->whereIn('id', $questionTagId)->take(count($questionTagId))->get(['id', 'name']);

                    $tagsIdIndex = [];
                    foreach ($tagsTable as $tag) {
                        $tagsIdIndex[$tag->id] = $tag;
                    }

                    $questionIdIndexTags = [];
                    foreach ($questionToTags as $questionToTag) {
                        if (! isset($questionIdIndexTags[$questionToTag->question_id])) {
                            $questionIdIndexTags[$questionToTag->question_id] = [];
                        }

                        if (isset($tagsIdIndex[$questionToTag->tag_id])) {
                            $questionIdIndexTags[$questionToTag->question_id][] = $tagsIdIndex[$questionToTag->tag_id];
                        }
                    }

                    switch ($tab) {
                        case 'trending':
                            $table->orderBy('vote_up', 'desc');
                            $table->orderBy('created_at', 'desc');
                            $table->where([['status', '0'],['created_at','<=',date('Y-m-d H:i:s',time())]]);
                            break;

                        case 'unanswered':
                            $table->where([['answered', '0'],['status','0'],['created_at','<=',date('Y-m-d H:i:s',time())]]);
                        default:
                            $table->orderBy('created_at', 'desc');
                            $table->where([['status', '0'],['created_at','<=',date('Y-m-d H:i:s',time())]]);
                    }

                    $questions = $table->get(['id', 'uid','subject', 'detail', 'stared', 'answered', 'vote_up', 'viewed', 'created_at']);
                    foreach ($questions as &$question) {
                        $question->detail = !empty($question->detail)?mb_strlen(strip_tags($question->detail), 'utf-8') > 10 ? mb_substr(strip_tags($question->detail), 0, 100, 'utf-8').'...' : strip_tags($question->detail):'';
                        $question->tags = isset($questionIdIndexTags[$question->id]) ? $questionIdIndexTags[$question->id] : [];
                        $question->created_at = Computing::timejudgment($question->created_at);

                        $askuser = DB::select('SELECT id, display_name,avatar FROM users WHERE id = '.$question->uid.' LIMIT 1');

                        $question->askuser = $askuser;

                        $newAnswer = DB::select('SELECT uid,detail FROM answers WHERE question_id = '.$question->id.' ORDER BY vote_up DESC,created_at DESC LIMIT 1');
                        if (! empty($newAnswer)) {
                            $question->detail=mb_strlen(strip_tags($newAnswer[0]->detail), 'utf-8') > 100 ? mb_substr(strip_tags($newAnswer[0]->detail), 0, 100, 'utf-8').'...' : strip_tags($newAnswer[0]->detail);

                            $newAnswer = DB::select('SELECT id, display_name,avatar FROM users WHERE id = '.$newAnswer[0]->uid.' LIMIT 1');

                            $question->newAnswer = $newAnswer[0];
                        }
                    }
                }
                $this->biz['questions'] = empty($questions)?[]:$questions;
            }else{
                $this->accepted = false;
                $this->status = 404;
                $this->description = '暂无数据';
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
        $wrapper  = [];

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
        $rules = [
            'q' => 'required',
            'page' => 'integer',
            'tab' => 'in:newest,trending,unanswered'
        ];
        $validator = Validator::make($this->input, $rules);

        if ($validator->fails()) {
            $this->accepted = false;
        }
    }

}
