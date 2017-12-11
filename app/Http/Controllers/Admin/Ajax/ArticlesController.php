<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Repositories\Admin\ArticleCommentRepository;
use App\Repositories\Admin\ArticlesRepository;
use App\Utils\Upload;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class ArticlesController extends Controller
{
    /**
     * 获取文章列表
     * @return json
     */
    public function getList(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $ArticlesRepository = new ArticlesRepository($input);
        $ArticlesRepository ->dofunction = 'getList';
        $ArticlesRepository->contract();

        if (! $ArticlesRepository->passes()) {
            return response()->json($ArticlesRepository->wrap(), $ArticlesRepository->status);
        }
        return response($ArticlesRepository->data);
    }

    /**
     * 审核文章
     * @return json
     */
    public function check(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $ArticlesRepository = new ArticlesRepository($input);
        $ArticlesRepository ->dofunction = 'check';
        $ArticlesRepository->contract();

        if (! $ArticlesRepository->passes()) {
            return response()->json($ArticlesRepository->wrap(), $ArticlesRepository->status);
        }
        return response('');
    }

    /**
     * 编辑标题
     * @return json
     */
    public function edit(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $ArticlesRepository = new ArticlesRepository($input);
        $ArticlesRepository ->dofunction = 'edit';
        $ArticlesRepository->contract();

        if (! $ArticlesRepository->passes()) {
            return response()->json($ArticlesRepository->wrap(), $ArticlesRepository->status);
        }
        return response('');
    }


    /**
     * 上传文章缩略图
     */
    public function upload(Request $request){
        $adminid = $request->security()->get('uid');
        $imgpath = Upload::uploadArticles('assets');
        $input = Input::all();
        $input['thumbnails'] = $imgpath;
        $input['adminid'] = $adminid;
        $ArticlesRepository = new ArticlesRepository($input);
        $ArticlesRepository ->dofunction = 'upload';
        $ArticlesRepository->contract();

        if (! $ArticlesRepository->passes()) {
            return response()->json($ArticlesRepository->wrap(), $ArticlesRepository->status);
        }
        return response('');
    }

    /**
     * 评论列表
     * @param Request $request
     */
    public function getCommentList(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $ArticleCommentRepository = new ArticleCommentRepository($input);
        $ArticleCommentRepository ->dofunction = 'getList';
        $ArticleCommentRepository->contract();

        if (! $ArticleCommentRepository->passes()) {
            return response()->json($ArticleCommentRepository->wrap(), $ArticleCommentRepository->status);
        }
        return response($ArticleCommentRepository->data);
    }

    /**
     * 编辑问题
     * @return json
     */
    public function commentEdit(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $ArticleCommentRepository = new ArticleCommentRepository($input);
        $ArticleCommentRepository ->dofunction = 'edit';
        $ArticleCommentRepository->contract();

        if (! $ArticleCommentRepository->passes()) {
            return response()->json($ArticleCommentRepository->wrap(), $ArticleCommentRepository->status);
        }
        return response('');
    }

    /**
     * 编辑问题
     * @return json
     */
    public function commentDel(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $ArticleCommentRepository = new ArticleCommentRepository($input);
        $ArticleCommentRepository ->dofunction = 'del';
        $ArticleCommentRepository->contract();

        if (! $ArticleCommentRepository->passes()) {
            return response()->json($ArticleCommentRepository->wrap(), $ArticleCommentRepository->status);
        }
        return response('');
    }
}
