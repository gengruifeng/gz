<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Admin\QuestionsRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class QuestionsController extends Controller
{
    //
    
    public function asklist()
    {

        return view('admin.questionslist');
    }

    public function edit(Request $request){
        $id = $request->id;
        $data = DB::table('questions')->select('id','subject','detail')->where('id',$id)->first();
        $QuestionsRepository = new QuestionsRepository();

        $tag =$QuestionsRepository->getQuestionsTags($id);
        $tagAll = DB::table('tags')->orderBy('created_at','desc')->get();

        if(!$data){
            return  Redirect::to('admin/questions/list');
        }
        return view('admin.questionsedit')->with('data',$data)->with('tag',$tag)->with('tagAll',$tagAll);
    }

    public function answered(Request $request,$questionid){
        $id = $questionid;
        $data = DB::table('questions')->select('id','subject','detail')->where('id',$id)->first();
        if(!$data){
            return  Redirect::to('admin/questions/list');
        }
        return view('admin.answeredslist')->with('questionid',$questionid)->with('subject',$data->subject);
    }
    
}
