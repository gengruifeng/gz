<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Repositories\Admin\AccountRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
class AccountController extends Controller
{
    /**
     * 获取用户列表
     * @return json
     */
    public function userList(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $AccountRepository = new AccountRepository($input);
        $AccountRepository ->dofunction = 'getList';
        $AccountRepository->contract();

        if (! $AccountRepository->passes()) {
            return response()->json($AccountRepository->wrap(), $AccountRepository->status);
        }
        return response($AccountRepository->data);
    }

    /**
     * 封禁用户
     * @return json
     */
    public function fengjinUser(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $AccountRepository = new AccountRepository($input);
        $AccountRepository ->dofunction = 'fengjinUser';
        $AccountRepository->contract();

        if (! $AccountRepository->passes()) {
            return response()->json($AccountRepository->wrap(), $AccountRepository->status);
        }
        return response('');
    }

    /**
     * 提交用户
     */
    public function subedit(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $AccountRepository = new AccountRepository($input);
        $AccountRepository ->dofunction = 'subedit';
        $AccountRepository->contract();

        if (! $AccountRepository->passes()) {
            return response()->json($AccountRepository->wrap(), $AccountRepository->status);
        }
        return response('');
    }
}
