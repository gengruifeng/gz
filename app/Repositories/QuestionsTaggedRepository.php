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

class QuestionsTaggedRepository extends Repository implements RepositoryInterface
{
    const PATTERN_TAG = '#^(?P<tag>[0-9a-zA-Z\x{4e00}-\x{9fa5}-]+)(;(?&tag))?$#u';

    /**
     * 标签问题列表
     *
     * {@inheritdoc}
     */
    public function contract()
    {
        $this->validate();

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
        $tags = explode(';', $this->input['tags']);
        $this->biz = [
            'raw_tags' => $this->input['tags'],
            'tab' => $tab,
            'tags' => $tags,
            'page' => $page,
            'total' => 0,
            'page_total' => Pagination::total(0),
            'pagination' => Pagination::generate($page, 0),
            'questions' => []
        ];

        // Select sphinx tags index
        if ($this->passes()) {
            $tagId = [];
            foreach ($tags as $tagName) {
                $tag = DB::table('tags')
                    ->select('id')
                    ->where('name', $tagName)
                    ->first();

                if ($tag != null) {
                    $tagId[] = $tag->id;
                }
            }
            $tagTotal = count($tagId);

            if (0 === $tagTotal || $tagTotal < count($tags)) {
                $this->accepted = false;
                $this->status = 404;
            }
        }

        // Select Questions
        if ($this->passes()) {
            $currenttime = date("Y-m-d H:i:s",time());
            if (1 < $tagTotal) {
                $questionCount = DB::select(sprintf('SELECT count(o.question_id) AS total FROM (SELECT question_id FROM question_tags WHERE tag_id = %s) AS i JOIN question_tags AS o ON o.question_id = i.question_id WHERE o.created_at <= "'.$currenttime.'" AND tag_id = %s', $tagId[0], $tagId[1]));
            } else {
                $questionCount = DB::select('SELECT count(tag_id) AS total FROM question_tags WHERE tag_id = '.$tagId[0].' and created_at <= "'.$currenttime.'"');
            }

            $total = $questionCount[0]->total;
            $this->biz['total'] = $total;
            $this->biz['page_total'] = Pagination::total($total);
            $this->biz['pagination'] = Pagination::generate($page, $total);

            if (0 < $total) {
                if($this->input['type'] === 1){
                    $questions = true;
                }
                if($this->input['type'] === 2){
                    if (1 < $tagTotal) {
                        $taggedQuestions = DB::select('SELECT o.question_id FROM (SELECT question_id FROM question_tags as qt JOIN questions AS q ON q.id = qt.question_id WHERE tag_id = :tagOne ORDER BY q.vote_up DESC LIMIT :offset, 10) AS i JOIN question_tags AS o ON o.question_id = i.question_id WHERE o.created_at <= "'.$currenttime.'"and tag_id = :tagTwo', [
                            'tagOne' => $tagId[0],
                            'tagTwo' => $tagId[1],
                            'offset' => 1 < $page ? ($page - 1) * 10 : 0
                        ]);
                    } else {
                        $offset = 1 < $page ? ($page - 1) * 10 : 0;
                        $taggedQuestions = DB::table('question_tags')
                            ->join('questions','questions.id','=','question_tags.question_id')
                            ->select('question_tags.question_id')
                            ->where('question_tags.tag_id',$tagId[0])
                            ->where("question_tags.created_at","<=",$currenttime)
                            ->orderby('questions.vote_up','desc')
                            ->orderby('questions.created_at','desc')
                            ->skip($offset)
                            ->take(10)
                            ->get();
                    }

                    $questionId = [];
                    foreach ($taggedQuestions as $taggedQuestion) {
                        $questionId[] = $taggedQuestion->question_id;
                    }

                    $table = DB::table('questions');

                    $table->whereIn('id', $questionId);

                    switch ($tab) {
                        case 'trending':
                            $table->orderBy('vote_up', 'desc');
                            $table->orderBy('created_at', 'desc');
                            $table->where([['status', '0'],['created_at','<=',$currenttime]]);
                            break;

                        case 'unanswered':
                            $table->where([['answered', '0'],['status','0'],['created_at','<=',$currenttime]]);
                        default:
                            $table->orderBy('created_at', 'desc');
                            $table->where([['status', '0'],['created_at','<=',$currenttime]]);
                    }

                    $questions = $table->take(10)->get(['id', 'uid', 'subject', 'detail', 'answered', 'viewed', 'stared', 'vote_up', 'created_at']);

                    // Supply with author and the first answer
                    if (! empty($questions)) {
                        $questionToTags = DB::select('SELECT question_id, tag_id FROM question_tags WHERE question_id IN('.implode(',', $questionId).')');

                        $questionTagId = [];
                        foreach ($questionToTags as $questionToTag) {
                            $questionTagId[] = $questionToTag->tag_id;
                        }

                        $questionTags = DB::select('SELECT id, name FROM tags WHERE id IN('.implode(',', $questionTagId).') LIMIT :limit', ['limit' => count($questionTagId)]);

                        $questionTagIdIndex = [];
                        foreach ($questionTags as $questionTag) {
                            $questionTagIdIndex[$questionTag->id] = $questionTag;
                        }

                        // Index Tags to Question
                        $questionIdIndexTag = [];
                        foreach ($questionToTags as $questionToTag) {
                            if (! isset($questionIdIndexTag[$questionToTag->question_id])) {
                                $questionIdIndexTag[$questionToTag->question_id] = [];
                            }

                            $questionIdIndexTag[$questionToTag->question_id][] = $questionTagIdIndex[$questionToTag->tag_id];
                        }

                        $uid = [];
                        foreach ($questions as $question) {
                            $uid[] = $question->uid;
                        }

                        $users = DB::select('SELECT id, avatar FROM users WHERE id IN('.implode(',', $uid).') LIMIT :limit', ['limit' => count($uid)]);

                        $userIdIndex = [];
                        foreach ($users as $user) {
                            $userIdIndex[$user->id] = $user;
                        }

                        foreach ($questions as &$question) {
                            $question->author = $userIdIndex[$question->uid];
                            $question->tags = $questionIdIndexTag[$question->id];
                            $question->created_at = Computing::timejudgment($question->created_at);
                            $newAnswer = DB::select('SELECT uid,detail FROM answers WHERE question_id = '.$question->id.' ORDER BY vote_up DESC,created_at DESC  LIMIT 1');
                            $question->detail = !empty($question->detail)?mb_strlen(strip_tags($question->detail), 'utf-8') > 100 ? mb_substr(strip_tags($question->detail), 0, 100, 'utf-8').'...' : strip_tags($question->detail):'';

                            if (! empty($newAnswer)) {
                                $question->detail=mb_strlen(strip_tags($newAnswer[0]->detail), 'utf-8') > 100 ? mb_substr(strip_tags($newAnswer[0]->detail), 0, 100, 'utf-8').'...' : strip_tags($newAnswer[0]->detail);
                                $newAnswer = DB::select('SELECT id, display_name FROM users WHERE id = '.$newAnswer[0]->uid.' LIMIT 1');

                                $question->newAnswer = $newAnswer[0];
                            }
                        }
                    }else{
                        $this->accepted = false;
                        $this->status = 404;
                        $this->description = '暂无数据';
                    }
                }
                $this->biz['questions'] = $questions;
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
        $rules = [
            'page' => 'integer',
            'tab' => 'in:newest,trending,unanswered',
            'tags' => 'required|regex:'.self::PATTERN_TAG
        ];
        $validator = Validator::make($this->input, $rules);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
        }
    }
}
