<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

use App\Repositories\PersonalRespository;

class PersonalController extends Controller
{
    /**
     * 个人中心 加关注
     */
    public function addAttention(Request $request)
    {
        $uid = $request->security()->get('uid');
        $input = $request->all();
        $respository = new PersonalRespository($input);
        $respository->addAttention($uid,$input['fid']);
        if(!$respository->passes()){
            return response()->json($respository->wrap(), $respository->status);
        }
        return response('');
    }

    /**
     * 个人中心 取消关注
     */
    public function delAttention(Request $request)
    {
        $uid = $request->security()->get('uid');
        $input = $request->all();
        $respository = new PersonalRespository($input);
        $respository->delAttention($uid,$input['fid']);
        if(!$respository->passes()){
            return response()->json($respository->wrap(), $respository->status);
        }
        return response('');
    }

    /**
     * 向某人提问
     */
    public function askQuestion(Request $request)
    {
        $uid = $request->security()->get('uid');
        $input = $request->all();
        $respository = new PersonalRespository($input);
        $respository->askQuestion($uid);
        if(!$respository->passes()){
            return response()->json($respository->wrap(), $respository->status);
        }
        return response('');
    }

}
