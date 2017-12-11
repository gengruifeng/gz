<?php

namespace App\Http\Controllers;

use Hash;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

use App\Entity\Article;
use App\Entity\ArticleTag;
use App\Entity\Tag;
use App\Entity\ArticleComment;
use App\Entity\User;

use App\Utils\Computing;

use App\Repositories\ArticlesIndexRepository;
use App\Utils\HttpUserAgent;

class ArticlesController extends Controller
{
    /**
     * Articles
     *
     * @param Request $request
     *
     * @return View
     */
    public function index(Request $request)
    {
        $permit = ['page'];

        $input = $request->only($permit);
        $repo = new ArticlesIndexRepository($input);
        $repo->contract();
        if (! $repo->passes()) {
            throw new UnauthorizedHttpException('no my articles');
        }
        $tags = DB::select('SELECT id, name FROM tags ORDER BY tagged_articles DESC LIMIT 10');
        return view('articles.index', [
            'articles' => $repo->biz,
            'tags' => $tags
        ]);
    }

    /**
     * Compose Article
     *
     * @param Request $request
     *
     * @return View
     */
    public function compose(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        return view('articles.compose')->with('uid',$uid);
    }

    /**
     * Revise Article
     *
     * @param Request $request
     *
     * @return View
     */
    public function revise(Request $request, $id)
    {
        $article = Article::find($id, ['id', 'uid', 'subject', 'detail']);
        if (null === $article) {
            throw new NotFoundHttpException();
        }

        $uid = $request->security()->get('uid');
        if ((int) $uid !== $article->uid) {
            throw new UnauthorizedHttpException('Basic realm="My Realm"');
        }

        $articleTags = ArticleTag::where('article_id', $article->id)->get(['tag_id']);
        $tagId = [];
        $tagCount = 0;
        foreach ($articleTags as $articleTag) {
            $tagCount++;
            $tagId[] = $articleTag->tag_id;
        }

        $tags = Tag::whereIn('id', $tagId)->get(['id', 'name'])->take($tagCount);

        $tagtostr = "";
        if($tags !== null){
            foreach($tags as $tag){
                $tagtostr .= $tag->name.",";
            }
        }
        return view('articles.revise',
            [
                'id' => $article->id,
                'subject' => $article->subject,
                'detail' => $article->detail,
                'tags' => rtrim($tagtostr,','),
            ]
        );
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
        $uid = $request->security()->get('uid');
        $article = Article::where('id', $id)->first(['id', 'uid', 'subject', 'detail', 'standard', 'viewed', 'stared', 'shared', 'vote_up', 'created_at']);

        if (null === $article) {
            throw new NotFoundHttpException();
        }

        if (1 !== $article->standard) {
            if (0 === (int) $uid) {
                throw new NotFoundHttpException();
            }

            if (0 < (int) $uid && $article->uid !== (int) $uid) {
                throw new UnauthorizedHttpException('Basic realm="My Realm"');
            }
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

        $articlehistory = DB::select('SELECT article_id, created_at FROM article_history WHERE article_id  = '.$id.' and type = 1');

        // 浏览数加1
        DB::table('articles')->where([['id','=',$id],['standard','=',1]])->increment('viewed', 1);

        $tagsToString = '';
        foreach ($tags as $tag) {
            $tagsToString .= ','.$tag->name;
        }
        // 默认赞 和收藏 状态
        if(0 < (int) $uid){
            //是否赞过该文章
            $articleVote = DB::table('article_votes')
                ->select('id','up_down')
                ->where(['uid'=>$uid,'article_id'=>$id])
                ->first();
            if($articleVote === null){
                $is_praise = 0;
            }else{
                $is_praise = $articleVote->up_down;
            }
            //是否收藏该文章
            $articleStars = DB::table('article_stars')
                ->select('id')
                ->where(['uid'=>$uid,'article_id'=>$id])
                ->first();
            if($articleStars === null){
                $is_collect = 0;
            }else{
                $is_collect = 1;
            }
        }else{
            $is_praise = 0;
            $is_collect = 0;
        }
        if(HttpUserAgent::isMobile()){
            return view('html5.articles.show',
                [
                    'article' => $article,
                    'author' => null === $author ? '佚名' : $author->display_name,
                    'updated_at' => empty($articlehistory)?Computing::timejudgment($article->created_at):Computing::timejudgment($articlehistory[0]->created_at),
                    'tags' => $tags,
                    'tagsToString' => trim($tagsToString, ','),
                ]
            );
        }else{
            return view('articles.show',
                [
                    'article' => $article,
                    'author' => null === $author ? '佚名' : $author->display_name,
                    'updated_at' => empty($articlehistory)?Computing::timejudgment($article->created_at):Computing::timejudgment($articlehistory[0]->created_at),
                    'comment_count' => $commentCount,
                    'tags' => $tags,
                    'tagsToString' => trim($tagsToString, ','),
                    'is_praise' => $is_praise,
                    'is_collect' => $is_collect,
                ]
            );
        }

    }

    /**
     * Redirect to the correct URI
     *
     * @param Request $request
     *
     * @return View
     */
    public function myArticles()
    {

        return view('articles.private');
    }

    /**
     * Redirect to the correct URI
     *
     * @param Request $request
     *
     * @return View
     */
    public function articlePage(Request $request)
    {
        $uid = $request->security()->get('uid');
        $input = $request->all();
        $input['uid'] = $uid;
        $repo = new ArticlesIndexRepository($input);
        $articles = $repo->articlePage();
        
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return view('articles._private')->with('articles',$articles);
    }

    /**
     * Articles
     *
     * @param Request $request
     *
     * @return View
     */
    public function articleslist(Request $request)
    {
        $permit = ['page'];

        $input = $request->only($permit);
        $repo = new ArticlesIndexRepository($input);
        $repo->contract();
        if (! $repo->passes()) {
            throw new UnauthorizedHttpException('no my articles');
        }
        return view('articles.list', [
            'articles' => $repo->biz,
        ]);
    }

}
