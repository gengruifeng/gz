<?php

namespace App\Repositories;

use DB;
use Log;
use Validator;

use App\Entity\Article;
use App\Entity\ArticleComment;
use App\Entity\User;

use App\Utils\Computing;
use App\Utils\HttpStatus;
use App\Utils\Notification;

class ArticlesCommentsComposeRepository extends Repository implements RepositoryInterface
{
    /**
     * 文章评论
     *
     * {@inheritdoc}
     */
    public function contract()
    {
        $this->validate();

        $article = Article::find($this->input['article_id'], ['id', 'uid', 'standard']);

        if (null === $article) {
            $this->accepted = false;
            $this->status = 404;
            $this->description = '该文章不存在';
        }

        if ($this->passes()) {
            if (1 !== $article->standard) {
                $this->accepted = false;
                $this->status = 400;
                $this->description = '该文章不能被评论';
            }
        }

        if ($this->passes()) {
            $articleComment = new ArticleComment;

            $articleComment->uid = $this->input['uid'];
            $articleComment->article_id = $this->input['article_id'];
            $articleComment->content = $this->input['content'];

            if (! $articleComment->save()) {
                Log::error("添加文章评论失败,用户ID为 ".$this->input['uid']."，文章ID为 ".$this->input['article_id']."");

                $this->accepted = false;
                $this->status = 500;
                $this->description = '发生一个内部错误';
            }
        }

        if ($this->passes()) {
            if ((int) $this->input['uid'] !== $article->uid) {
                Notification::sendNotify($this->input['uid'], $article->uid, 2, 1, $article->id);
            }

            $author = User::find($this->input['uid'], ['id', 'display_name', 'avatar', 'occupation']);

            if (1 === $author->occupation) {
                $education = DB::select('SELECT uid, school, department FROM user_educations WHERE uid = '.$this->input['uid'].' LIMIT 1');

                $author->education = empty($education) ? null : $education[0];
            } elseif (2 === $author->occupation) {
                $work = DB::select('SELECT uid, company, position FROM user_works WHERE uid = '.$this->input['uid'].' LIMIT 1');

                $author->work = empty($work) ? null : $work[0];
            }

            $this->biz = [
                'id' => $articleComment->id,
                'uid' => $this->input['uid'],
                'article_id' => $articleComment->article_id,
                'content' => $articleComment->content,
                'updated_at' =>Computing::timejudgment($articleComment->updated_at),
                'author' => $author
            ];
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

            if (! $this->errors->isEmpty()) {
                $errors = [];
                foreach ($this->errors->getErrors() as $key => $value) {
                    $errors[] = [
                        'input' => $key,
                        'message' => $value
                    ];
                }

                $wrapper['errors'] = $errors;
            }
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
            'content' => 'required',
        ];
        $messages = [
            'content.required' => '请填写评论内容',
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '请输入信息';
            $this->accepted = false;

            $messages = $validator->errors();

            if ($messages->has('content')) {
                $this->errors->add('content', $messages->first('content'));
            }
        }
    }
}

