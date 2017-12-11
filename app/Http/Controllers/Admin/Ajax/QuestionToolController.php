<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Repositories\Admin\QuestionsToolRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Log;

class QuestionToolController extends Controller
{

    /**
     * 获取问题列表
     * @return json
     */
    public function getList(){
        $QuestionsToolRepository = new QuestionsToolRepository(Input::all());
        $QuestionsToolRepository ->dofunction = 'getList';
        $QuestionsToolRepository->contract();

        if (! $QuestionsToolRepository->passes()) {
            return response()->json($QuestionsToolRepository->wrap(), $QuestionsToolRepository->status);
        }
        return response($QuestionsToolRepository->data);
    }

    /**
     * 添加问题
     * @return json
     */
    public function add(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $QuestionsToolRepository = new QuestionsToolRepository($input);
        $QuestionsToolRepository ->dofunction = 'add';
        $QuestionsToolRepository->contract();

        if (! $QuestionsToolRepository->passes()) {
            return response()->json($QuestionsToolRepository->wrap(), $QuestionsToolRepository->status);
        }
        return response('');
    }

    /**
     * 添加问题
     * @return json
     */
    public function getone(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $QuestionsToolRepository = new QuestionsToolRepository($input);
        $QuestionsToolRepository ->dofunction = 'getone';
        $QuestionsToolRepository->contract();

        if (! $QuestionsToolRepository->passes()) {
            return response()->json($QuestionsToolRepository->wrap(), $QuestionsToolRepository->status);
        }
        return response($QuestionsToolRepository->data);
    }

    /**
     * 编辑问题
     * @return json
     */
    public function edit(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $QuestionsToolRepository = new QuestionsToolRepository($input);
        $QuestionsToolRepository ->dofunction = 'edit';
        $QuestionsToolRepository->contract();

        if (! $QuestionsToolRepository->passes()) {
            return response()->json($QuestionsToolRepository->wrap(), $QuestionsToolRepository->status);
        }
        return response('');
    }

    /**
     * 删除问题
     * @return json
     */
    public function del(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $QuestionsToolRepository = new QuestionsToolRepository($input);
        $QuestionsToolRepository ->dofunction = 'del';
        $QuestionsToolRepository->contract();

        if (! $QuestionsToolRepository->passes()) {
            return response()->json($QuestionsToolRepository->wrap(), $QuestionsToolRepository->status);
        }
        return response('');
    }

    /**
     * 获取用户列表
     * @return json
     */
    public function getUserList(){
        $QuestionsToolRepository = new QuestionsToolRepository(Input::all());
        $QuestionsToolRepository ->dofunction = 'getUserList';
        $QuestionsToolRepository->contract();

        if (! $QuestionsToolRepository->passes()) {
            return response()->json($QuestionsToolRepository->wrap(), $QuestionsToolRepository->status);
        }
        return response($QuestionsToolRepository->data);
    }

    /**
     * 移除用户
     * @return json
     */
    public function userDel(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $QuestionsToolRepository = new QuestionsToolRepository($input);
        $QuestionsToolRepository ->dofunction = 'userDel';
        $QuestionsToolRepository->contract();

        if (! $QuestionsToolRepository->passes()) {
            return response()->json($QuestionsToolRepository->wrap(), $QuestionsToolRepository->status);
        }
        return response('');
    }

    /**
     * 添加用户
     * @return json
     */
    public function userAdd(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $QuestionsToolRepository = new QuestionsToolRepository($input);
        $QuestionsToolRepository ->dofunction = 'userAdd';
        $QuestionsToolRepository->contract();

        if (! $QuestionsToolRepository->passes()) {
            return response()->json($QuestionsToolRepository->wrap(), $QuestionsToolRepository->status);
        }
        return response('');
    }
}
