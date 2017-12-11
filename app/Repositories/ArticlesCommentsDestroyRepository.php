<?php

namespace App\Repositories;

use DB;
use Log;
use Validator;

use App\Entity\ArticleComment;

use App\Utils\HttpStatus;
use App\Utils\Notification;

class ArticlesCommentsDestroyRepository extends Repository implements RepositoryInterface
{
    /**
     * 文章评论
     *
     * {@inheritdoc}
     */
    public function contract()
    {
        $comment = ArticleComment::find($this->input['cid'], ['id', 'uid', 'article_id']);

        if (null === $comment) {
            $this->accepted = false;
            $this->status = 404;
            $this->description = '该评论不存在';
        }

        if ($this->passes()) {
            if ($comment->uid !== (int) $this->input['uid']) {
                $this->accepted = false;
                $this->status = 401;
                $this->description = '您不能删除该评论';
            }
        }

        if ($this->passes()) {
            if (! $comment->delete()) {
                Log::error("删除文章评论失败,用户ID为 ".$this->input['uid']."，评论ID为 ".$this->input['cid']."");

                $this->accepted = false;
                $this->status = 500;
                $this->description = '发生一个内部错误，评论未删除';
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
    }
}

