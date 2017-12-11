<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Repositories\Admin\AnsweredRepository;
use App\Repositories\Admin\QuestionsRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class QuestionsController extends Controller
{

    /**
     * 获取问题列表
     * @return json
     */
    public function getList(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $QuestionsRepository = new QuestionsRepository($input);
        $QuestionsRepository ->dofunction = 'getList';
        $QuestionsRepository->contract();

        if (! $QuestionsRepository->passes()) {
            return response()->json($QuestionsRepository->wrap(), $QuestionsRepository->status);
        }
        return response($QuestionsRepository->data);
    }

    /**
     * 删除问题
     * @return json
     */
    public function del(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $QuestionsRepository = new QuestionsRepository($input);
        $QuestionsRepository ->dofunction = 'del';
        $QuestionsRepository->contract();

        if (! $QuestionsRepository->passes()) {
            return response()->json($QuestionsRepository->wrap(), $QuestionsRepository->status);
        }
        return response('');
    }

    /**
     * 编辑问题
     * @return json
     */
    public function edit(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $QuestionsRepository = new QuestionsRepository($input);
        $QuestionsRepository ->dofunction = 'edit';
        $QuestionsRepository->contract();

        if (! $QuestionsRepository->passes()) {
            return response()->json($QuestionsRepository->wrap(), $QuestionsRepository->status);
        }
        return response('');
    }

    /**
     * 推介热门
     * @return json
     */
    public function hot(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $QuestionsRepository = new QuestionsRepository($input);
        $QuestionsRepository ->dofunction = 'hot';
        $QuestionsRepository->contract();

        if (! $QuestionsRepository->passes()) {
            return response()->json($QuestionsRepository->wrap(), $QuestionsRepository->status);
        }
        return response('');
    }

    public function getAnsweredList(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $AnsweredRepository = new AnsweredRepository($input);
        $AnsweredRepository ->dofunction = 'getList';
        $AnsweredRepository->contract();

        if (! $AnsweredRepository->passes()) {
            return response()->json($AnsweredRepository->wrap(), $AnsweredRepository->status);
        }
        return response($AnsweredRepository->data);
    }

    /**
     * 编辑问题
     * @return json
     */
    public function answeredEdit(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $AnsweredRepository = new AnsweredRepository($input);
        $AnsweredRepository ->dofunction = 'edit';
        $AnsweredRepository->contract();

        if (! $AnsweredRepository->passes()) {
            return response()->json($AnsweredRepository->wrap(), $AnsweredRepository->status);
        }
        return response('');
    }

    /**
     * 编辑问题
     * @return json
     */
    public function answeredDel(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $AnsweredRepository = new AnsweredRepository($input);
        $AnsweredRepository ->dofunction = 'del';
        $AnsweredRepository->contract();

        if (! $AnsweredRepository->passes()) {
            return response()->json($AnsweredRepository->wrap(), $AnsweredRepository->status);
        }
        return response('');
    }
}
