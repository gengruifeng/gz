<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;

use App\Utils\Upload;
use App\Utils\Notification;

use App\Repositories\QuestionsAskRepository;
use App\Repositories\QuestionsNolineRepository;

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
        $input = [
            'uid'=>$uid,
            'subject' => Input::get('subject'),
            'detail' => Input::get('detail'),
            'tags' => Input::get('tags'),
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'askadd';
        $repo->contract();

        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }

        return response()->json($repo->info);
    }
    /**
     * 问题删除
     *
     * @param void
     *
     * @return Response
     */
    public function askdel(Request $request)
    {

        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid'=>$uid,
            'question_id'=>Input::get('ask_id'),
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'askdel';
        $repo->contract();
        if ($repo->status==402) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json("");
    }
    /**
     * 问答列表
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
            'type'=>Input::get('type'),
            'page'=>Input::get('page'),
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'asklist';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->info);
    }
    /**
     * 回答列表
     *
     * @param void
     *
     * @return Response
     */
    public function answerslist(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid'=>$uid,
            'answer_id'=>Input::get('answerid'),
            'askid'=>Input::get('askid'),
            'page'=>Input::get('page'),
            'askType'=>Input::get('askType'),
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'answerslist';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }

        return response()->json($repo->info);
    }
    /**
     * 用户点赞
     *
     * @param void
     *
     * @return Response
     */
    public function voteup(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid'=>$uid,
            'answers_id'=>Input::get('answers_id'),
            'answersuid'=>Input::get('answersuid'),
            'ask_id'=>Input::get('ask_id'),
            'up'=>Input::get('up'),
            'askType'=>Input::get('askType'),
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'voteup';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->info);
    }
    /**
     * 用户关注
     *
     * @param void
     *
     * @return Response
     */
    public function stared(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid'=>$uid,
            'answers_id'=>Input::get('answers_id'),
            'ask_id'=>Input::get('ask_id'),
            'askuid'=>Input::get('askuid'),
            'askType'=>Input::get('askType'),
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'stared';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->info);
    }
    /**
     * 上传问答图片
     *
     * @param void
     *
     * @return Response
     */
    public function askupload(Request $request)
    {
        $avatar = Upload::img('assets');
        if($avatar){
            $imgges = [
                'success' => true,
                'msg' => '上传成功',
                'file_path' => $avatar,
            ];
        }else{
            $imgges = [
                'success' => false,
                'msg' => '上传失败，请选择图片文件进行上传！',
                'file_path' => $avatar,
            ];
        }
        return response()->json($imgges);
    }
    /**
     * 用户回答
     *
     * @param void
     *
     * @return Response
     */
    public function answers(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid'=>$uid,
            'ask_id'=>Input::get('ask_id'),
            'askuid'=>Input::get('askuid'),
            'editor'=>Input::get('editor'),
            'askType'=>Input::get('askType'),
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'answers';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json( $repo->info);
    }
    /**
     * 用户评论
     *
     * @param void
     *
     * @return Response
     */
    public function commented(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid'=>$uid,
            'answer_id'=>Input::get('answer_id'),
            'answers_uid'=>Input::get('answers_uid'),
            'content'=>htmlspecialchars(Input::get('detail')),
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'commented';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json( $repo->info);
    }
    /**
     * 问题编辑
     *
     * @param void
     *
     * @return Response
     */
    public function askedit(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid'=>$uid,
            'question_id'=> Input::get('askid'),
            'subject' => Input::get('subject'),
            'detail' => Input::get('detail'),
            'tags' => Input::get('tags'),
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'askedit';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->info);
    }
    /**
     * 回答删除
     *
     * @param void
     *
     * @return Response
     */
    public function answeredel(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid'=>$uid,
            'ask_id'=>Input::get('ask_id'),
            'answer_id'=>Input::get('answer_id'),
            'askType'=>Input::get('askType'),
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'answeredel';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->info);
    }
    /**
     * 回答编辑
     *
     * @param void
     *
     * @return Response
     */
    public function answereup(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid'=>$uid,
            'answer_id'=>Input::get('answer_id'),
            'detail'=>Input::get('detail'),
            'askType'=>Input::get('askType'),
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'answereup';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->info);
    }
    /**
     * 评论删除
     *
     * @param void
     *
     * @return Response
     */
    public function commenteddel(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid'=>$uid,
            'comment_id'=>Input::get('comment_id'),
            'answer_id'=>Input::get('answer_id'),
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'commenteddel';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->info);
    }
    /**
     *回复删除
     *
     * @param void
     *
     * @return Response
     */
    public function repliesdel(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid'=>$uid,
            'replies_id'=>Input::get('replies_id'),
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'repliesdel';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->info);
    }
 /**
 * 用户卡片
 *
 * @param void
 *
 * @return Response
 */
    public function card(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid'=>$uid,
            'carduid'=>Input::get('carduid'),
            'askType'=>Input::get('askType'),
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'card';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->info);
    }
    /**
     * 评论列表
     *
     * @param void
     *
     * @return Response
     */
    public function commentedlist(Request $request)
    {
        $input = [
            'uid' => empty($request->security()->get('uid'))?'':$request->security()->get('uid'),
            'answer_id'=>Input::get('answer_id'),
            'page' => Input::get('page'),
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'commentedlist';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->info);
    }
    /**
     * 邀请人列表
     *
     * @param void
     *
     * @return Response
     */
    public function invitations(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'question_id'=>Input::get('askid'),
            'page'=>Input::get('page'),
            'uid'=>$uid,
        ];
        $questionnoline = new QuestionsNolineRepository();
        $questionnoline->contract();
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'invitations';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->info);
    }
    /**
     * 邀请人回答添加
     *
     * @param void
     *
     * @return Response
     */
    public function invitationsadd(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'askuid'=>Input::get('askuid'),
            'question_id'=>Input::get('askid'),
            'invited'=>Input::get('invited'),
            'uid'=>$uid,
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'invitationsadd';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->info);
    }
    /**
     * 搜索用户信息
     *
     * @param void
     *
     * checkuser
     * @return Response
     */
    public function search(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid' =>$uid,
            'data' => empty(Input::get('data'))?[]:Input::get('data'),
            'question_id'=>Input::get('askid'),
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'search';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->info);
    }
    /**
     * 检验此用户是否可以回答问题
     *
     * @param void
     *
     *
     * @return Response
     */
    public function checkuser(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid' =>$uid,
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'checkuser';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->info);
    }
}
