<?php

namespace App\Repositories;

use DB;
use Log;
use Validator;

use App\Entity\Article;
use App\Entity\ArticleTag;
use App\Entity\Tag;

use App\Utils\HttpStatus;
use App\Utils\XSS;

class ArticlesComposeRepository extends Repository implements RepositoryInterface
{
    const MAX_COUNT = 50;

    const PATTERN_SUBJECT = '#^[0-9a-zA-Z\x{4e00}-\x{9fa5}\x{ff0c}\x{3002}\x{ff1f}\s\+-,\.\?/]+$#u';
    const PATTERN_TAG = '#^(?P<tag>[0-9a-zA-Z\x{4e00}-\x{9fa5}\#\+-]+)(;(?&tag))*$#u';

    /**
     * 书写文章
     *
     * {@inheritdoc}
     */
    public function contract()
    {
        DB::beginTransaction();

        $this->validate();

        if ($this->passes()) {
            if (self::MAX_COUNT < mb_strlen($this->input['subject'])) {
                $this->accepted = false;
                $this->status = 400;
                $this->description = '文章标题不得超过50个字符';
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

        // Store the article content
        if($this->passes()){
            $article = new Article();
            $article->uid = $this->input['uid'];
            $article->subject = preg_replace('#\s+#', ' ', $this->input['subject']);
            $article->detail = $this->input['detail'];

            if (! $article->save()) {
                DB::rollBack();
                Log::error("添加文章失败,用户ID为 ".$this->input['uid']."");

                $this->accepted = false;
                $this->status = 500;
                $this->description = '发生一个内部错误, 文章添加失败';
            }
        }

        // Tag the article
        if ($this->passes()) {
            $tagIds = Tag::getTagsId($tagNames, $this->input['uid']);

            if(!$tagIds){
                Log::error("添加文章标签失败,用户ID为 ".$this->input['uid']."");

                $this->accepted = false;
                $this->status = 500;
                $this->description = '发生一个内部错误, 标签未添加';
                DB::rollBack();
            }else{
                DB::table('tags')->whereIn('id', $tagIds)->increment('tagged_articles', 1);

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
                    Log::error("添加文章标签关系失败,用户ID为 ".$this->input['uid']."");

                    $this->accepted = false;
                    $this->status = 500;
                    $this->description = '发生一个内部错误, 标签未添加';
                    DB::rollBack();
                }
            }

            
        }

        if ($this->passes()) {
            DB::commit();
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
