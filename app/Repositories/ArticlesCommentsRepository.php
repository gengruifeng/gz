<?php

namespace App\Repositories;

use DB;
use Log;
use Validator;

use App\Entity\ArticleComment;
use App\Entity\User;

use App\Utils\Computing;
use App\Utils\HttpStatus;

class ArticlesCommentsRepository extends Repository implements RepositoryInterface
{
    /**
     * 文章评论
     *
     * {@inheritdoc}
     */
    public function contract()
    {
        $comments = DB::select(
            'SELECT o.id, uid, article_id, content, updated_at FROM (SELECT id FROM article_comments WHERE article_id = :article_id LIMIT :offset, 10) AS i JOIN article_comments AS o ON o.id = i.id',
            [
                'article_id' => $this->input['id'],
                'offset' => (isset($this->input['page']) && 1 < (int) $this->input['page']? (int) $this->input['page'] - 1 : 0) * 10
            ]
        );

        if (! empty($comments)) {
            $userId = $userIdIndex = [];

            foreach ($comments as $item) {
                $userId[] = $item->uid;
            }

            $users = User::find($userId, ['id', 'display_name', 'avatar', 'occupation']);
            $userEducations = $userWorks = [];
            foreach ($users as $user) {
                if (1 === $user->occupation) {
                    $userEducations[] = $user->id;
                } elseif (2 === $user->occupation) {
                    $userWorks[] = $user->id;
                }
            }

            // Select users education
            $educationUIdIndex = [];
            if (! empty($userEducations)) {
                $educations = DB::select('SELECT uid, school, department FROM user_educations WHERE uid IN('.implode(',', $userEducations).') LIMIT :limit', ['limit' => count($userEducations)]);

                foreach ($educations as $education) {
                    $educationUIdIndex[$education->uid] = $education;
                }
            }

            // Select users work experience
            $workUIdIndex = [];
            if (! empty($userWorks)) {
                $works = DB::select('SELECT uid, company, position FROM user_works WHERE uid IN('.implode(',', $userWorks).') LIMIT :limit', ['limit' => count($userWorks)]);

                foreach ($works as $work) {
                    $workUIdIndex[$work->uid] = $work;
                }
            }

            // 1 for Student 2 for Working Staff
            foreach ($users as &$person) {
                if (1 === $person->occupation) {
                    $person->education = isset($educationUIdIndex[$person->id]) ? $educationUIdIndex[$person->id] : null;
                } elseif (2 === $person->occupation) {
                    $person->work = isset($workUIdIndex[$person->id]) ? $workUIdIndex[$person->id] : null;
                }

                $userIdIndex[$person->id] = $person;
            }

            foreach ($comments as &$item) {
                $item->author = isset($userIdIndex[$item->uid]) ? $userIdIndex[$item->uid] : '佚名';
                $item->updated_at=Computing::timejudgment($item->updated_at);
                $item->uid = $this->input['uid'];
            }
            $this->biz = $comments;
        }
        if(empty($comments)){
            $this->status = 404;
            $this->description = "加载文章评论失败";
            $this->accepted = false;
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

            return $wrapper;
        }

        return $this->biz;
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
        ];
        $messages = [
            'page.integer' => '页数无效',
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '请输入信息';
            $this->accepted = false;

            $messages = $validator->errors();

            if ($messages->has('page')) {
                $this->errors->add('page', $messages->first('page'));
            }
        }
    }
}

