<?php

namespace App\Repositories;

use DB;
use Log;
use Validator;
use App\Entity\Question;
use App\Entity\Tag;
use App\Entity\Answers;
use App\Entity\AnswerComments;

use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;

use App\Utils\HttpStatus;
use App\Utils\Pagination;
use App\Utils\Computing;
use App\Utils\Notification;

class QuestionsAskRepository extends Repository implements RepositoryInterface
{
    const PATTERN_TAG = '#^(?P<tag>[0-9a-zA-Z\x{4e00}-\x{9fa5}\#\+]+)(;(?&tag))*$#u';       //返回数组
    public $info = [];
    //访问控制器
    public $dofunction ;
    /**
     * 提问业务
     *
     * {@inheritdoc}
     */
    public function contract()
    {
        //验证tag标签是否唯一
        if($this->passes()){
            $funtion = $this->dofunction;
            $this->$funtion();
        }
        return $this->info;
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
     * Validate the input
     *
     * @param void
     *
     * @return void
     */
    private function validate()
    {
        $rules = [
            'subject' => 'required',
            'detail' => 'required',
            'tags' => 'required|regex:'.self::PATTERN_TAG,
        ];
        $messages = [
            'subject.required' => '请填写标题',
            'detail.required' => '请填写内容',
            'tags.required' => '请添加至少一个标签',
            'tags.regex' => '标签不正确'
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '请输入信息';
            $this->accepted = false;

            $messages = $validator->errors();

            if ($messages->has('subject')) {
                $this->errors->add('subject', $messages->first('subject'));
            }

            if ($messages->has('detail')) {
                $this->errors->add('detail', $messages->first('detail'));
            }

            if ($messages->has('tags')) {
                $this->errors->add('tags', $messages->first('tags'));
            }
        }
    }
    /**
     * 添加问题
     *
     * @return void
     */
    private function askadd()
    {
        DB::beginTransaction();
        $this->validate();

        $questionyz= DB::table('questions')
            ->where([['subject','=',$this->input['subject']],['status','=',0]])
            ->first();

        if(!empty($questionyz)){
            $this->accepted = false;
            $this->status = 402;
            $this->description = '标题已经存在';
        }

        if($this->passes()){
            $question = new Question();
            $question->uid=$this->input['uid'];
            $question->subject=trim($this->input['subject']);
            $question->detail=$this->input['detail'];
            $tagNames = explode(';', $this->input['tags']);
            //数据入库
            if(! $question->save()){
                Log::error("添加问题失败，原因为数据添加失败，用户ID为 ".$this->input['uid']."");
                DB::rollBack();
                $this->accepted = false;
                $this->status = 500;
                $this->description = '发生一个内部错误, 问题未添加';
            }

            if ($this->passes()) {

                if (5 < count($tagNames)) {
                    $this->accepted = false;
                    $this->status = 400;
                    $this->description = '不得超过5个标签';
                }
            }
            if ($this->passes()) {

                $tagIds = Tag::getTagsId($tagNames,$this->input['uid']);

                DB::table('tags')->whereIn('id',$tagIds)->increment('tagged_answers', 1);

                $datetime = date('Y-m-d H:i:s');
                $connection = new Connection();
                $connection->setParams(['host' => env('SPHINX_HOST'), 'port' => env('SPHINX_PORT')]);
                $ret = SphinxQL::create($connection)->query("select * from questions where id=".$question->id)->execute();

                if(empty($ret)){
                    $question->subject = addslashes($question->subject);
                    $sphinx =SphinxQL::create($connection)->query("insert into questions values ({$question->id},'".$question->subject."')")->execute();
                }

                $articleTags = [];

                foreach ($tagIds as $value) {

                    $addanswers=DB::table('question_tags')->insert(
                        ['question_id' => $question->id, 'tag_id' => $value,'created_at'=>date('y-m-d H:i:s',time()),'updated_at'=>date('y-m-d H:i:s',time())]
                    );

                }
                if (! $addanswers && $sphinx) {
                    DB::rollBack();
                    Log::error("添加问题失败，原因为新增标签和spihnx失败,用户ID为 ".$this->input['uid']."");

                    $this->accepted = false;

                    $this->status = 500;

                    $this->description = '发生一个内部错误, 标签未添加';

                }else{
                    DB::commit();
                    //默认关注自己的问题
                    DB::table('question_stars')->insert(
                        ['uid' => $this->input['uid'], 'question_id' => $question->id,'created_at'=>date('y-m-d H:i:s',time()),'updated_at'=>date('y-m-d H:i:s',time())]
                    );
                    //修改关注数量
                    $upstared=DB::table('questions')->where([['id', $question->id],['status','=',0]])->increment('stared', 1);
                    //修改用户统计的问题数量
                    if(!empty(DB::table('user_analysis')->where('uid',$this->input['uid'])->first())){
                        DB::table('user_analysis')->where('uid',$this->input['uid'])->increment('question', 1);
                    }
                    $this->info = $questionres =[

                        'description' => '添加成功',

                        'question_id'=>$question->id,

                        'subject'=>$this->input['subject'],

                    ];

                }
            }
        }
    }
    /**
     * 问答热门问题 and 标签
     *
     * @return void
     */
    private function askInfo()
    {
        $this->pagevalidate();
        $type =  empty($this->input['type'])?'created_at':$this->input['type'];
        if($this->passes()){
            //查询热门问题的id
            $hotasks=DB::table('questions')
                ->select('id','subject','detail','answered')
                ->where([['is_hot','=',1],['status','=',0]])
                ->limit('3')
                ->get();
            //热门标签
            $hottags=DB::table('tags')
                ->select('id','name')
                ->orderBy('tagged_answers','desc')
                ->limit('12')
                ->get();
            //网站问题统计and用户统计
            $usercount=DB::table('users')
                ->where('disabled','=',0)
                ->count('id');
            $questioncount=DB::table('questions')
                ->where([['status','=',0],['created_at','<=',date('y-m-d H:i:s',time())]])
                ->count('id');
            $this->info = [
                'hottags' => $hottags,//热门标签
                'usercount' => $usercount,//用户总人数
                'questioncount'=> $questioncount,//问题总数
                'hotasks' => $hotasks,//热门问题
                'type' => $type,
            ];
        }
    }
    /**
     * 问题下拉加载
     *
     * @return array
     */
    private function asklist()
    {
        $page = empty($this->input['page'])?1:$this->input['page'];
        if(intval($page)<1){
            $page = 1;
        }
        //判断问题类型，类型为  最新问题 created_at 最多点赞  vote_up 待回答问题 answered =0
        $type =  empty($this->input['type'])?'created_at':$this->input['type'];
        $askList = [];
        $pageSize = 10;
        $pageStart = ($page - 1) * $pageSize ;
        $page = empty($this->input['page'])?1:$this->input['page'];
        //问答列表
        if($type=='answered'){
            //获取总条数
            $askcount = DB::table('questions')
                ->where([['answered','=',0],['status','=',0],['is_hot','=',0]])
                ->count('id');

            $ask=DB::select("select o.id,o.uid,o.subject,o.detail,o.answered,o.viewed,o.stared,o.vote_up,o.created_at from  (select id from questions where answered = 0 and status = 0 and is_hot = 0 and created_at <= '".date('y-m-d H:i:s',time())."' ORDER BY answered desc,created_at desc limit ".$pageStart.",".$pageSize.") as i join questions as o on o.id = i.id 
");
        }else{
            //获取总条数
            $askcount = DB::table('questions')
                ->where([['status','=',0],['is_hot','=',0]])
                ->count('id');
            $ask=DB::select("select o.id,o.uid,o.subject,o.detail,o.answered,o.viewed,o.stared,o.vote_up,o.created_at from  (select id from questions  where status = 0 and is_hot = 0 and created_at <= '".date('y-m-d H:i:s',time())."' ORDER BY ".$type." desc  limit ".$pageStart.",".$pageSize.") as i join questions as o on o.id = i.id 
");
        }
        foreach ($ask as $val){
            //查询答案
            $answers=DB::table('answers')
                ->select('detail','vote_up','question_id','commented','created_at','uid')
                ->orderBy('vote_up','desc')
                ->orderBy('created_at','desc')
                ->where('question_id',$val->id)
                ->first();
            //查询标签
            $tagid=DB::table('question_tags')
                ->where('question_id',$val->id)
                ->lists('tag_id');
            //问题标签
            $tags=DB::table('tags')
                ->select('id','name','tagged_answers')
                ->whereIn('id',$tagid)
                ->get();
            $uid = ! empty($answers->uid)?$answers->uid:$val->uid;
            //查询用户信息
            if(!empty($answers->uid)){
                $answersuser=$this->userInfo($uid);
                $username =  !empty($answersuser->display_name)?(mb_strlen($answersuser->display_name, 'utf-8') > 10 ? mb_substr($answersuser->display_name, 0, 10, 'utf-8').'...' : $answersuser->display_name):"";

            }
            $user=$this->userInfo($val->uid);
            //问答信息列表
            //判断用户是否是问题的发布者 1是 2不是
            if(!empty($this->input['uid'])){
                $answeruserstatus = $this->input['uid']==$val->uid?1:2;
            }
            $askList[]= [
                'detail'=>!empty($answers->detail)?mb_strlen(strip_tags($answers->detail), 'utf-8') > 10 ? mb_substr(strip_tags($answers->detail), 0, 100, 'utf-8').'...' : strip_tags($answers->detail):'',
                'vote_up'=>empty($answers->vote_up)?0:$answers->vote_up,
                'commented'=>empty($answers->commented)?0:$answers->commented,
                'created_at'=>empty($answers->created_at)?'':Computing::timejudgment($answers->created_at),
                'answereduid'=>empty($answers->uid)?'':$answers->uid,
                'askdetail'=>mb_strlen(strip_tags($val->detail), 'utf-8') > 100 ? mb_substr(strip_tags($val->detail), 0, 100, 'utf-8').'...' : strip_tags($val->detail),
                'askid'=>$val->id,
                'subject'=>$val->subject,
                'answered'=>$val->answered,
                'viewed'=>$val->viewed,
                'stared'=>$val->stared,
                'askvote_up'=>empty($val->vote_up)?0:$val->vote_up,
                'askcreated_at'=>Computing::timejudgment($val->created_at),
                'username'=>empty($answersuser->display_name)?'':$username,
                'avatar'=>empty($user->avatar)?'':$user->avatar,
                'tags' =>$tags,
                'askuid'=>$val->uid,
                'answeruserstatus'=>empty($answeruserstatus)?'':$answeruserstatus,
            ];
        }
        if(!empty($askList)){
            $this->info = $askList;
        }else{
            $this->status = 404;
            $this->description = "没有问题了！";
            $this->accepted = false;
        }


    }
    /**
     * 问题详情
     *
     * @return array
     */
    private function detail()
    {
        $this->detailvalidate();
        if($this->passes()){
            $detailinfo = [];
            //查询问题
            $ask=DB::table('questions')
                ->select('id','detail','subject','uid','answered','viewed','stared','vote_up','created_at')
                ->where([['id',$this->input['askid']],['status','=',0],['created_at','<=',date('y-m-d H:i:s',time())]])
                ->first();
            if(empty($ask)){
                $this->status = 404;
                $this->description = "文章未找到!";
                $this->accepted = false;
            }
            if($this->passes()){
                //浏览数+1
                DB::table('questions')->where([['id',$this->input['askid']],['status','=',0]])->increment('viewed', 1);
                //查询标签
                $tagid=DB::table('question_tags')
                    ->where('question_id',$ask->id)
                    ->lists('tag_id');
                //查询相关问题ID
                $askids=DB::table('question_tags')
                    ->whereIn('tag_id',$tagid)
                    ->lists('question_id');
                //查询相关问题
                $askarray=[$ask->id];
                //去除本篇文章的ＩＤ
                $askid = array_diff($askids,$askarray);
                $relatedask =DB::table('questions')
                    ->select('id','subject','uid','answered','created_at')
                    ->orderBy('answered','created_at','desc')
                    ->where('status','=',0)
                    ->whereIn('id',$askid)
                    ->take(5)
                    ->get();
                foreach ($relatedask as $related){
                    $related->subject = mb_strlen($related->subject, 'utf-8') > 25 ? mb_substr($related->subject, 0, 25, 'utf-8').'...' : $related->subject;
                }
                //问题标签
                $tags=DB::table('tags')
                    ->select('id','name','tagged_answers')
                    ->whereIn('id',$tagid)
                    ->get();
                //查询问题的用户信息
                $user=$this->userInfo($ask->uid);
                //登录用户是否关注
                $stared = empty($this->input['uid'])?'':DB::table('question_stars')
                    ->select('uid','question_id')
                    ->where([['uid','=',$this->input['uid']],['question_id','=',$ask->id]])
                    ->get();
                //判断用户是否是问题的发布者 1是 2不是
                if($this->input['uid']==$ask->uid){
                    $askuserstatus = 1;
                }else{
                    //判断此用户是否回答过此问题
                    $answeruserstatus = DB::table('answers')
                        ->select('id')
                        ->where([['uid','=',$this->input['uid']],['question_id','=',$ask->id]])
                        ->first();
                    $askuserstatus = 2;
                }
                //网站问题统计and用户统计
                $usercount=DB::table('users')
                    ->where('disabled','=',0)
                    ->count('id');
                $questioncount=DB::table('questions')
                    ->where([['status','=',0],['created_at','<',date('y-m-d H:i:s',time())]])
                    ->count('id');
                //已经邀请的
                $invitedid =DB::table('question_invitations')->where('question_id','=',$ask->id)->lists('invited');
                //已经邀请的
                empty($invitedid)?$invited=[]:$invited=DB::table('users')
                    ->select('id','avatar')
                    ->whereIn('id',$invitedid)
                    ->get();
                //基本信息录用
                $detailinfo=[
                    'invited' => $invited,
                    'askdetail'=>$ask->detail,
                    'subject'=>$ask->subject,
                    'answered'=>$ask->answered,
                    'viewed'=>$ask->viewed,
                    'stared'=>$ask->stared,
                    'askvote_up'=>empty($ask->vote_up)?0:$ask->vote_up,
                    'askcreated_at'=>Computing::timejudgment($ask->created_at),
                    'askuid'=>$ask->uid,
                    'askid'=>$ask->id,
                    'username'=>empty($user->display_name)?'':mb_strlen($user->display_name, 'utf-8') > 10 ? mb_substr($user->display_name, 0, 10, 'utf-8').'...' : $user->display_name,
                    'avatar'=>empty($user->avatar)?'':$user->avatar,
                    'uid'=>empty($user->id)?'':$user->id,
                    'tags' =>$tags,
                    'relatedask'=>$relatedask,
                    'staredstatus'=>empty($stared)?1:2,
                    'askuserstatus'=>empty($askuserstatus)?"":$askuserstatus,
                    'questioncount'=>$questioncount,
                    'usercount'=>$usercount,
                    'answeruserstatus'=>!empty($answeruserstatus)?1:2,
                ];
                $this->info = $detailinfo;
            }
        }
    }
    /**
     * Validate the input
     *
     * @param void
     *
     * @return void
     */
    private function detailvalidate()
    {
        $rules = [
            'askid' => 'required|numeric',
        ];
        $messages = [
            'askid.required' => '参数错误',
            'askid.numeric' => '参数错误',
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '请输入信息';
            $this->accepted = false;

            $messages = $validator->errors();

            if ($messages->has('askid')) {
                $this->errors->add('askid', $messages->first('askid'));
            }
        }
    }
    /**
 * 问答参数校检
 *
 * @param void
 *
 * @return void
 */
    private function answersvalidate()
    {
        $rules = [
            'ask_id' => 'required|numeric',
            'editor' =>  'required',
        ];
        $messages = [
            'ask_id.required' => '参数错误',
            'ask_id.numeric' => '参数错误',
            'editor.required' => '内容不能为空',
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '请输入信息';
            $this->accepted = false;

            $messages = $validator->errors();

            if ($messages->has('ask_id')) {
                $this->errors->add('ask_id', $messages->first('ask_id'));
            }
            if ($messages->has('editor')) {
                $this->errors->add('editor', $messages->first('editor'));
            }
        }
    }
    /**
     * 回复参数校检
     *
     * @param void
     *
     * @return void
     */
    private function repliesvalidate()
    {
        $rules = [
            'commented_id' => 'required|numeric',
            'contetn' =>  'required',
        ];
        $messages = [
            'commented_id.required' => '参数错误',
            'commented_id.numeric' => '参数错误',
            'content.required' => '回复不能为空',
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '请输入信息';
            $this->accepted = false;

            $messages = $validator->errors();

            if ($messages->has('commented_id')) {
                $this->errors->add('commented_id', $messages->first('commented_id'));
            }
            if ($messages->has('content')) {
                $this->errors->add('content', $messages->first('content'));
            }
        }
    }
    /**
     * 问答编辑参数校检
     *
     * @param void
     *
     * @return void
     */
    private function answereupvalidate()
    {
        $rules = [
            'answer_id' => 'required',
            'detail' =>  'required',
        ];
        $messages = [
            'answer_id.required' => '参数错误',
            'detail.required' => '参数不能为空',
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '请输入信息';
            $this->accepted = false;

            $messages = $validator->errors();

            if ($messages->has('answer_id')) {
                $this->errors->add('answer_id', $messages->first('answer_id'));
            }
            if ($messages->has('detail')) {
                $this->errors->add('detail', $messages->first('detail'));
            }
        }
    }
    /**
     * 问答添加参数校检
     *
     * @param void
     *
     * @return void
     */
    private function answerevalidate()
    {
        $rules = [
            'ask_id' => 'required',
            'editor' =>  'required',
            'uid' =>  'required',
        ];
        $messages = [
            'ask_id.required' => '参数错误',
            'editor.required' => '参数不能为空',
            'uid.required' => '请登录',
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '请输入信息';
            $this->accepted = false;

            $messages = $validator->errors();

            if ($messages->has('ask_id')) {
                $this->errors->add('ask_id', $messages->first('ask_id'));
            }
            if ($messages->has('editor')) {
                $this->errors->add('editor', $messages->first('editor'));
            }
            if ($messages->has('uid')) {
                $this->errors->add('uid', $messages->first('uid'));
            }
        }
    }
    /**
     * 评论参数校检
     *
     * @param void
     *
     * @return void
     */
    private function commentedvalidate()
    {
        $rules = [
            'answer_id' => 'required',
            'content' =>  'required',
        ];
        $messages = [
            'answer_id.required' => '参数错误',
            'content.required' => '评论不能为空',
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '请输入信息';
            $this->accepted = false;

            $messages = $validator->errors();

            if ($messages->has('answer_id')) {
                $this->errors->add('answer_id', $messages->first('answer_id'));
            }
            if ($messages->has('content')) {
                $this->errors->add('content', $messages->first('content'));
            }
        }
    }
    /**
     * Validate the input
     *
     * @param void
     *
     * @return void
     */
    private function answerslist()
    {
        //查询答案 answer_id
        $where=' and 1=1 ';
        if(!empty($this->input['answer_id'])){
            $answersid = null;
           foreach (explode(",",$this->input['answer_id']) as $val){
               $answersid.= "'".$val."',";

           }
            $answersid = substr($answersid,0,-1);
            $where = " and id not in(".$answersid.") ";
        }
        //$pageSize = 10;
        //$page = empty($this->input['page'])?1:$this->input['page'];
        //$pageStart = ($page - 1) * $pageSize ;
        //limit ".$pageStart.",".$pageSize."
        $answers=DB::select("select o.id,o.uid,o.question_id,o.detail,o.commented,o.updated_at,o.vote_up from  (select id from answers where question_id = ".$this->input['askid'].$where." ORDER BY vote_up desc,updated_at desc ) as i join answers as o on o.id = i.id");
        $answersList = [];

        foreach ($answers as $val){
            //判断用户是否点过赞
            if($this->input['uid'] != $val->uid){
                $answervote = DB::table('answer_votes')
                    ->select('id')
                    ->where([['uid','=',$this->input['uid']],['answers_id','=',$val->id]])
                    ->first();
                $votestatus = empty($answervote)?1:2;
            }else{
                $votestatus =2;
            }
            //查询回答的的用户信息
            $user=$this->userInfo($val->uid);
            //判断用户是否是问题的发布者
            if(!empty($this->input['uid'])){
                $answersuserstatus = $this->input['uid']==$val->uid?1:"";
            }
            //问答信息列表
            $answersList[]= [
                'detail'=>empty($val->detail)?'':$val->detail,
                'vote_up'=>empty($val->vote_up)?0:$val->vote_up,
                'commented'=>empty($val->commented)?0:$val->commented,
                'created_at'=>empty($val->updated_at)?0:Computing::timejudgment($val->updated_at),
                'answersuid'=>empty($val->uid)?'':$val->uid,
                'answers_id'=>empty($val->id)?'':$val->id,
                'answersname'=>empty($user->display_name)?"":$user->display_name,
                'answersavatar'=>empty($user->avatar)?"":$user->avatar,
                'uid'=>empty($user->id)?"":$user->id,
                'corporate'=>empty($user->userstatus['corporate'])?"":$user->userstatus['corporate'],
                'position'=>empty($user->userstatus['position'])?"":$user->userstatus['position'],
                'answersuserstatus' => empty($answersuserstatus)?"":$answersuserstatus,
                'votestatus'=>$votestatus,
                //'page'=>$page,
            ];
        }
        if (empty($answersList)){
            $this->status = 203;
            $this->description = "没有数据了!";
            $this->accepted = false;
        }else{
            $this->info = $answersList;
        }
    }
    /**
     * 点赞
     *
     * @return array
     */
    private function voteup()
    {
        //查询用户是否点过赞
        $voteup=DB::table('answer_votes')
            ->select('id','uid','answers_id')
            ->where([['uid','=',$this->input['uid']],['answers_id','=',$this->input['answers_id']]])
            ->get();
        //查询是不本用户的
        $answers=DB::table('answers')
            ->select('id','uid')
            ->where([['uid','=',$this->input['uid']],['id','=',$this->input['answers_id']]])
            ->get();
        if(!empty($voteup)){
            $this->status = 402;
            $this->description = "已经点过赞了！";
            $this->accepted = false;
        }
        if(!empty($answers)){
            $this->status = 402;
            $this->description = "不能给自己点赞！";
            $this->accepted = false;
        }

        if($this->passes()){
            //新增用户赞了那个回答
            $addanswers=DB::table('answer_votes')->insert(
                ['uid' => $this->input['uid'], 'answers_id' => $this->input['answers_id'],'up_down'=>$this->input['up'],'created_at'=>date('y-m-d H:i:s',time()),'updated_at'=>date('y-m-d H:i:s',time())]
            );
            //更新回答表里的赞
            $upanswers = DB::table('answers')->where('id',$this->input['answers_id'])->increment('vote_up', 1);
            //更新用户统计数据
            if(!empty(DB::table('user_analysis')->where('uid',$this->input['answersuid'])->first())){
                DB::table('user_analysis')->where('uid',$this->input['answersuid'])->increment('reputation', 1);
            }
            //更新问题里面的总得赞数
            DB::table('questions')->where([['id',$this->input['ask_id']],['status','=',0]])->increment('vote_up', 1);
            //查询现在的赞数
            $vote_up = DB::table('answers')->select('vote_up')->where('id',$this->input['answers_id'])->get();
            if($addanswers && $upanswers && ! empty($vote_up)){
                Notification::sendNotify($this->input['uid'],$this->input['answersuid'],3,26,$this->input['answers_id']);
                $this->info = [
                    'vote_up'=>$vote_up[0]->vote_up,
                ];
            }else{
                Log::error("赞失败,用户ID为 ".$this->input['uid']."，回答ID为 ".$this->input['answers_id']."");
                $this->status = 404;
                $this->description = "未找到该回答";
                $this->accepted = false;
            }
        }
    }
    /**
     * 关注
     *
     * @return array
     */
    private function stared()
    {
        //查询用户是否关注
        $stared=DB::table('question_stars')
            ->select('id','uid','question_id')
            ->where([['uid','=',$this->input['uid']],['question_id','=',$this->input['ask_id']]])
            ->get();

        if(empty($stared)){
            //新增关注
            $addstared=DB::table('question_stars')->insert(
                ['uid' => $this->input['uid'], 'question_id' => $this->input['ask_id'],'created_at'=>date('y-m-d H:i:s',time()),'updated_at'=>date('y-m-d H:i:s',time())]
            );
            //更新问题里面的总的关注数
            $upstared=DB::table('questions')->where([['id',$this->input['ask_id']],['status','=',0]])->increment('stared', 1);
            //查询现在的关注数
            $stared = DB::table('questions')->select('stared')->where([['id',$this->input['ask_id']],['status','=',0]])->get();
            //判断是否成功执行
            if($addstared && $upstared && ! empty($stared)){
                $this->info = [
                    'status'=>'取消',
                    'stared'=>$stared[0]->stared,
                ];
            }else{
                Log::error("关注失败,用户ID为 ".$this->input['uid']."，问题ID为 ".$this->input['ask_id']."");

                $this->status = 404;
                $this->description = "未找到该问题";
                $this->accepted = false;
            }
        }else{
            //取消关注
            $delstared=DB::table('question_stars')->where([['uid','=',$this->input['uid']],['question_id','=',$this->input['ask_id']]])->delete();
            //更新问题里面的总的关注数
            $upstared=DB::table('questions')->where([['id',$this->input['ask_id']],['status','=',0]])->increment('stared', -1);
            //查询现在的关注数
            $stared = DB::table('questions')->select('stared')->where([['id',$this->input['ask_id']],['status','=',0]])->get();
            //判断是否成功执行
            if($delstared && $upstared && ! empty($stared)){
                $this->info = [
                    'status'=>'关注',
                    'stared'=>$stared[0]->stared,
                ];
            }else{
                Log::error("取消关注失败,用户ID为 ".$this->input['uid']."，问题ID为 ".$this->input['ask_id']."");
                $this->status = 404;
                $this->description = "未找到该问题";
                $this->accepted = false;
            }
        }
    }
    /**
     * 添加回答
     *
     * @return array
     */
    private function answers()
    {
        $this->answerevalidate();
        //新增回答
        if($this->input['uid']==$this->input['askuid']){
            $this->status = 400;
            $this->description = "不能回答自己的问题！";
            $this->accepted = false;
        }
        if(!empty(DB::table('answers')
            ->select('id')
            ->where([['question_id',$this->input['ask_id']],['uid','=',$this->input['uid']]])
            ->first())){
            $this->status = 400;
            $this->description = "只能回答一次哦！可以编辑你的回答！";
            $this->accepted = false;
        }
        if($this->passes()){
            $answer = new Answers();
            $answer->question_id = $this->input['ask_id'];
            $answer->uid = $this->input['uid'];
            $answer->detail = $this->input['editor'];
            $answer->save();
            if(!$answer){
                Log::error("添加回答失败,用户ID为 ".$this->input['uid']."，问题ID为 ".$this->input['ask_id']."");
                $this->status = 404;
                $this->description = "添加回答失败";
                $this->accepted = false;
            }
            if($this->passes()){
                DB::table('questions')->where([['id',$this->input['ask_id']],['status','=',0]])->increment('answered', 1);
                $questions=DB::table('questions')
                    ->select('answered')
                    ->where([['id',$this->input['ask_id']],['status','=',0]])
                    ->first();
                $answers = DB::table('answers')->select('id','uid','question_id','detail','vote_up','commented','updated_at')->where('id',$answer->id)->get();
                //更新用户统计数据
                if(!empty(DB::table('user_analysis')->where('uid',$this->input['uid'])->first())){
                    DB::table('user_analysis')->where('uid',$this->input['uid'])->increment('answer', 1);
                }
                $user = $this->userInfo($answers[0]->uid);
                if(!empty($this->input['uid'])){
                    $answersuserstatus = $this->input['uid']==$answers[0]->uid?1:2;
                }
                //问答信息列表
                $answersList= [
                    'detail'=>$answers[0]->detail,
                    'vote_up'=>$answers[0]->vote_up,
                    'commented'=>$answers[0]->commented,
                    'created_at'=>Computing::timejudgment($answers[0]->updated_at),
                    'answersuid'=>$answers[0]->uid,
                    'answers_id'=>$answers[0]->id,
                    'answered'=>$questions->answered,
                    'answersname'=>$user->display_name,
                    'answersavatar'=>$user->avatar,
                    'corporate'=>$user->userstatus['corporate'],
                    'position'=>$user->userstatus['position'],
                    'commenteddetail'=>empty($commented)?'':$commented,
                    'answersuserstatus'=>$answersuserstatus,
                    'page'=>1,
                ];
                //给关注此问题的人发通知
                $starednum = DB::table('question_stars')
                    ->select('uid')
                    ->where('question_id',$this->input['ask_id'])
                    ->get();
                if(!empty($starednum)){
                    foreach ($starednum as $val){
                        //回答自己的问题不发通知
                        if($this->input['askuid']!=$val->uid && $this->input['uid']!=$val->uid){
                            Notification::sendNotify($this->input['uid'],$val->uid,3,24,$this->input['ask_id']);
                        }
                    }
                }
                Notification::sendNotify($this->input['uid'],$this->input['askuid'],3,21,$this->input['ask_id']);
                $this->info=$answersList;
            }
        }
    }

    /**
     * 添加评论
     *
     * @return array
     */
    private function commented()
    {
        $this->commentedvalidate();
        if($this->passes()){
            //新增评论
            $answerscomments = new AnswerComments();
            $answerscomments->answer_id = $this->input['answer_id'];
            $answerscomments->uid = $this->input['uid'];
            $answerscomments->content = $this->input['content'];
            $answerscomments->save();
            if(!$answerscomments){
                $this->status = 500;
                $this->description = "";
                $this->accepted = false;
            }
            DB::table('answers')->where('id',$this->input['answer_id'])->increment('commented',1);
            //查询新增信息
            $commented=DB::table('answer_comments')
            ->select('id','content','uid','answer_id','created_at')
            ->where('id','=',$answerscomments->id)
            ->first();
            //查询评论数
            $commentednum=DB::table('answers')
            ->select('commented')
            ->where('id','=',$commented->answer_id)
            ->first();
            $user = $this->userInfo($commented->uid);
            //问答信息列表
            $commentedlist = [
                'comment_id'=>$commented->id,
                'comment_uid'=>$commented->uid,
                'answer_id'=>$commented->answer_id,
                'content'=>$commented->content,
                'created_at'=>Computing::timejudgment($commented->created_at),
                'username'=>empty($user->display_name)?'':$user->display_name,
                'avatar'=>empty($user->avatar)?'':$user->avatar,
                'commented'=>$commentednum->commented,
                //'pageInfo'=>$pageInfo,
            ];
            if($this->input['uid']!=$this->input['answers_uid']){
                Notification::sendNotify($this->input['uid'],$this->input['answers_uid'],3,27,$this->input['answer_id']);
            }
            $this->info = $commentedlist;
        }

    }
    /**
     *问题的删除
     *
     * @return array
     */
    private function askdel()
    {
        //删除问题
        $answers = DB::table('answers')->where('question_id','=',$this->input['question_id'])->get();
        if(empty($answers[0])){
            $askdel=DB::table('questions')->where([['uid','=',$this->input['uid']],['id','=',$this->input['question_id']],['status','=',0]])->delete();
            if(!$askdel){
                Log::error("删除问题失败,用户ID为 ".$this->input['uid']."，问题ID为 ".$this->input['question_id']."");
                $this->status = 500;
                $this->description = "服务器错误";
                $this->accepted = false;
            }else{
                //删除通知
                $notificationsdel=DB::table('notifications')
                    ->where([['associate_id','=',$this->input['question_id']],['type','=',3]])
                    ->delete();
                if(!empty(DB::table('user_analysis')->where('uid',$this->input['uid'])->first())){
                    DB::table('user_analysis')->where('uid',$this->input['uid'])->increment('question', -1);
                }
            }
        }else{
            $this->status = 402;
            $this->description = "该问题有回答，请联系管理员进行删除";
            $this->accepted = false;
        }
    }

    /**
     * 删除回答
     *
     * @return array
     */
    private function answeredel()
    {
        //查询是否有此条评论
        $answersarr=DB::table('answers')
            ->where([['uid','=',$this->input['uid']],['id','=',$this->input['answer_id']]])
            ->first();
        if(empty($answersarr)){
            $this->status = 404;
            $this->description = "回答不存在";
            $this->accepted = false;
        }
        if($this->passes()){
        //删除回答
            $answers = DB::table('answer_comments')
                ->where([['uid','=',$this->input['uid']],['answer_id','=',$this->input['answer_id']]])
                ->first();
            if(empty($answers) && $answersarr->vote_up < 1){
                $answeredel=DB::table('answers')
                    ->where('id','=',$this->input['answer_id'])
                    ->delete();
                $question=DB::table('questions')
                    ->select('answered')
                    ->where([['id',$this->input['ask_id']],['status','=',0]])
                    ->first();
                $answered = $question->answered;
                if(!$answeredel){
                    Log::error("删除回答失败,用户ID为 ".$this->input['uid']."，回答ID为 ".$this->input['answer_id']."");

                    $this->status = 500;
                    $this->description = "服务器错误，删除失败";
                    $this->accepted = false;
                }else{
                    //删除通知
                    $notificationsdel=DB::table('notifications')
                        ->where([['associate_id','=',$this->input['answer_id']],['type','=',3]])
                        ->whereIn('show_type', [26, 27])
                        ->delete();
                    if($question->answered>=1){
                        DB::table('questions')
                            ->where([['id',$this->input['ask_id']],['status','=',0]])
                            ->increment('answered', -1);
                        $answered =  $answered-1;
                    }
                }
                $this->info = [
                    'answered'=>$answered
                ];

            }else{
                $this->status = 402;
                $this->description = "该回答有评论或者点赞，请联系管理员进行删除";
                $this->accepted = false;
            }
        }
    }

    /**
     * 编辑问题
     *
     * @return array
     */
    private function askselect()
    {
        //查询问题
        $answers = DB::table('questions')->where([['uid','=',$this->input['uid']],['id','=',$this->input['question_id']],['status','=',0]])->first();

        if(!empty($answers)){
            //查询问题下的标签
            $tagid=DB::table('question_tags')
                ->where('question_id',$answers->id)
                ->lists('tag_id');
            //问题标签
            $tags=DB::table('tags')
                ->select('id','name','tagged_answers')
                ->whereIn('id',$tagid)
                ->get();
            $tagtostr = "";
            if($tags !== null){
                foreach($tags as $tag){
                    $tagtostr .= $tag->name.",";
                }
            }
            $answers->tags = empty($tags)?"":rtrim($tagtostr,',');
            $this->info = $answers;

        }else{
            $this->status = 500;
            $this->description = "该问题不存在，或者你没有这个权限编辑该问题！";
            $this->accepted = false;
        }
    }
    /**
     * 回答编辑
     *
     * @return array
     */
    private function answereup()
    {
        $this->answereupvalidate();
        if($this->passes()){
            $answereup = DB::table('answers')
                ->where([['uid','=',$this->input['uid']],['id','=',$this->input['answer_id']]])
                ->update(['detail' => $this->input['detail'],'updated_at'=> date('y-m-d h:i:s', time())]);
            //新增关注
            if(!$answereup){
                Log::error("编辑回答失败,用户ID为 ".$this->input['uid']."，回答ID为 ".$this->input['answer_id']."");

                $this->status = 500;
                $this->description = "你没有权限！";
                $this->accepted = false;
            }else{
                $answere = DB::table('answers')
                    ->select('detail')
                    ->where('id','=',$this->input['answer_id'])
                    ->first();
                $this->info= [
                    'detail'=>$answere->detail,
                ];
            }
        }
    }
    /**
     *问题编辑
     *
     * @return array
     */
    private function askedit()
    {
        $this->validate();
        $questionyz= DB::table('questions')
            ->where([['subject','=',$this->input['subject']],['id','!=',$this->input['question_id']],['status','=',0]])
            ->first();
        $tagNames = explode(';', $this->input['tags']);
        if(!empty($questionyz)){
            $this->accepted = false;
            $this->status = 402;
            $this->description = '标题重复';
        }
        if (5 < count($tagNames)) {
            $this->accepted = false;
            $this->status = 400;
            $this->description = '不得超过5个标签';
        }
        if($this->passes()){
            
            $answereup = DB::table('questions')
                ->where([['uid','=',$this->input['uid']],['id','=',$this->input['question_id']],['status','=',0]])
                ->update(['subject' => trim($this->input['subject']),'detail' => $this->input['detail']]);
            //添加修改的标签ID

            $tagIds = Tag::getTagsId($tagNames, $this->input['uid']);

            $existingTagIds = DB::table('question_tags')->where('question_id','=', $this->input['question_id'])->lists('tag_id');

            $decreaseTagIds = array_diff($existingTagIds, $tagIds);
            $increaseTagIds = array_diff($tagIds, $existingTagIds);
            $datetime = date('Y-m-d H:i:s');

            if( false === DB::table('question_tags')->where('question_id',$this->input['question_id'])->delete()){
                Log::error("编辑问题失败,用户ID为 ".$this->input['uid']."，问题ID为 ".$this->input['question_id']."");

                $this->accepted = false;
                $this->status = 500;
                $this->description = '发生一个内部错误';
            }

            foreach ($tagIds as $value) {

                $addanswers = DB::table('question_tags')->insert(
                    ['question_id' => $this->input['question_id'], 'tag_id' => $value, 'created_at' => date('y-m-d h:i:s', time()), 'updated_at' => date('y-m-d h:i:s', time())]
                );

            }
            $connection = new Connection();

            $connection->setParams(['host' => env('SPHINX_HOST'), 'port' => env('SPHINX_PORT')]);

            $ret = SphinxQL::create($connection)->query("select * from questions where id=" . $this->input['question_id'])->execute();

            if(!empty($ret)){
                $this->input['subject'] = addslashes($this->input['subject']);
                $sq = SphinxQL::create($connection)->replace()->into('questions');
                $sq->value('id', $this->input['question_id'])->value('subject', trim($this->input['subject']));
                $sq->execute();
            }else{
                $this->input['subject'] = addslashes($this->input['subject']);
                SphinxQL::create($connection)->query("insert into questions values ({$this->input['question_id']},'".trim($this->input['subject'])."')")->execute();
            }
            //添加标签
            if (! empty($decreaseTagIds)) {
                DB::table('tags')->whereIn('id', $decreaseTagIds)->decrement('tagged_answers', 1);
            }

            if (! empty($increaseTagIds)) {
                DB::table('tags')->whereIn('id', $increaseTagIds)->increment('tagged_answers', 1);
            }

            if (! $addanswers) {
                Log::error("编辑标签或者更新spihnx失败,用户ID为 ".$this->input['uid']."，问题ID为 ".$this->input['question_id']."");

                $this->accepted = false;

                $this->status = 500;

                $this->description = '发生一个内部错误, 标签未添加';

            }else{

                $this->info = $questionres =[
                    'question_id'=>$this->input['question_id'],
                    'subject'=>$this->input['subject'],
                ];
            }
        }
    }
    /**
     * 评论删除
     *
     * @return array
     */
    private function commenteddel()
    {
        $commented=DB::table('answer_comments')
            ->where([['uid','=',$this->input['uid']],['id','=',$this->input['comment_id']]])
            ->first();
        if(empty($commented)){
            $this->status = 404;
            $this->description = "评论不存在";
            $this->accepted = false;
        }
        if($this->passes()){
            //删除评论
            $commenteddel=DB::table('answer_comments')->where([['uid','=',$this->input['uid']],['id','=',$this->input['comment_id']]])->delete();

            $answers=DB::table('answers')
                ->select('commented')
                ->where('id',$this->input['answer_id'])
                ->first();
            $commented = $answers->commented;
            if(!$commenteddel){
                Log::error("删除问题评论失败,用户ID为 ".$this->input['uid']."，评论ID为 ".$this->input['comment_id']."");
                $this->status = 500;
                $this->description = "服务器错误";
                $this->accepted = false;
            }else{
                //删除通知
                $notificationsdel=DB::table('notifications')
                    ->where([['associate_id','=',$this->input['comment_id']],['type','=',3],['show_type', '=',28]])
                    ->delete();
                if($answers->commented>=1){
                    //更新问题里面的总的评论数
                    $upcommented=DB::table('answers')->where('id',$this->input['answer_id'])->decrement('commented', 1);
                    $commented = intval($commented)-1;
                }
            }
            $this->info = [
                'commented'=>$commented
            ];
        }
    }
    /**
     * 用户信息
     *
     * @return array
     */
    private function userInfo($uid)
    {
        $user=DB::table('users')
            ->select('id','display_name','avatar','occupation')
            ->where([['id','=',$uid],['disabled','=',0]])
            ->first();
        //查询是学生还是在职员工并获取信息 1: 学生 2：在职员工
        if(!empty($user)){
            if($user->occupation==1){
                $userstatus=DB::table('user_educations')
                    ->select('uid','school','department')
                    ->where('uid',$uid)
                    ->first();
            }elseif($user->occupation==2){
                $userstatus=DB::table('user_works')
                    ->select('uid','company','position')
                    ->where('uid',$uid)
                    ->first();
            }
            if(!empty($userstatus)){
                $user->userstatus =[
                    'corporate'=>empty($userstatus->school)?$userstatus->company:$userstatus->school,
                    'position'=>empty($userstatus->department)?$userstatus->position:$userstatus->department,
                ];
            }else{
                $user->userstatus = [
                    'corporate'=>'',
                    'position'=>'',
                ];
            }
            return $user;
        }
    }
    /**
     * 用户名片信息
     *
     * @return array
     */
    private function card()
    {
        $user=DB::table('users')
            ->select('id','display_name','avatar','occupation')
            ->where([['id','=',$this->input['carduid']],['disabled','=',0]])
            ->first();
        //判断用户是否存在
        if(empty($user)){
            $this->status = 404;
            $this->description = "用户不存在，或者已被禁用！";
            $this->accepted = false;
        }
       if($this->passes()){
           $usertared=DB::table('user_following')
               ->select('uid','following','created_at')
               ->where([['uid','=',$this->input['uid']],['following','=',$user->id]])
               ->first();

           //1未关注，2 已关注
           $isusertared = empty($usertared)?1:2;
           //1为当前用户，2为不是当前用户
           $isstared = ($user->id==$this->input['uid'])?1:2;
           if(empty($user)){
               $this->status = 500;
               $this->description = "用户不存在！";
               $this->accepted = false;
           }
           if($this->passes()){
               //查询是学生还是在职员工并获取信息 1: 学生 2：在职员工
               if($user->occupation==1){
                   $userstatus=DB::table('user_educations')
                       ->select('uid','school','department')
                       ->where('uid',$user->id)
                       ->first();
               }elseif($user->occupation==2){
                   $userstatus=DB::table('user_works')
                       ->select('uid','company','position')
                       ->where('uid',$user->id)
                       ->first();
               }
               //输送信息
               if(!empty($userstatus)){
                   $user->userstatus =[
                       'corporate'=>empty($userstatus->school)?$userstatus->company:$userstatus->school,
                       'position'=>empty($userstatus->department)?$userstatus->position:$userstatus->department,
                   ];
               }else{
                   $user->userstatus = [
                       'corporate'=>'',
                       'position'=>'',
                   ];
               }
               $user->isusertared=$isusertared;
               $user->isstared=$isstared;
               $this->info = $user;
           }
       }

    }
    /**
     * 评论列表
     *
     * @return array
     */
    private function commentedlist()
    {
        $commentedlist=[];
            $commented=DB::select("select o.id,o.uid,o.answer_id,o.content,o.created_at from  (select id from answer_comments where answer_id = ".$this->input['answer_id']." ORDER BY created_at ) as i join answer_comments as o on o.id = i.id");
        foreach ($commented as $key => $val){
            $user = $this->userInfo($val->uid);
            //判断此条评论是否是此用户的 1 是 2 不是
            $commentedstatus =  $this->input['uid'] == $val->uid?1:2;
            //问答信息列表
            $commentedlist[]= [
                'comment_id'=>$val->id,
                'comment_uid'=>$val->uid,
                'answer_id'=>$val->answer_id,
                'content'=>$val->content,
                'created_at'=>Computing::timejudgment($val->created_at),
                'username'=>empty($user->display_name)?'':$user->display_name,
                'avatar'=>empty($user->avatar)?'':$user->avatar,
                'commentedstatus'=>$commentedstatus,
            ];
        }
        $this->info = $commentedlist;
    }
    /**
     * 邀请
     *
     * @return array
     */
    private function invitations()
    {
        $pageSize = 5;
        $page = empty($this->input['page'])?1:$this->input['page'];
        $pageStart = ($page - 1) * $pageSize ;
        //邀请人回答 检索标签ID
        $a=DB::table('question_tags')
            ->where('question_id',$this->input['question_id'])
            ->lists('tag_id');
        //去除重复
        $a = array_flip(array_flip($a));
        //查询该标签下所有的问题id
        if(!empty($a)){
            $b=DB::table('question_tags')
                ->whereIn('tag_id',$a)
                ->lists('question_id');

            $b = array_flip(array_flip($b));
            $question=DB::table('questions')
                ->select('uid')
                ->where('id','=',$this->input['question_id'])
                ->first('uid');
            //查询用户
            $answers=DB::table('answers')
                ->where('uid','<>',$question->uid)
                ->whereIn('question_id',$b)
                ->lists('uid');
            $questions=DB::table('questions')
                ->where([['status','=',0],['uid','<>',$question->uid]])
                ->whereIn('id',$b)
                ->lists('uid');
            $invitations =DB::table('question_invitations')->where('question_id','=',$this->input['question_id'])->lists('invited');
            $id=array_merge($questions,$answers);
            $id = array_flip(array_flip($id));
            $result=array_diff($id,$invitations);
            if(! empty($result)){
                $result = implode(',', $result);
            }

            empty ($result) ?$where = "" : $where = "users.id in (".$result.")  ";
            //查询用户信息
            if(!empty($where)){
                $e=DB::select("select o.id,o.display_name,o.avatar,o.occupation,o.created_at from  (select users.id from users join user_analysis as u on users.id = u.uid where  ".$where."  and users.id <> ". $question->uid." and  users.display_name IS NOT NULL AND users.display_name != '' order by u.activity desc  limit ".$pageStart.",".$pageSize.") as i join users as o on o.id = i.id 
");
                if(count($e) < 5 ){
                    $result=array_merge($questions,$answers,$invitations);
                    $result = array_flip(array_flip($result));
                    if(! empty($result)){
                        $result = implode(',', $result);
                        $result.=','.$this->input['uid'].','. $question->uid;
                    }
                    $pageSize = 5-count($e);
                    $pageStart = ($page - 1) * $pageSize ;
                    //查询剩余的活跃用户
                    $i=DB::select("select o.id,o.display_name,o.avatar,o.occupation,o.created_at from  (select users.id from users join user_analysis as u on u.uid = users.id  where users.id not in  (".$result.")  order by u.online desc  limit ".$pageStart.",".$pageSize.") as i join users as o on o.id = i.id 
");
                    $e = array_merge($i,$e);
                }
            }else{
                $result=array_merge($questions,$answers,$invitations);
                $result = array_flip(array_flip($result));
                if(! empty($result)){
                    $result = implode(',', $result);
                    $result.=','.$question->uid;
                }else{
                    $result = '';
                    $result.=$this->input['uid'].','.$question->uid;
                }
                //查询剩余的活跃用户
                $e=DB::select("select o.id,o.display_name,o.avatar,o.occupation,o.created_at from  (select users.id from users  join user_analysis as u on u.uid = users.id where users.id not in  (".$result.") order by u.activity desc limit ".$pageStart.",".$pageSize." ) as i join users as o on o.id = i.id 
");
            }
            foreach ($e as $key => $user){
                $invitations =DB::table('question_invitations')->where([['invited','=',$user->id],['question_id','=',$this->input['question_id']],['uid','=',$this->input['uid']]])->first();
                if($user->occupation==1){
                    $userstatus=DB::table('user_educations')
                        ->select('uid','school','department')
                        ->where('uid',$user->id)
                        ->first();
                }elseif($user->occupation==2){
                    $userstatus=DB::table('user_works')
                        ->select('uid','company','position')
                        ->where('uid',$user->id)
                        ->first();
                }
                $analysis=DB::table('user_analysis')
                    ->select('question','answer','reputation')
                    ->where('uid',$user->id)
                    ->first();
                //邀请人回答
                if(!empty($userstatus)){
                    $e[$key]->corporate =empty($userstatus->school)?$userstatus->company:$userstatus->school;
                    $e[$key]->position = empty($userstatus->department)?$userstatus->position:$userstatus->department;;
                }else{
                    $e[$key]->corporate ='';
                    $e[$key]->position = '';
                }
                $e[$key]->question = empty($analysis->question)?0:$analysis->question;
                $e[$key]->answer = empty($analysis->answer)?0:$analysis->answer;
                $e[$key]->reputation =empty($analysis->reputation)?0:$analysis->reputation;
                $e[$key]->page = $page;
                //判断用户是否被邀请过，没有被邀请过为1 被邀请过为2
                $e[$key]->invitations = empty($invitations)?1:2;
            }
        }
        if(!empty($e)){
            $this->info=[
                'invitations' => $e
            ];
        }else{
            $this->status = 402;
            $this->description = "没有可以邀请的人了！";
            $this->accepted = false;
        }
    }
    /**
     * 邀请人回答
     *
     * @return array
     */
    private function invitationsadd()
    {
        //邀请人回答
       $invitations =DB::table('question_invitations')->where([['uid','=',$this->input['uid']],['question_id','=',$this->input['question_id']],['invited','=',$this->input['invited']]])->first();
        if(!empty($invitations)){
            $this->status = 402;
            $this->description = "已经邀请";
            $this->accepted = false;
        }
        if($this->passes()){
            //查询用户id
            $questions =DB::table('questions')
                ->select('uid')
                ->where('id','=',$this->input['question_id'])
                ->first();
            if($questions->uid==$this->input['invited']){
                $this->status = 402;
                $this->description = "不能邀请此问题的主人回答问题！";
                $this->accepted = false;
            }
        }
        if($this->passes()){
            $addinvitations=DB::table('question_invitations')
                ->insert(
                ['uid' => $this->input['uid'], 'question_id' => $this->input['question_id'],'invited'=>$this->input['invited'],'created_at'=>date('y-m-d H:i:s',time()),'updated_at'=>date('y-m-d H:i:s',time())]
            );
            if(!$addinvitations){
                Log::error("邀请人回答失败,用户ID为 ".$this->input['uid']."，问题ID为 ".$this->input['question_id']."，被邀请人ID为 ".$this->input['invited']."");

                $this->status = 500;
                $this->description = "邀请失败";
                $this->accepted = false;
            }else{
                Notification::sendNotify($this->input['uid'],$this->input['invited'],3,25,$this->input['question_id']);
                if(!empty(DB::table('user_analysis')->where('uid',$this->input['uid'])->first())){
                    DB::table('user_analysis')->where('uid',$this->input['uid'])->increment('invitation', 1);
                }

                $this->info =[
                    'description'=>"已邀请",
                ];
            }
        }
    }
    /**
     * 检索用户信息
     *
     * @return array
     */
    private function search()
    {
        $str = '';
        foreach ($this->input['data'] as $val){
            $str .= "'".$val['id']."',";
        }
        $str = substr($str,0,-1);
        //查询用户ID
        if($str){
            $users = DB::select("select o.id,o.display_name,o.avatar,o.occupation,o.created_at from  (select id from users where id in ( ".$str.") and  id <> ".$this->input['uid']."   ORDER BY created_at desc ) as i join users as o on o.id = i.id");
        }
        if(!empty($users)){
            foreach ($users as $key => $user){
                //查询用户是否被关注
                $invitations =DB::table('question_invitations')
                    ->where([
                        ['invited','=',$user->id],
                        ['question_id','=',$this->input['question_id']],
                        ['uid','=',$this->input['uid']]
                    ])
                    ->first();
                if($user->occupation==1){
                    $userstatus=DB::table('user_educations')
                        ->select('uid','school','department')
                        ->where('uid',$user->id)
                        ->first();
                }elseif($user->occupation==2){
                    $userstatus=DB::table('user_works')
                        ->select('uid','company','position')
                        ->where('uid',$user->id)
                        ->first();
                }
                //查询用户统计
                $analysis=DB::table('user_analysis')
                    ->select('question','answer','reputation')
                    ->where('uid',$user->id)
                    ->first();
                //基本信息录用
                if(!empty($userstatus)){
                    $users[$key]->corporate =empty($userstatus->school)?$userstatus->company:$userstatus->school;
                    $users[$key]->position = empty($userstatus->department)?$userstatus->position:$userstatus->department;;
                }else{
                    $users[$key]->corporate ='';
                    $users[$key]->position = '';
                }
                $users[$key]->question = empty($analysis->question)?0:$analysis->question;
                $users[$key]->answer = empty($analysis->answer)?0:$analysis->answer;
                $users[$key]->reputation =empty($analysis->reputation)?0:$analysis->reputation;
                //判断用户是否被邀请过，没有被邀请过为1 被邀请过为2
                $users[$key]->invitations = empty($invitations)?1:2;
            }
            $this->info=$users;
        }
    }
    /**
     * Validate the input
     *
     * @param void
     *
     * @return void
     */
    private function pagevalidate()
    {
        $rules = [
            'page' => 'integer',
        ];
        $messages = [
            'page.integer' => '参数错误',
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 404;
            $this->description = '分页参数错误';
            $this->accepted = false;

            $messages = $validator->errors();

            if ($messages->has('page')) {
                $this->errors->add('page', $messages->first('page'));
            }
        }
    }
}
