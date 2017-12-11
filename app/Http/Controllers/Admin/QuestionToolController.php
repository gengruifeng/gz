<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Admin\QuestionsRepository;
use App\Repositories\Admin\QuestionsToolRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class QuestionToolController extends Controller
{
    //

    public function index(){
        $QuestionsRepository = new QuestionsRepository();
        $tagAll = DB::table('tags')->orderBy('created_at','desc')->get();

        return view('admin.questionstoollist')->with('tagAll',$tagAll);
    }

    public function release(Request $request,$token){
        //woyingzhichanggongzuo md5
       if(!empty($token) && $token == 'b993ab04c2b6cdd3a5c6a95d50c0fd05'){
           $QuestionsToolRepository = new QuestionsToolRepository();
           $QuestionsToolRepository ->dofunction = 'release';
           $QuestionsToolRepository->contract();
           return response('');
       }elseif(!empty($token) && $token == 'b993ab04c2b6cdd3a5c6a95d50c0fd05guanzu'){
           $QuestionsToolRepository = new QuestionsToolRepository();
           $QuestionsToolRepository ->dofunction = 'updateStared';
           $QuestionsToolRepository->contract();
       }
        return response('');
    }
}
