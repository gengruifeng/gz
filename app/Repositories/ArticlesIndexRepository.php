<?php

namespace App\Repositories;

use DB;
use Log;
use Validator;

use App\Utils\HttpStatus;
use App\Utils\Pagination;

class ArticlesIndexRepository extends Repository implements RepositoryInterface
{
    const STANDARD_APPROVED = 1;

    /**
     * 文章列表
     *
     * {@inheritdoc}
     */
    public function contract()
    {
        $this->validate();
        $this->biz=[
            'pageinfo' => [],
            'articles' => [],
        ];
        if($this->passes()){
            $articles = DB::select(
                'SELECT o.id, uid, subject, thumbnails, vote_up, updated_at FROM (SELECT articles.id,a.created_at FROM articles join article_history as a on articles.id = a.article_id WHERE articles.standard = :standard and a.type = 1   ORDER BY a.created_at DESC LIMIT :offset, 10) AS i JOIN articles AS o ON o.id = i.id',
                [
                    'standard' => self::STANDARD_APPROVED,
                    'offset' => (isset($this->input['page']) && 1 < (int) $this->input['page']? (int) $this->input['page'] - 1 : 0) * 10
                ]
            );
            //查询分页信息
            $page = empty($this->input['page'])?1:$this->input['page'];
            $count = DB::table('articles')
                ->where('standard','=',1)
                ->count('id');
            $pageinfo=[
                'total' => $count,
                'page' => $page,
                'page_total' => Pagination::total($count),
                'pagination' => Pagination::generate($page, $count),
            ];
            //判断传递的参数
            if($pageinfo['page_total'] < intval($this->input['page']) ){
                $this->status = 404;
                $this->description = "输入的页码有误!";
                $this->accepted = false;
            }
        }
            if($this->passes()){
                if (! empty($articles)) {
                    $authorId = $articleId = [];
                    foreach ($articles as $value) {
                        $authorId[] = $value->uid;
                        $articleId[] = $value->id;
                    }
                    $authors = DB::select('SELECT id, display_name FROM users WHERE id IN('.implode(',', $authorId).')');

                    $authorIdIndex = [];
                    if (! empty($authors)) {
                        foreach ($authors as $author) {
                            $author->display_name = mb_strlen($author->display_name, 'utf-8') > 10 ? mb_substr($author->display_name, 0, 10, 'utf-8').'...' : $author->display_name;
                            $authorIdIndex[$author->id] = $author;
                        }
                    }
                    $articleTagAsso = DB::select('SELECT article_id, tag_id FROM article_tags WHERE article_id  IN('.implode(',', $articleId).')');

                    $articlehistory = DB::select('SELECT article_id, created_at FROM article_history WHERE article_id  IN('.implode(',', $articleId).') and type = 1 order by created_at desc');

                    foreach ($articlehistory as $value) {
                        $articleindex[$value->article_id] = $value;
                    }

                    foreach ($articleTagAsso as $value) {
                        $tagId[] = $value->tag_id;
                    }

                    $tagIdIndex = [];
                    if (! empty($tagId)) {
                        $tags = DB::select('SELECT id, name FROM tags WHERE id IN('.implode(',', $tagId).')');

                        foreach ($tags as $value) {
                            $tagIdIndex[$value->id] = $value;
                        }
                    }

                    $articleTags = [];
                    foreach ($articleTagAsso as $value) {
                        if (! isset($tagIdIndex[$value->tag_id])) {
                            continue;
                        }

                        if (! isset($articleTags[$value->article_id])) {
                            $articleTags[$value->article_id] = [];
                        }

                        $articleTags[$value->article_id][] = $tagIdIndex[$value->tag_id];
                    }
                    foreach ($articles as &$article) {
                        if (isset($authorIdIndex[$article->uid])) {
                            $article->author = $authorIdIndex[$article->uid];
                        }
                        if (isset($articleindex[$article->id])) {
                            $article->created_at = $articleindex[$article->id]->created_at;
                        }

                        $article->tags = isset($articleTags[$article->id]) ? $articleTags[$article->id] : [];
                    }
                    //sort($articles);
                    $this->biz=[
                        'pageinfo' => $pageinfo,
                        'articles' => $articles,
                    ];
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
        $rules = [
            'page' => 'integer',
        ];
        $messages = [
            'page.integer' => '参数错误',
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 404;
            $this->description = '分页参数错误';
            $this->accepted = false;

            $messages = $validator->errors();

            if ($messages->has('page')) {
                $this->errors->add('page', $messages->first('page'));
            }
        }
    }


    /**
     * my articles page
     *
     * @param void
     *
     * @return void
     */
    public function articlePage()
    {
        $page = empty($this->input['page'])?1:$this->input['page'];
        if(intval($page)<1){
            $page = 1;
        }
        $articles = DB::table("articles")
            ->select('id','subject','standard','updated_at')
            ->where('uid',$this->input['uid'])
            ->orderby('standard')
            ->orderby('updated_at','desc')
            ->take(10)
            ->skip(($page-1)*10)
            ->get();
        if(empty($articles)){
            $this->status = 404;
            $this->description = "没有数据了！";
            $this->accepted = false;
        }
        return $articles;
    }



}
