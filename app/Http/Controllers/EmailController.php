<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class EmailController extends Controller
{
    /**
     * 邮箱绑定页面
     * @param Request $request
     * @return $this
     */
    public function token(Request $request){
        $MailBindingRepository = new MailBindingRepository(['token' => $request->token]);
        $MailBindingRepository->contract();
        return view('welcome')->with('data',$MailBindingRepository->wrap());
    }

}
