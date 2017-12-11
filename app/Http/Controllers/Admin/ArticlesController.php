<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Article;
use App\Entity\ArticleComment;
use App\Entity\ArticleTag;
use App\Entity\Tag;
use App\Entity\User;
use App\Repositories\Admin\ArticlesRepository;
use App\Utils\Computing;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ArticlesController extends Controller
{
    //
    
    public function artlist(){

        return view('admin.artcleslist');
    }

    public function edit(Request $request){
        $id = $request->id;
        $data = DB::table('articles')->where('id',$id)->first();
        $ArticlesRepository = new ArticlesRepository();

        $tag =$ArticlesRepository->getArticlesTags($id);

        $tagAll = DB::table('tags')->orderBy('created_at','desc')->get();

        if(!$data){
            return  Redirect::to('admin/questions/list');
        }
        return view('admin.articlesedit')->with('data',$data)->with('tag',$tag)->with('tagAll',$tagAll);
    }


    /**
     * Show Specific Article
     *
     * @param Request $request
     *
     * @return View
     */
    public function show(Request $request, $id)
    {
        $article = Article::where('id', $id)->first(['id', 'uid', 'subject', 'detail', 'standard', 'viewed', 'stared', 'shared', 'vote_up', 'updated_at']);

        if (null === $article) {
            throw new NotFoundHttpException();
        }

        $commentCount = ArticleComment::where('article_id', $id)->count(['id']);

        $author = User::find($article->uid, ['id', 'display_name']);

        $articleTags = ArticleTag::where('article_id', $id)->get(['tag_id']);
        $tagId = [];
        $tagCount = 0;
        foreach ($articleTags as $articleTag) {
            $tagCount++;
            $tagId[] = $articleTag->tag_id;
        }

        $tags = Tag::whereIn('id', $tagId)->get(['id', 'name'])->take($tagCount);
        $tagsToString = '';
        foreach ($tags as $tag) {
            $tagsToString .= ','.$tag->name;
        }
        return view('articles.show',
            [
                'article' => $article,
                'author' => null === $author ? '佚名' : $author->display_name,
                'updated_at' => Computing::timejudgment($article->updated_at),
                'comment_count' => $commentCount,
                'tags' => $tags,
                'tagsToString' => trim($tagsToString, ','),
            ]
        );
    }

    /**
     * 文章评论列表页
     */
    public function comment(Request $request,$articleid){
        $id = $articleid;
        $data = DB::table('articles')->select('id','subject','detail')->where('id',$id)->first();
        if(!$data){
            return  Redirect::to('admin/questions/list');
        }
        return view('admin.articlecommentlist')->with('articleid',$articleid)->with('subject',$data->subject);
    }
}
