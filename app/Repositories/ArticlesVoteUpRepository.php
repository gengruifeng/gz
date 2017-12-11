<?php

namespace App\Repositories;

use DB;
use Log;
use Validator;

use App\Entity\Article;
use App\Entity\ArticleVote;

use App\Utils\HttpStatus;
use App\Utils\Notification;

class ArticlesVoteUpRepository extends Repository implements RepositoryInterface
{
    const VOTE_UP = 1;

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
            $article = Article::find($this->input['id'], ['id', 'uid', 'standard', 'vote_up', 'created_at', 'updated_at']);

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
                $this->description = '该文章不能被赞';
            }
        }

        if ($this->passes()) {
            if ((int) $this->input['uid'] === $article->uid) {
                $this->accepted = false;
                $this->status = 400;
                $this->description = '不能给自己的文章点赞';
            }
        }

        if ($this->passes()) {
            $existingVote = ArticleVote::where('uid', $this->input['uid'])
                ->where('article_id', $this->input['id'])
                ->where('up_down', self::VOTE_UP)
                ->first(['id']);

            if (null !== $existingVote) {
                $this->accepted = false;
                $this->status = 400;
                $this->description = '已赞过';
            }
        }

        if ($this->passes()) {
            $newVote = new ArticleVote();

            $newVote->article_id = $this->input['id'];
            $newVote->uid = $this->input['uid'];
            $newVote->up_down = self::VOTE_UP;

            if (! $newVote->save()) {
                Log::error("文章点赞失败,用户ID为 ".$this->input['uid']."，文章ID为 ".$this->input['id']."");

                $this->accepted = false;
                $this->status = 500;
                $this->description = '发生一个内部错误, 点赞未成功';
            }
        }

        if ($this->passes()) {
            Notification::sendNotify($this->input['uid'], $article->uid, 2, 3, $article->id);

            $article->vote_up += 1;

            //更新回答表里的赞
            DB::table('user_analysis')->where('uid',$article->uid)->increment('reputation', 1);

            if (! $article->save()) {
                Log::error("修改文章点赞数量失败,用户ID为 ".$this->input['uid']."，文章ID为 ".$this->input['id']."");

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
