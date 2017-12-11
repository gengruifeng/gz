<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Entity\Article;
use App\Entity\ArticleComment;

use App\Repositories\ArticlesCommentsRepository;
use App\Repositories\ArticlesCommentsComposeRepository;
use App\Repositories\ArticlesCommentsDestroyRepository;

class ArticlesCommentsController extends Controller
{
    /**
     * 列出文章评论
     *
     * @param int $id Article ID
     *
     * @return View
     */
    public function index(Request $request, $id)
    {
        $article = Article::find($id, ['id']);
        if (null === $article) {
            throw new NotFoundHttpException();
        }

        $permit = ['page'];
        $input = $request->only($permit);
        $input['id'] = $id;
        $input['uid'] = $request->security()->get('uid');

        $repo = new ArticlesCommentsRepository($input);
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return view('articles._comments')->with('comments',$repo->biz);
    }

    /**
     * 撰写评论
     *
     * @param Request $request
     * @param int $id Article ID
     *
     * @return Response
     */
    public function compose(Request $request, $id)
    {
        $permit = [
            'content',
        ];
        $input = $request->only($permit);

        foreach ($input as &$value) {
            trim($value);
        }
        $input['content']=htmlspecialchars($input['content']);
        $input['article_id'] = $id;
        $input['uid'] = $request->security()->get('uid');

        $repo = new ArticlesCommentsComposeRepository($input);
        $repo->contract();

        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }

        return response()->json($repo->biz);
    }

    /**
     * 删除评论
     *
     * @param Request $Request
     * @param int $aid Article ID
     * @param int $cid Comment ID
     *
     * @return Response
     */
    public function destroy(Request $request, $aid, $cid)
    {
        $input = [
            'aid' => $aid,
            'cid' => $cid,
            'uid' => $request->security()->get('uid')
        ];

        $repo = new ArticlesCommentsDestroyRepository($input);
        $repo->contract();

        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }

        return response('');
    }
}
