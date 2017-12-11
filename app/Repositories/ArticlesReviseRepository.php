<?php

namespace App\Repositories;

use DB;
use Log;
use Validator;

use App\Entity\Article;
use App\Entity\ArticleTag;
use App\Entity\Tag;

use App\Utils\HttpStatus;

class ArticlesReviseRepository extends Repository implements RepositoryInterface
{
    const PATTERN_SUBJECT = '#^[0-9a-zA-Z\x{4e00}-\x{9fa5}\x{ff0c}\x{3002}\x{ff1f}\s\+-,\.\?/]+$#u';
    const PATTERN_TAG = '#^(?P<tag>[0-9a-zA-Z\x{4e00}-\x{9fa5}]+)(;(?&tag))*$#u';

    /**
     * 书写文章
     *
     * {@inheritdoc}
     */
    public function contract()
    {
        $this->validate();

        $article = Article::find($this->input['id'], ['id', 'uid', 'subject', 'detail', 'standard', 'created_at', 'updated_at']);

        if (null === $article) {
            $this->accepted = false;
            $this->status = 404;
            $this->description = '该文章不存在';
        }

        if ($this->passes()) {
            if ((int) 1 === $article->standard) {
                $this->accepted = false;
                $this->status = 403;
                $this->description = '文章已被审核通过, 不可被修改';
            }
        }

        if ($this->passes()) {
            if ((int) $this->input['uid'] !== $article->uid) {
                $this->accepted = false;
                $this->status = 403;
                $this->description = '您不能修改该文章';
            }
        }

        if ($this->passes()) {
            $tagNames = explode(';', $this->input['tags']);

            if (5 < count($tagNames)) {
                $this->accepted = false;
                $this->status = 400;
                $this->description = '每篇文章不得超过5个标签';
            }
        }

        if ($this->passes()) {
            $article->subject = preg_replace('#\s+#', ' ', $this->input['subject']);
            $article->detail = $this->input['detail'];

            if (! $article->save()) {
                Log::error("修改文章失败,用户ID为 ".$this->input['uid']."，文章ID为 ".$this->input['id']."");

                $this->accepted = false;
                $this->status = 500;
                $this->description = '发生一个内部错误, 文章未修改';
            }
        }

        // Delete old tags and create new tags for the article
        if ($this->passes()) {
            $existingTags = ArticleTag::where('article_id', $article->id)->get(['tag_id']);

            $existingTagIds = [];
            foreach ($existingTags as $existingTag) {
                $existingTagIds[] = $existingTag->tag_id;
            }

            $tagIds = Tag::getTagsId($tagNames, $this->input['uid']);

            $decreaseTagIds = array_diff($existingTagIds, $tagIds);
            $increaseTagIds = array_diff($tagIds, $existingTagIds);

            if (false === ArticleTag::where('article_id', $article->id)->delete()) {
                Log::error("修改文章标签失败,用户ID为 ".$this->input['uid']."，文章ID为 ".$this->input['id']."");

                $this->accepted = false;
                $this->status = 500;
                $this->description = '发生一个内部错误, 标签未修改';
            }
        }

        // Add new tags for the article
        if ($this->passes()) {
            $datetime = date('Y-m-d H:i:s');

            $articleTags = [];
            foreach ($tagIds as $value) {
                $articleTags[] = [
                    'article_id' => $article->id,
                    'tag_id' => $value,
                    'created_at' => $datetime,
                    'updated_at' => $datetime
                ];
            }

            if (! DB::table('article_tags')->insert($articleTags)) {
                Log::error("新增文章新标签失败,用户ID为 ".$this->input['uid']."，文章ID为 ".$this->input['id']."");

                $this->accepted = false;
                $this->status = 500;
                $this->description = '发生一个内部错误, 标签未修改';
            };
        }

        // Decrease and increase tagged_articles for tags
        if ($this->passes()) {
            if (! empty($decreaseTagIds)) {
                DB::table('tags')->whereIn('id', $decreaseTagIds)->decrement('tagged_articles', 1);
            }

            if (! empty($increaseTagIds)) {
                DB::table('tags')->whereIn('id', $increaseTagIds)->increment('tagged_articles', 1);
            }
        }

        if ($this->passes()) {
            $this->biz = [
                'id' => $article->id,
                'subject' => $article->subject
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
            'subject' => 'required',
            'detail' => 'required',
            'tags' => 'required|regex:'.self::PATTERN_TAG,
        ];
        $messages = [
            'subject.required' => '请填写标题',
            'detail.required' => '请填写内容',
            'tags.required' => '请添加至少一个标签',
            'tags.regex' => '标签不正确'
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '请输入信息';
            $this->accepted = false;

            $messages = $validator->errors();

            if ($messages->has('subject')) {
                $this->errors->add('subject', $messages->first('subject'));
            }

            if ($messages->has('detail')) {
                $this->errors->add('detail', $messages->first('detail'));
            }

            if ($messages->has('tags')) {
                $this->errors->add('tags', $messages->first('tags'));
            }
        }
    }
}
