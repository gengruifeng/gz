<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PasscodeController extends Controller
{
    /**
     * 通过邮箱修改密码页面
     * @param Request $request
     * @return $this
     */
    public function token(Request $request){
        return view('welcome')->with('token',$request->token);
    }
}
