<?php

namespace App\Http\Controllers\Ajax;

use App\Repositories\ResumeManageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

use App\Http\Requests;
use App\Http\Controllers\Controller;



use App\Repositories\ResumeRepository;
use Illuminate\Support\Facades\Session;
use DB;

class ResumeManageController extends Controller
{
    /**
     * 保存简历
     *
     * @param void
     *
     * @return Response
     */
    public function resumeSave(Request $request)
    {
        $input = $request->all();
        $input['uid'] = $request->security()->get('uid');
        $repository = new ResumeManageRepository($input);
        $repository->dofunction = 'resumeSave';
        $repository->contract();
        if (! $repository->passes()) {
            return response()->json($repository->wrap(), $repository->status);
        }
        return response()->json();
    }

    /**
     * 删除简历
     *
     * @param void
     *
     * @return Response
     */
    public function resumeDelete(Request $request)
    {
        $input = $request->all();
        $input['uid'] = $request->security()->get('uid');
        $repository = new ResumeManageRepository($input);
        $repository->dofunction = 'resumeDelete';
        $repository->contract();
        if (! $repository->passes()) {
            return response()->json($repository->wrap(), $repository->status);
        }
        return response()->json();
    }

    /**
     * 修改简历标题
     *
     * @param void
     *
     * @return Response
     */
    public function resumeUpdateTitle(Request $request)
    {
        $input = $request->all();
        $input['uid'] = $request->security()->get('uid');
        $repository = new ResumeManageRepository($input);
        $repository->dofunction = 'resumeUpdateTitle';
        $repository->contract();
        if (! $repository->passes()) {
            return response()->json($repository->wrap(), $repository->status);
        }
        return response()->json();
    }

    /**
     * 下载简历
     *
     * @param void
     *
     * @return Response
     */
    public function resumeDownload(Request $request)
    {
        $request->security()->get('uid');
        return response("");
    }

}
