<?php

namespace App\Repositories;

use App\Utils\HttpStatus;
use DB;
use Log;
use Illuminate\Support\Facades\Input;
use App\Entity\DialogMessage;
use App\Entity\Dialogs;
use App\Entity\Notification;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Question;
use App\Entity\Answers;
use App\Entity\AnswerComments;
use App\Entity\Contents;
use App\Entity\ContentUser;
use App\Entity\ArticleHistory;
class NoticeRespository extends Repository implements RepositoryInterface
{
    /**
     * 个人信息
     *
     * {@inheritdoc}
     */
    public function contract()
    {
    }
    /**
     * Wrap the contract result to JSON object.
     *
     * {@inheritdoc}
     */
    public function wrap()
    {
        $wrapper = [];

        if (! $this->passes()) {
            $wrapper = [
                'error_id' => $this->status,
                'description' => $this->description,
                'error_name' => HttpStatus::$statusTexts[$this->status],
            ];

            if (! $this->errors->isEmpty()) {
                $errors = [];
                foreach ($this->errors->getErrors() as $key => $value) {
                    $errors[] = [
                        'input' => $key,
                        'message' => $value
                    ];
                }
                $wrapper['errors'] = $errors;
            }

        }
        return $wrapper;
    }

    /*
     * 问答信息页面展示
     */
    public function askMsg($userid)
    {
        $page = empty($this->input['page'])?1:$this->input['page'];
        if(intval($page)<1){
            $page = 1;
        }
        $notifications = DB::table('notifications')
            ->join('users','notifications.from','=','users.id')
            ->select('users.id as userId','users.display_name as name','users.avatar','notifications.show_type','notifications.associate_id','notifications.read','notifications.created_at')
            ->where(['notifications.recipient'=>$userid,'notifications.type'=>3])
            ->orderBy('notifications.created_at','desc')
            ->take($this->input['pageSize'])
            ->skip(($page-1)*$this->input['pageSize'])
            ->get();
        if(!empty($notifications)){
            foreach ( $notifications as $key=>$notification){
                //最新
                if($notification->read == 1){
                    $notification->is_new = 0;
                }else{
                    $notification->is_new = 1;
                }
                //问题相关(回答，编辑，删除，回答了关注的问题，邀请回答问题);
                if(in_array($notification->show_type,array(21,22,23,24,25))){
                    $question = Question::select('id','subject')->where('id',$notification->associate_id)->first();
                    if($question != null){
                        $notification->question_id = $question->id;
                        $notification->question_subject = $question->subject;
                    }else{
                        unset($notifications[$key]);
                    }
                }
                //回答相关(赞同，评论);
                if(in_array($notification->show_type,array(26,27))){
                    $answer = DB::table('answers')
                        ->join('questions','answers.question_id','=','questions.id')
                        ->select('answers.id as answer_id','answers.question_id','answers.detail','questions.subject as question_subject')
                        ->where('questions.status',0)
                        ->where('answers.id',$notification->associate_id)
                        ->first();

                    if($answer != null){
                        $notification->answer_detail = mb_strlen(strip_tags($answer->detail), 'utf-8') > 100 ? mb_substr(strip_tags($answer->detail), 0, 100, 'utf-8').'...' : strip_tags($answer->detail);
                        $notification->question_id = $answer->question_id;
                        $notification->question_subject = $answer->question_subject;
                    }else{
                        unset($notifications[$key]);
                    }
                }
                //回复了评论
                if($notification->show_type == 28){
                    $comment = DB::table('answer_comments')
                        ->join('answers','answer_comments.answer_id','=','answers.id')
                        ->join('questions','answers.question_id','=','questions.id')
                        ->select('answer_comments.id as comment_id','answer_comments.content as comment_content','answers.id as answer_id','answers.question_id','answers.detail','questions.id as question_id','questions.subject as question_subject')
                        ->where('questions.status',0)
                        ->first();
                    if($comment != null){
                        $notification->question_id = $comment->question_id;
                        $notification->question_subject = $comment->question_subject;
                        $notification->comment_id = $comment->comment_id;
                        $notification->comment_content = mb_strlen($comment->comment_content, 'utf-8') > 100 ? mb_substr($comment->comment_content, 0, 100, 'utf-8').'...' : $comment->comment_content;
                    }else{
                        unset($notifications[$key]);
                    }
                }

            }
        }else{
            $this->status = 404;
            $this->description = "没有数据了！";
            $this->accepted = false;
        }
        //将通知标为已读
        $askInfo = $this->getNoReadNoticeNum($userid);
        if(!empty($askInfo) && $askInfo['answerNum']>0){
            $this->updateNoReadNoticeToRead($userid,3);
        }
        return $notifications;
    }

    /*
     * 文章信息页面展示
     */
    public function articleMsg($userid)
    {
        $page = empty($this->input['page'])?1:$this->input['page'];
        if(intval($page)<1){
            $page = 1;
        }
        $notifications = DB::table('notifications')
            ->join('users','notifications.from','=','users.id')
            ->join('articles','notifications.associate_id','=','articles.id')
            ->select('users.id as userId','users.display_name as name','users.avatar','articles.id as articleId','articles.subject','notifications.show_type','notifications.associate_id','notifications.read','notifications.created_at')
            ->where(['notifications.recipient'=>$userid,'notifications.type'=>2])
            ->orderBy('notifications.created_at','desc')
            ->take($this->input['pageSize'])
            ->skip(($page-1)*$this->input['pageSize'])
            ->get();

        if(!empty($notifications)){
            foreach ( $notifications as $notification){
                $notification->articleSubject = $notification->subject;
                //最新
                if($notification->read == 1){
                    $notification->is_new = 0;
                }else{
                    $notification->is_new = 1;
                }

                //文章历史数据
                $articleHistorys = ArticleHistory::select('article_id','reason')->where('article_id',$notification->associate_id)->get();
                if($articleHistorys != null){
                    foreach ($articleHistorys as $articleHistory){
                        $notification->content = $articleHistory->reason;
                    }
                }
            }
        }else{
            $this->status = 404;
            $this->description = "没有数据了！";
            $this->accepted = false;
        }
        //将通知标为已读
        $articleInfo = $this->getNoReadNoticeNum($userid);
        if(!empty($articleInfo) && $articleInfo['articleNum']>0){
            $this->updateNoReadNoticeToRead($userid,2);
        }
        return $notifications;
    }

    /*
     * 私信页面展示
     */
    public function privateMsg($userid)
    {
        $page = empty($this->input['page'])?1:$this->input['page'];
        if(intval($page)<1){
            $page = 1;
        }
        $dialogs = DB::table("dialogs")
            ->select('id')
            ->where('recipient',$userid)
            ->orwhere('sender',$userid)
            ->orderBy('updated_at','desc')
            ->take($this->input['pageSize'])
            ->skip(($page-1)*$this->input['pageSize'])
            ->get();

        if(!empty($dialogs)){
            foreach($dialogs as $key =>$dialog){
                $dialogMessage = DialogMessage::select('id','sender','recipient','content','read','created_at')->where('dialog_id',$dialog->id)->where('operator','<>',$userid)->orderBy('created_at','desc')->first();
                $num = DialogMessage::where('dialog_id',$dialog->id)->where('operator','<>',$userid)->count();
                if($dialogMessage != null){
                    $user = User::select('id','display_name','avatar')->where('id',$dialogMessage->sender)->first();
                    if($user != null){
                        $dialog->name = $user->display_name;
                        $dialog->avatar = $user->avatar;
                    }
                    if($dialogMessage->recipient == $userid && $dialogMessage->read == 0){
                        $dialog->is_new = 1;
                    }else{
                        $dialog->is_new = 0;
                    }
                    $dialog->count = $num;
                    $dialog->content = $dialogMessage->content;
                    $dialog->created_at = $dialogMessage->created_at;
                }else{
                    unset($dialogs[$key]);
                }
            }
        }else{
            $this->status = 404;
            $this->description = "没有数据了！";
            $this->accepted = false;
        }
        //将私信标为已读
        $privateInfo = $this->getNoReadNoticeNum($userid);
        if(!empty($privateInfo) && $privateInfo['privateMsgNum']>0){
            DB::table('dialog_messages')
                ->where(['recipient'=>$userid,'read'=>0])
                ->update(['read'=>1]);
        }
        return $dialogs;
    }

    /*
     * 私信详情页面展示
     */
    public function privateLetterDetail($loginId,$dialogId)
    {
        $dialog = Dialogs::select('sender','recipient')->where('id',$dialogId)->first();
        if($dialog != null){
            if($dialog->sender != $loginId && $dialog->recipient != $loginId){
                $this->status = 403;
                $this->description = "无权限";
                $this->accepted = false;    
            }
            if($this->passes()){
                if($dialog->sender == $loginId){
                    $user = User::select('id','display_name','avatar')->where('id',$dialog->recipient)->first();
                }else{
                    $user = User::select('id','display_name','avatar')->where('id',$dialog->sender)->first();
                }
                $user->dialogId = $dialogId;
                $contents = DialogMessage::select('id','sender','recipient','dialog_id','content','created_at')->where('dialog_id',$dialogId)->where('operator','<>',$loginId)->orderBy('created_at','desc')->get();
                if($contents != null){
                    foreach($contents as $content){
                        $users = User::select('id','display_name','avatar')->where('id',$content->sender)->first();
                        if($users != null){
                            $content->userId = $users->id;
                            $content->name = $users->display_name;
                            $content->avatar = $users->avatar;
                        }
                    }
                }
                return [
                    'contents'=>$contents,
                    'user'=>$user
                ];
            }
        }else{
            $this->status = 404;
            $this->description = "无数据";
            $this->accepted = false;
        }
    }

    /*
     * 系统通知页面展示
     */
    public function systemMsg($userid)
    {
        //查询用户通知表
        $contentUserInfo = DB::table('content_user')
            ->join('users','content_user.uid','=','users.id')
            ->select('users.created_at','content_user.content_id')
            ->where('uid',$userid)
            ->orderby('content_user.content_id','desc')
            ->first();

        //获取未读系统信息
        if($contentUserInfo === null){
            $userInfo = DB::table('users')->select('id','created_at')->where('id',$userid)->first();
            $notReadMsg = DB::table('contents')->where('created_at','>',$userInfo->created_at)->lists('id');
        }else{
            $notReadMsg = DB::table('contents')->where('id','>',$contentUserInfo->content_id)->where('created_at','>',$contentUserInfo->created_at)->lists('id');
        }
        $notReaddata = [];
        //将未读通知加入到通知列表
        foreach($notReadMsg as $val){
            $notReaddata[] = [
                'uid'=>$userid,
                'content_id'=>$val,
                'created_at'=>date('Y-m-d H:i:s',time()),
                'updated_at'=>date('Y-m-d H:i:s',time()),
            ];
        }
        DB::table("content_user")->insert($notReaddata);

    }

    /*
     * 系统通知分页数据
     */
    public function systemMsgPage($userid)
    {
        $page = empty($this->input['page'])?1:$this->input['page'];
        if(intval($page)<1){
            $page = 1;
        }
        //获取系统通知
        $notifications = DB::table('content_user')
            ->join('users','content_user.uid','=','users.id')
            ->join('contents','content_user.content_id','=','contents.id')
            ->select('users.id as uid','users.display_name as name','contents.content as content','content_user.content_id','content_user.read as is_new','contents.created_at')
            ->where(['content_user.uid'=>$userid,])
            ->orderBy('content_user.created_at','desc')
            ->take($this->input['pageSize'])
            ->skip(($page-1)*$this->input['pageSize'])
            ->get();
        if(empty($notifications)){
            $this->status = 404;
            $this->description = "没有数据了！";
            $this->accepted = false; 
        }
        //将未读系统消息改为已读
        DB::table('content_user')
            ->where(['uid'=>$userid,'read'=>0])
            ->update(['read'=>1]);
        return $notifications;
    }

    /**
     * 私信对话页面添加私信
     */
    public function addPrivateMsg($uid)
    {
        DB::beginTransaction();
        $dialogmsg = new DialogMessage;
        $dialogmsg->dialog_id = $this->input['dialogid'];
        $dialogmsg->sender = $uid;
        $dialogmsg->recipient = $this->input['userid'];
        $dialogmsg->content = $this->input['message'];
        $a = $dialogmsg->save();
        $b = DB::table('dialogs')
            ->where('id',$this->input['dialogid'])
            ->update(['updated_at'=>date("Y-m-d H:i:s",time())]);
        if($a && $b){
            DB::commit();
        }else{
            Log::error("发送私信失败，原因为数据添加失败，用户ID为 ".$uid."，对话ID为".$this->input['dialogid']);
            DB::rollback();
            $this->status = 500;
            $this->description = '写入数据失败';
            $this->accepted = false;
            
        }
    }

    /**
     * 私信列表页面删除私信
     */
    public function delDialog($userid)
    {
        //查询私信信息
        $dialogmsgs = DB::table('dialogs')
            ->join('dialog_messages','dialogs.id','=','dialog_messages.dialog_id')
            ->select('dialog_messages.id','dialog_messages.operator')
            ->where('dialogs.id',$this->input['dialogid'])
            ->get();
        if(!empty($dialogmsgs)){
            Dialogs::destroy($this->input['dialogid']);
        }else{
            foreach($dialogmsgs as $dialogmsg){
                if($dialogmsg->operator > 0){
                    DialogMessage::destroy($dialogmsg->id);
                }else{
                    $dialogInfo = DialogMessage::find($dialogmsg->id);
                    $dialogInfo->operator = $userid;
                    $dialogInfo->save();
                }
            }
        }
    }


    /**
     * 获取所有未读通知条数
     */
    public function getNoReadNoticeNum($uid,$time = null)
    {
        $num = [];
        if($this->passes()){
            $answerNum = Notification::where(['recipient'=>$uid,'type'=>3,'read'=>0])->count();
            $articleNum = Notification::where(['recipient'=>$uid,'type'=>2,'read'=>0])->count();
            if(null != $time){
                $contentNum = Contents::where('created_at','>',$time)->count();
            }else{
                $contentNum = Contents::count();
            }
            $contentUserNum = ContentUser::where('uid',$uid)->count();
            $privateMsgNum = DialogMessage::where(['recipient'=>$uid,'read'=>0])->count();
            $totalNum = $contentNum-$contentUserNum+$answerNum+$articleNum+$privateMsgNum;
            $systemNum = $contentNum-$contentUserNum;
            $num = [
                'totalNum'=>(int)$totalNum >99 ? '99+' : $totalNum,
                'systemNum'=>(int)$systemNum >99 ? '99+' : $systemNum,
                'answerNum'=>(int)$answerNum >99 ? '99+' : $answerNum,
                'articleNum'=>(int)$articleNum >99 ? '99+' : $articleNum,
                'privateMsgNum'=>(int)$privateMsgNum >99 ? '99+' : $privateMsgNum,
            ];
        }
        return $num;
    }

    /**
     * 将已读通知做标记
     */
    public function updateNoReadNoticeToRead($uid,$type)
    {
        $result = DB::table('notifications')
            ->where(['recipient'=>$uid,'type'=>$type,'read'=>0])
            ->update(['read'=>1]);

        return $result;
    }

    /**
     * 私信列表页面添加私信
     */
    public function addDialog($uid)
    {
        $user = User::select('id')->where('display_name', $this->input['recipient'])->first();
        if ($user != null ){
            if ($uid == $user->id) {
                $this->status = 400;
                $this->description = '无法给自己写私信！';
                $this->accepted = false;
            } else {
                //查询dialog是否有用户之间对话id
                $result = Dialogs::select('id')->where(['sender' => $uid, 'recipient' => $user->id])->orwhere(['sender' => $user->id, 'recipient' => $uid])->first();
                
                if ($result != null) {
                    //根据对话id修改私信表时间
                    DB::beginTransaction();
                    $a = DB::table('dialogs')
                        ->where('id', $result->id)
                        ->update(['updated_at' => date("Y-m-d H:i:s", time())]);
                    //新增对话信息
                    $dialogmsg = new DialogMessage;
                    $dialogmsg->dialog_id = $result->id;
                    $dialogmsg->sender = $uid;
                    $dialogmsg->recipient = $user->id;
                    $dialogmsg->content = $this->input['message'];
                    $b = $dialogmsg->save();
                    if ($a && $b) {
                        DB::commit();
                    } else {
                        Log::error("发送私信失败，原因为数据添加失败，用户ID为 ".$uid."，对话ID为".$result->id);
                        DB::rollback();
                        $this->status = 500;
                        $this->description = '写入数据失败';
                        $this->accepted = false;
                    }
                } else {
                    DB::beginTransaction();
                    //私信对话表数据写入
                    $dialog = new Dialogs;
                    $dialog->sender = $uid;
                    $dialog->recipient = $user->id;
                    $a = $dialog->save();
                    //私信内容表数据写入
                    $dialogmsg = new DialogMessage;
                    $dialogmsg->dialog_id = $dialog->id;
                    $dialogmsg->sender = $uid;
                    $dialogmsg->recipient = $user->id;
                    $dialogmsg->content = $this->input['message'];
                    $b = $dialogmsg->save();
                    if ($a && $b) {
                        DB::commit();
                    } else {
                        Log::error("发送私信失败，原因为数据添加失败，用户ID为 ".$uid."，对话ID为".$dialog->id);
                        DB::rollback();
                        $this->status = 500;
                        $this->description = '写入数据失败';
                        $this->accepted = false;
                    }
                }
            }
        }else{
            $this->status = 400;
            $this->description = '用户不存在';
            $this->accepted = false;
        }
    }
}
