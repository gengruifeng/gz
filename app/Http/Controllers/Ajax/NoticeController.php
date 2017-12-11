<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

use App\Repositories\NoticeRespository;

class NoticeController extends Controller
{
    /**
     * 私信对话页面发送私信
     */
    public function addPrivateMsg(Request $request)
    {
        $uid = $request->security()->get('uid');
        $input = $request->all();
        $respository = new NoticeRespository($input);
        $respository->addPrivateMsg($uid);
        if(!$respository->passes()){
            return response()->json($respository->wrap(), $respository->status);
        }
        return response('');
    }

    /**
     * 私信列表页面发送私信
     */
    public function addDialog(Request $request)
    {
        $uid = $request->security()->get('uid');
        $input = $request->all();
        $respository = new NoticeRespository($input);
        $respository->addDialog($uid);
        if(!$respository->passes()){
            return response()->json($respository->wrap(), $respository->status);
        }
        return response('');
    }

    /**
     * 私信列表页面删除私信
     */
    public function delDialog(Request $request)
    {
        $input = $request->all();
        $uid = $request->security()->get('uid');
        $respository = new NoticeRespository($input);
        $respository->delDialog($uid);
        if(!$respository->passes()){
            return response()->json($respository->wrap(), $respository->status);
        }
        return response('');
    }

}
