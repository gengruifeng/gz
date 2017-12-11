<?php

namespace App\Repositories;

use DB;
use Log;
use Validator;

use App\Entity\Article;
use App\Entity\ArticleStar;

use App\Utils\HttpStatus;
use App\Utils\Notification;

class ArticlesStarRepository extends Repository implements RepositoryInterface
{
    /**
     * 书写文章
     *
     * {@inheritdoc}
     */
    public function contract()
    {

        if (null === $this->input['uid']) {
            $this->accepted = false;
            $this->status = 403;
            $this->description = '请登录';
        }

        if ($this->passes()) {
            $article = Article::find($this->input['id'], ['id', 'uid', 'standard', 'stared', 'created_at', 'updated_at']);

            if (null === $article) {
                $this->accepted = false;
                $this->status = 404;
                $this->description = '该文章已不存在';
            }
        }

        if ($this->passes()) {
            if (1 !== $article->standard) {
                $this->accepted = false;
                $this->status = 400;
                $this->description = '该文章不能被收藏';
            }
        }

        if ($this->passes()) {
            if ((int) $this->input['uid'] === $article->uid) {
                $this->accepted = false;
                $this->status = 400;
                $this->description = '不能收藏自己的文章';
            }
        }

        if ($this->passes()) {
            $existingVote = ArticleStar::where('uid', $this->input['uid'])
                ->where('article_id', $this->input['id'])
                ->first(['id']);

            if (null !== $existingVote) {
                $this->accepted = false;
                $this->status = 400;
                $this->description = '已收藏';
            }
        }

        if ($this->passes()) {
            $newStar = new ArticleStar();

            $newStar->article_id = $this->input['id'];
            $newStar->uid = $this->input['uid'];

            if (! $newStar->save()) {
                Log::error("收藏文章失败,用户ID为 ".$this->input['uid']."，文章ID为 ".$this->input['id']."");

                $this->accepted = false;
                $this->status = 500;
                $this->description = '发生一个内部错误, 收藏未成功';
            }
        }

        if ($this->passes()) {
            Notification::sendNotify($this->input['uid'], $article->uid, 2, 2, $article->id);

            $article->stared += 1;

            if (! $article->save()) {
                Log::error("修改文章收藏数量失败,用户ID为 ".$this->input['uid']."，文章ID为 ".$this->input['id']."");

                $this->accepted = false;
                $this->status = 500;
                $this->description = '发生一个内部错误';
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
}
