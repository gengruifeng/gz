<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use Illuminate\Support\Facades\Input;

use App\Repositories\QuestionsAskRepository;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

use DB;


class QuestionsController extends Controller
{

    /**
     * 书写问题
     *
     * @param void
     *
     * @return Response
     */
    public function ask(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        return view('questions.ask_add')->with('uid',$uid);
    }
    /**
     * 书写问题
     *
     * @param void
     *
     * @return Response
     */
    public function askindex(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid'=>$uid,
            'type'=>Input::get('type'),
            'page'=>Input::get('page'),
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'askinfo';
        $info = $repo->contract();
        //url重定向
        if (! $repo->passes()) {
            throw new NotFoundHttpException();
        }

        return view('questions.list')->with('askinfo',$info);
    }
    /**
     * 问题下拉加载
     *
     * @param void
     *
     * @return Response
     */
    public function asklist(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid'=>$uid,
            'page'=>Input::get('page'),
            'type'=>Input::get('type'),
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'asklist';
        $info = $repo->contract();
        //url重定向
        if (! $repo->passes()) {
            throw new NotFoundHttpException();
        }

        return view('questions._list')->with('askinfo',$info);
    }
    /**
     * 问题详情
     *
     * @param void
     *
     * @return Response
     */
    public function detail(Request $request, $askid)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'askid'=>$askid,
            'uid'=>$uid,
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'detail';
        $info = $repo->contract();
        if ($repo->status ==404) {
            throw new NotFoundHttpException();
        }
        //url重定向
        if (! $repo->passes()) {
            return redirect()->route('questions',$repo->info);
        }
        $tagsToString = '';
        foreach ($info['tags'] as $tag) {
            $tagsToString .= ','.$tag->name;
        }
        $info['tagsToString'] = trim($tagsToString, ',');
        return view('questions.ask_detail')->with('askdetail',$info);
    }
    /**
     * 问题编辑
     *
     * @param void
     *
     * @return Response
     */
    public function askselect(Request $request,$askid)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid'=>$uid,
            'question_id'=>$askid,
        ];
        $repo = new QuestionsAskRepository($input);

        $repo->dofunction = 'askselect';
        $info = $repo->contract();
        if (! $repo->passes()) {
            throw new UnauthorizedHttpException('no my questions');
        }
        return view('questions.ask_edit')->with('askdetail',$info);
    }
    /**
     * 问答脚本
     *
     * @param void
     *
     * @return Response
     */
    public function askscript(Request $request,$type)
    {
        //DB::beginTransaction();
        if($type == 'executescripts'){
            $users= DB::table('users')
                ->select('id')
                ->get();
            foreach ($users as $userval){
                if(empty(DB::table('user_analysis')->where('uid',$userval->id)->first())){
                    DB::table('user_analysis')->insert(
                        ['uid' => $userval->id, 'created_at'=>date('y-m-d H:i:s',time()),'updated_at'=>date('y-m-d H:i:s',time())]
                    );
                }

                $vote_up = 0;
                $questionnum = DB::table('questions')
                    ->where([['uid',$userval->id],['status',0],['created_at','<=',date('y-m-d H:i:s',time())]])
                    ->count();

                $answers = DB::table('answers')
                    ->join('questions', 'questions.id', '=', 'answers.question_id')
                    ->where([['answers.uid',$userval->id],['questions.status',0]])
                    ->get();
                foreach ($answers as $answerval){
                    $vote_up = $vote_up+$answerval->vote_up;
                }

                $articles = DB::table('articles')->select('vote_up')->where([['uid',$userval->id],['standard',1]])->get();

                foreach ($articles as $articleval){
                    $vote_up = $vote_up +$articleval->vote_up;
                }
                $answersnum = DB::table('answers')
                    ->join('questions', 'questions.id', '=', 'answers.question_id')
                    ->where([['answers.uid',$userval->id],['questions.status',0]])
                    ->count();
                $followingnum = DB::table('user_following')->where('uid',$userval->id)->count();
                $followernum = DB::table('user_following')->where('following',$userval->id)->count();
                $invitationsnum =DB::table('question_invitations')->where('invited',$userval->id)->count();
                DB::table('user_analysis')
                    ->where('uid',$userval->id)
                    ->update(['question' => $questionnum,'reputation'=> $vote_up,'answer' => $answersnum,'following' => $followingnum,'invitation' => $invitationsnum,'follower'=>$followernum]);

            }
        }else{
            return redirect()->route('login');
        }
    }
}
