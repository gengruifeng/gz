<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Utils\HttpStatus;
use DB;
use Log;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

use App\Entity\UserFollowing;
use App\Entity\UserAnalysis;
use App\Entity\Question;
use App\Entity\Tag;
use App\Entity\QuestionTag;
use App\Entity\QuestionInvitations;
use App\Entity\QuestionStars;
use App\Entity\Notification as notify;
use App\Utils\Notification;
use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;
use App\Utils\Computing;

class PersonalRespository extends Repository implements RepositoryInterface
{
    private $connection;

    /**
     * Construct Search Articles Instance
     *
     * @param array $input
     */
    public function __construct(array $input = [])
    {
        parent::__construct($input);

        $this->connection = new Connection();
        $this->connection->setParams(['host' => env('SPHINX_HOST'), 'port' => env('SPHINX_PORT')]);
    }
    /**
     * 个人信息
     *
     * {@inheritdoc}
     */
    public function contract()
    {
        //获取用户信息
        $userinfo = $this->getUserInfo($this->input['uid']);
        if($userinfo != null){
            $result = $this->selectUser($userinfo->id,$userinfo->occupation);
            $result['display_name'] = $userinfo->display_name;
            $result['avatar'] = $userinfo->avatar;
            $result['userId'] = $userinfo->id;
            if(isset($this->input['loginId'])){
                if($this->isAttention($this->input['loginId'],$this->input['uid'])){
                    $result['isAttention'] = 1;
                }else{
                    $result['isAttention'] = 0;
                }
            }else{
                $result['isAttention'] = 0;
            }
            return $result;
        }
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

    /**
     * 获取用户信息
     */
    public function getUserInfo($uid)
    {
        $userinfo = $this->getUserInfoById($uid);
        return $userinfo;
    }

    /**
     * 根据用户昵称获取用户信息
     */
    public function getUserInfoByDisplayName($displayName)
    {
        return DB::table('users')->select('id','display_name','occupation','avatar')->where('display_name',$displayName)->first();
    }

    /**
     * 根据uid获取user信息
     */
    public function getUserInfoById($uid)
    {
        return DB::table('users')->select('id','display_name','occupation','avatar')->where('id',$uid)->first();
    }
    /**
     * 获取user基本信息
     */
    public function selectUser($uid,$occupation)
    {
        $result=[];
        //查询用户身份描述
        $result['describe'] = $this->userDescribe($uid,$occupation);
        //粉丝数 关注数
        $analysis = DB::table('user_analysis')->select('follower','following')->where('uid',$uid)->first();
        if($analysis != null){
            //关注数
            $result['following']['num'] = $analysis->following;
            //粉丝数
            $result['follower']['num'] = $analysis->follower;
        }else{
            $result['following']['num'] = 0;
            //粉丝数
            $result['follower']['num'] = 0;  
        }
        //擅长领域
        $userTagIds = DB::table('user_proficiencies')->where('uid',$uid)->orderby('created_at','desc')->take(9)->lists('tag_id');
        if($userTagIds){
            $result['userTagInfo'] = DB::table('tags')->select('name')->whereIn('id',$userTagIds)->get();
        }
        return $result;
    }


    /**
     * 获取user基本信息
     */
    public function userDescribe($uid,$occupation)
    {
        $describe = [];
        //查询用户身份
        if( $occupation == 1 ){
            $info = DB::table('user_educations')->select('school','department')->where('uid',$uid)->first();
            if($info == null){
                $describe['first'] = '';
                $describe['second'] = '';
            }else{
                $describe['first'] = $info->school;
                $describe['second'] = $info->department;
            }
        }elseif( $occupation == 2 ){
            $info = DB::table('user_works')->select('company','position')->where('uid',$uid)->first();
            if($info == null){
                $describe['first'] = '';
                $describe['second'] = '';
            }else{
                $describe['first'] = $info->company;
                $describe['second'] = $info->position;
            }
        }
        return $describe;
    }

    /**
     * 获取用户提问相关信息
     */
    public function selectUserQuestions($uid,$page,$pagesize){
        //基本信息
        $time = date('Y-m-d H:i:s',time());
        $result = DB::table('questions')
            ->select('id','subject','answered','viewed','created_at')
            ->where(['uid'=>$uid,'status'=>0])
            ->where('created_at','<=',$time)
            ->skip(($page-1)*$pagesize)
            ->take($pagesize)
            ->orderby('created_at','desc')
            ->get();
        
        if(!empty($result)){
            //当回答数大于0时，查询回答人信息
            foreach ($result as $question)
            {
                if($question->answered >0){
                    $answeredName = [];
                    $answeredInfos = DB::table('answers')->select('uid')->where('question_id',$question->id)->take(2)->get();
                    //最新回答时间
                    $answerTime = DB::table('answers')->select('created_at')->where('question_id',$question->id)->orderby('created_at','desc')->first();
                    if($answerTime != null){
                        $question->created_at = $answerTime->created_at;
                    }
                    if($answeredInfos != null){
                        $answeredIds = DB::table('answers')->where('question_id',$question->id)->take(2)->lists('uid');
                        $answereds = DB::table('users')->select('id','display_name')->whereIn('id',$answeredIds)->get();
                        foreach($answeredInfos as $answeredInfo){
                            if($answereds != null){
                                foreach($answereds as $answered){
                                    if($answered->id == $answeredInfo->uid && !in_array($answered->display_name,$answeredName)){
                                        $answeredName[]= $answered->display_name;
                                    }
                                }
                            }
                        }
                    }
                    $question->answeredName = $answeredName;
                }
            }
            usort($result,function($a,$b){
                $al = $a->created_at;
                $bl = $b->created_at;
                if ($al == $bl) {
                    return 0;
                }
                return ($al <$bl) ? +1 : -1;
            });
            foreach ($result as $question){
                $question->created_at = Computing::timejudgment($question->created_at);
            }
        }else{
            $this->status = 404;
            $this->description = "没有数据了！";
            $this->accepted = false;
        }
        return $result;
    }

    /**
     * 获取用户回答相关信息
     */
    public function selectUserAnswered($uid,$page,$pagesize){
        //基本信息
        $answers = DB::table('answers')
            ->join('questions','answers.question_id','=','questions.id')
            ->select('answers.question_id','answers.detail','answers.created_at','answers.vote_up','questions.subject')
            ->where(['answers.uid'=>$uid,'questions.status'=>0])
            ->orderby('answers.created_at','desc')
            ->skip(($page-1)*$pagesize)
            ->take($pagesize)
            ->get();
        if(!empty($answers)){
            foreach($answers as $answer){
                $answer->questionId = $answer->question_id;
                $answer->title = $answer->subject;
                $answer->detail = strip_tags($answer->detail,'img');
            }
        }else{
            $this->status = 404;
            $this->description = "没有数据了！";
            $this->accepted = false;
        }
        return $answers;
    }

    /**
     * 获取用户文章相关信息
     */
    public function selectUserArticle($loginId,$uid,$displayName,$occupation,$page,$pagesize){
        //基本信息
        if($loginId == $uid){
            $articles = DB::table('articles')->select('id','subject','viewed','stared','created_at')->where('uid',$uid)->orderby('created_at','desc')->skip(($page-1)*$pagesize)->take($pagesize)->get();
        }else{
            $articles = DB::table('articles')->select('id','subject','viewed','stared','created_at')->where(['uid'=>$uid,'standard'=>1])->orderby('created_at','desc')->skip(($page-1)*$pagesize)->take($pagesize)->get();
        }
        $describe = $this->userDescribe($uid,$occupation);
        if(!empty($articles)){
            foreach ($articles as $article){
                $article->display_name = $displayName;
                $article->describe = $describe;
            }    
        }else{
            $this->status = 404;
            $this->description = "没有数据了！";
            $this->accepted = false;
        }
        return $articles;
    }

    /**
     * 获取用户收藏相关信息
     */
    public function selectUserCollect($uid,$page,$pagesize){
        //基本信息
        $articleStars = DB::table('article_stars')->select('article_id','created_at')->where('uid',$uid)->orderby('created_at','desc')->skip(($page-1)*$pagesize)->take($pagesize)->get();
        $articleIds = DB::table('article_stars')->where('uid',$uid)->orderby('created_at','desc')->skip(($page-1)*$pagesize)->take($pagesize)->lists('article_id');
        $articles = DB::table('articles')->select('id','uid','subject','viewed','stared')->where('standard',1)->whereIn('id',$articleIds)->get();
        if(!empty($articles)){
            foreach($articles as $article){
                foreach($articleStars as $articleStar){
                    if($article->id == $articleStar->article_id){
                      $article->created_at = $articleStar->created_at;
                    }
                }
                $userInfo = $this->getUserInfoById($article->uid);
                if($userInfo != null){
                    $article->display_name = $userInfo->display_name;
                    $article->describe = $this->userDescribe($userInfo->id,$userInfo->occupation);   
                }
            }
            usort($articles,function($a,$b){
                $al = $a->created_at;
                $bl = $b->created_at;
                if ($al == $bl) {
                    return 0;
                }
                return ($al <$bl) ? +1 : -1;
            });
        }else{
            $this->status = 404;
            $this->description = "没有数据了！";
            $this->accepted = false;
        }
        return $articles;
    }

    /**
     * 获取用户关注相关信息
     */
    public function selectUserFollow($uid,$page,$pagesize){
        //基本信息
        $questionIds = DB::table('question_stars')->where('uid',$uid)->orderby('created_at','desc')->skip(($page-1)*$pagesize)->take($pagesize)->lists('question_id');
        $questions = DB::table('questions')->select('id','subject','answered','viewed','created_at')->where('status',0)->whereIn('id',$questionIds)->get();
        if(!empty($questions)){
            //当回答数大于0时，查询回答人信息
            foreach ($questions as $key=>$question)
            {

                if($question->answered >0){
                    $answeredName = [];
                    $answeredInfos = DB::table('answers')->select('uid')->where('question_id',$question->id)->orderby('created_at','desc')->take(2)->get();
                    $answeredIds = DB::table('answers')->where('question_id',$question->id)->orderby('created_at','desc')->take(2)->lists('uid');
                    $answereds = DB::table('users')->select('id','display_name')->whereIn('id',$answeredIds)->get();
                    //最新回答时间
                    $answerTime = DB::table('answers')->select('created_at')->where('question_id',$question->id)->orderby('created_at','desc')->first();
                    if($answeredInfos != null){
                        foreach($answeredInfos as $answeredInfo){
                            if($answereds != null){
                                foreach($answereds as $answered){
                                    if($answered->id == $answeredInfo->uid && !in_array($answered->display_name,$answeredName)){
                                        $answeredName[]= $answered->display_name;
                                    }
                                }
                            }
                        }
                    }
                    $question->answeredName = implode(" ",$answeredName);
                    if($answerTime != null){
                        $question->created_at = $answerTime->created_at;
                    }
                }
            }
            usort($questions,function($a,$b){
                $al = $a->created_at;
                $bl = $b->created_at;
                if ($al == $bl) {
                    return 0;
                }
                return ($al <$bl) ? +1 : -1;
            });
            foreach ($questions as $question){
                $question->created_at = Computing::timejudgment($question->created_at);
            }
        }else{
            $this->status = 404;
            $this->description = "没有数据了！";
            $this->accepted = false;
        }
        return $questions;
    }

    /**
     * 获取用户所有粉丝相关信息
     */
    public function selectUserFans($uid){
        $followerIds = DB::table('user_following')->where('following',$uid)->lists('uid');
        $fansInfos = DB::table('users')->select('id','display_name','avatar','occupation')->whereIn('id',$followerIds)->get();
        $analysis = DB::table('user_analysis')->select('uid','follower','question','answer','reputation')->whereIn('uid',$followerIds)->get();
        $attentionIds = DB::table('user_following')->where('uid',$this->input['loginId'])->lists('following');
        if($fansInfos){
            foreach ($fansInfos as $fansInfo){
                $describe = $this->userDescribe($fansInfo->id,$fansInfo->occupation);
                if(in_array($fansInfo->id,$attentionIds)){
                    $fansInfo->isAttention = 1;
                }else{
                    $fansInfo->isAttention = 0;
                }
                if($describe){
                    $fansInfo->firstDescribe = $describe['first'];
                    $fansInfo->secondDescribe = $describe['second'];
                }
                if($analysis != null){
                    foreach($analysis as $analysi){
                        if($fansInfo->id == $analysi->uid){
                            $fansInfo->follower = $analysi->follower;
                            $fansInfo->question = $analysi->question;
                            $fansInfo->answer = $analysi->answer;
                            $fansInfo->reputation = $analysi->reputation;
                        }
                    } 
                }
            }  
        }
        return $fansInfos;
    }

    /**
     * 获取所有关注用户信息
     */
    public function selectUserFollowing($userid){
        $followingIds = DB::table('user_following')->where('uid',$userid)->lists('following');
        $followingInfos = DB::table('users')->select('id','display_name','avatar','occupation')->whereIn('id',$followingIds)->get();
        $analysis = DB::table('user_analysis')->select('uid','follower','question','answer','reputation')->whereIn('uid',$followingIds)->get();
        $attentionIds = DB::table('user_following')->where('uid',$this->input['loginId'])->lists('following');
        if($followingInfos != null){
            foreach ($followingInfos as $followingInfo){
                $describe = $this->userDescribe($followingInfo->id,$followingInfo->occupation);
                if(in_array($followingInfo->id,$attentionIds)){
                    $followingInfo->isAttention = 1;
                }else{
                    $followingInfo->isAttention = 0;
                }
                if($describe){
                    $followingInfo->firstDescribe = $describe['first'];
                    $followingInfo->secondDescribe = $describe['second'];
                }
                if($analysis != null){
                    foreach($analysis as $analysi){
                        if($followingInfo->id == $analysi->uid){
                            $followingInfo->follower = $analysi->follower;
                            $followingInfo->question = $analysi->question;
                            $followingInfo->answer = $analysi->answer;
                            $followingInfo->reputation = $analysi->reputation;
                        }
                    }   
                }
            } 
        }
        return $followingInfos;
    }

    /**
     * 是否已关注 true-是 false-否
     *
     */
    public function isAttention($loginId,$userId)
    {
        $info = DB::table('user_following')
            ->select('id')
            ->where(['uid'=>$loginId,'following'=>$userId])
            ->first();
        return $info;
    }
    /**
     * 加关注
     *
     */
    public function addAttention($uid,$fid)
    {
        $this->checkId($fid);
        if($uid == $fid){
            $this->status = 400;
            $this->description = "自己不能关注自己";
            $this->accepted = false;
        }

        if($this->passes()) {
            DB::beginTransaction();
            $userfollowInfo = UserFollowing::where(['uid' => $uid, 'following' => $fid,])->first();
            if($userfollowInfo === null){
                $userfollow = new UserFollowing;
                $userfollow->uid = $uid;
                $userfollow->following = $fid;
                $a = $userfollow->save();
            }else{
                $this->status = 400;
                $this->description = "已关注";
                $this->accepted = false;
                return;
            }
            $following = UserAnalysis::where('uid', $uid)->first();
            if($following != null){
                $following->following = $following->following + 1;
                $b = $following->save();
            }else{
                $following = new UserAnalysis;
                $following->uid = $uid;
                $following->following = 1;
                $b = $following->save();
            }
            $follower = UserAnalysis::where('uid', $fid)->first();
            if($follower != null){
                $follower->follower = $follower->follower + 1;
                $c = $follower->save();
            }else{
                $follower = new UserAnalysis;
                $follower->uid = $fid;
                $follower->follower = 1;
                $c = $follower->save();
            }
            if ($a && $b && $c) {
                Notification::sendNotify($uid, $fid, 3, 29);
                DB::commit();
            } else {
                Log::error("添加关注失败，原因为数据添加失败，用户ID为 ".$uid."，关注用户ID为".$fid);
                DB::rollback();
                $this->status = 500;
                $this->description = '写入数据失败';
                $this->accepted = false;
            }
        }
    }

    /**
     * 取消关注
     *
     */
    public function delAttention($uid,$fid)
    {
        $this->checkId($fid);
        if($this->passes()) {
            DB::beginTransaction();
            $a = UserFollowing::where(['uid' => $uid, 'following' => $fid,])->delete();
            $following = UserAnalysis::where('uid', $uid)->first();
            if ($following === null) {
                $follow = new UserAnalysis;
                $follow->uid = $uid;
                $b = $follow->save();
            } else {
                if ($following->following > 0) {
                    $following->following = $following->following - 1;
                    $b = $following->save();
                } else {
                    $b = true;
                }
            }
            $follower = UserAnalysis::where('uid', $fid)->first();
            if ($follower === null) {
                $follow = new UserAnalysis;
                $follow->uid = $fid;
                $c = $follow->save();
            } else {
                if ($follower->follower > 0) {
                    $follower->follower = $follower->follower - 1;
                    $c = $follower->save();
                } else {
                    $c = true;
                }
            }
            $notify = notify::where(['from' => $uid, 'recipient' => $fid, 'show_type' => 29])->first();
            if ($notify == null) {
                $d = true;
            } else {
                $d = notify::where(['from' => $uid, 'recipient' => $fid, 'show_type' => 29])->delete();
            }
            if ($a && $b && $c && $d) {
                DB::commit();
            } else {
                Log::error("取消关注失败，原因为数据删除失败，用户ID为 ".$uid."，删除用户ID为".$fid);
                DB::rollback();
                $this->status = 500;
                $this->description = '删除失败';
                $this->accepted = false;
            }
        }
    }

    /**
     * 关注、取消关注id 验证
     *
     */
    public function checkId($id){
        $data=[
            'id'=>$id,
        ];
        $rules = [
            'id'=>'required|integer|exists:users,id',
        ];
        $messages = [
            'id.required'=>'用户ID不能为空',
            'id.email'=>'ID数据类型不正确',
            'id.unique'=>'用户不存在',
        ];
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->errors();
            $this->status = 400;
            $this->description = $messages->first('id');
            $this->accepted = false;
        }
    }
    /**
     * 向他提问
     *
     */
    public function askQuestion($uid)
    {
        $question = Question::where('subject',$this->input['title'])->first();
        if($question ===null){
            DB::beginTransaction();
            $question = new Question;
            $question->uid = $uid;
            $question->subject = $this->input['title'];
            $question->detail = $this->input['content'];
            $question->stared = 1;
            $questionResult = $question->save();
            $tagResult = true;
            $questionTagResult = true;
            $questionStarsResult = true;
            $questionInvitationsResult = true;
            if($questionResult){
                //问题标题加入sphinx
                $this->sphinxAddQuestionTitle($question->id,$this->input['title']);
                //添加标签
                $tags=explode(',',$this->input['tags']);
                foreach ($tags as $tag){
                    $tagInfo = Tag::select('id')->where('name',$tag)->first();
                    if($tagInfo === null){
                        $newTag = new Tag;
                        $newTag->name = $tag;
                        $newTag->uid = $uid;
                        if($newTag->save()){
                            $tagId = $newTag->id;
                            $this->sphinxAddTagName($tagId,$tag);
                        }else{
                            $tagResult = false;
                        }
                    }else{
                        $tagId = $tagInfo->id;
                        $this->sphinxAddTagName($tagId,$tag);
                    }
                    $questionTag = new QuestionTag;
                    $questionTag->question_id = $question->id;
                    $questionTag->tag_id = $tagId;
                    if(!$questionTag->save()){
                        $questionTagResult=false;
                    }
                }
                $questionInvitations = new QuestionInvitations;
                $questionInvitations->uid = $uid;
                $questionInvitations->question_id = $question->id;
                $questionInvitations->invited = $this->input['userid'];

                if(!$questionInvitations->save()){
                    $questionInvitationsResult = false;
                }
                //默认关注此问题
                $questionStars = new QuestionStars;
                $questionStars->uid = $uid;
                $questionStars->question_id = $question->id;
                if(!$questionStars->save()){
                    $questionStarsResult = false;
                }
            }
            $userAnalysisResult = DB::table('user_analysis')->where('uid',$this->input['userid'])->increment('question', 1);
            if($questionResult && $tagResult && $questionTagResult  && $questionStarsResult && $questionInvitationsResult && $userAnalysisResult){
                Notification::sendNotify($uid,$this->input['userid'],3,25,$question->id);
                DB::commit();
            }else{
                Log::error("向TA提问失败，原因为数据添加失败，用户ID为 ".$uid."，被提问用户ID为".$this->input['userid']);
                DB::rollback();
                $this->status = 500;
                $this->description = '数据添加失败';
                $this->accepted = false;
            }
        }else{
            $this->status = 400;
            $this->description = '问题标题重复';
            $this->accepted = false;
        }
    }

    /**
     * 标签新建成功将标签名称存入sphinx
     *
     */
    public function sphinxAddTagName($id,$tagName){
        $ret = SphinxQL::create($this->connection)->query("select * from tags where id=".$id)->execute();
        if(!empty($ret)){
            $sphinx = SphinxQL::create($this->connection)->replace()->into('tags');
            $sphinx->value('id', $id)->value('name', $tagName);
            $sphinx->execute();
        }else{
            SphinxQL::create($this->connection)->query("insert into tags (id,name) values ({$id},'".$tagName."')")->execute();
        }
    }

    /**
     * 问题新建成功将问题标题存入sphinx
     *
     */
    public function sphinxAddQuestionTitle($id,$subject){
        $ret = SphinxQL::create($this->connection)->query("select * from questions where id=".$id)->execute();
        if(!empty($ret)){
            $sphinx = SphinxQL::create($this->connection)->replace()->into('questions');
            $sphinx->value('id', $id)->value('subject', $subject);
            $sphinx->execute();
        }else{
            SphinxQL::create($this->connection)->query("insert into questions (id,subject) values ({$id},'".$subject."')")->execute();
        }
    }


}
