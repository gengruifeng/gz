<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;

use App\Utils\Upload;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\ArticlesComposeRepository;
use App\Repositories\ArticlesReviseRepository;
use App\Repositories\ArticlesStarRepository;
use App\Repositories\ArticlesVoteUpRepository;

class ArticlesController extends Controller
{
    /**
     * Compose Article
     *
     * @param Request $request
     *
     * @return View
     */
    public function compose(Request $request)
    {
        $input = [
            'subject' => trim($request->input('subject')),
            'detail' => trim($request->input('detail')),
            'tags' => trim($request->input('tags')),
            'uid' => $request->security()->get('uid'),
        ];
        $repo = new ArticlesComposeRepository($input);
        $repo->contract();

        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }

        return response()->json($repo->biz);
    }

    /**
     * Revise Article
     *
     * @param Request $request
     *
     * @return Reponse
     */
    public function revise(Request $request, $id)
    {
        $input = [
            'subject' => trim($request->input('subject')),
            'detail' => trim($request->input('detail')),
            'tags' => trim($request->input('tags')),
            'id' => $id,
            'uid' => $request->security()->get('uid'),
        ];

        $repo = new ArticlesReviseRepository($input);
        $repo->contract();

        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }

        return response()->json($repo->biz);
    }

    /**
     * Star Article
     *
     * @param Request $request
     *
     * @return Reponse
     */
    public function star(Request $request, $id)
    {
        $input = [];
        $input['id'] = $id;
        $input['uid'] = $request->security()->get('uid');

        $repo = new ArticlesStarRepository($input);
        $repo->contract();

        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }

        return response('');
    }

    /**
     * Vote Up Article
     *
     * @param Request $request
     *
     * @return Reponse
     */
    public function voteUp(Request $request, $id)
    {
        $input = [];
        $input['id'] = $id;
        $input['uid'] = $request->security()->get('uid');

        $repo = new ArticlesVoteUpRepository($input);
        $repo->contract();

        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }

        return response('');
    }
    /**
     *
     * 上传图片
     * @param Request $request
     *
     * @return Response
     */
    public function articleimg(Request $request)
    {
        $avatar = Upload::img('assets');
        if($avatar){
            $imgges = [
                'success' => true,
                'msg' => '上传成功',
                'file_path' => $avatar,
            ];
        }else{
            $imgges = [
                'success' => false,
                'msg' => '上传失败，请选择图片文件进行上传！',
                'file_path' => $avatar,
            ];
        }
        return response()->json($imgges);
    }
}
