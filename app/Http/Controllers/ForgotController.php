<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ForgotController extends Controller
{
    //

    /**
     * 密码找回-邮箱页面
     */
    public function email(){
        return view('users.reBackPwdByEmail');
    }

    /**
     * 密码找回-邮箱页面
     */
    public function mobile(){

        return view('users.reBackPwdByTel');
    }

    /**
     * 密码找回-手机页面
     */
    public function mobilefill(Request $request){
        return view('users.setNewPwd')->with('mobile',$request->mobile);
    }

    /**
     * 密码找回-邮箱设置密码页面
     */
    public function emailSetPass(Request $request){
        return view('users.setNewPwdEmail')->with('token',$request->token);
    }


}
