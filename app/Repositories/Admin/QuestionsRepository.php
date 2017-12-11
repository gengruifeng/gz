<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/1
 * Time: 10:46
 */

namespace App\Repositories\Admin;

use App\Entity\UserProficiencies;
use App\Utils\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Utils\HttpStatus;
use App\Repositories\Repository;
use App\Repositories\RepositoryInterface;
use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;
use Log;


class QuestionsRepository extends Repository implements RepositoryInterface
{

    public $dofunction;

    public $doreturn;

    public $currenpPge = 1;

    public $num = 20;

    public $order = 'created_at';

    public $orderBy = 'DESC';

    public $data = [];

    private $connection;

    public function contract()
    {
        $this->connection = new Connection();
        $this->connection->setParams(['host' => env('SPHINX_HOST'), 'port' => env('SPHINX_PORT')]);
        $funtion = $this->dofunction;
        $this->$funtion();

    }

    /**
     * 返回结果
     * @return array
     */
    public function wrap(){
        $wrapper = [];
        if (!$this->passes()) {
            $wrapper = [
                'error_id' => $this->status,
                'description' => $this->description,
                'error_name' => HttpStatus::$statusTexts[$this->status],
                'errors' => $this->errors->getErrors(),
            ];
        }
        return $wrapper;
    }

    /**
     *获取用户列表
     */
    public function getList(){
        //初始化列表
        $this->_initPram();

        $ret = $this->_where(0);
        if(!empty($ret)){
            $totalret = $this->_where(1);
            $ret = $this->historyData($ret);
            $this->data = [
                'status' =>1,
                'currenpPge' =>$this->currenpPge,
                'totalPage' =>ceil($totalret/$this->num),
                'total' =>$totalret,
                'next' =>$this->currenpPge +1,
                'up' =>$this->currenpPge - 1,
                'data' =>$ret,
            ];
            \Log::info('[admin]Getting the question list data successful - adminid:'.$this->input['adminid']);
        }else{
            $this->data = [
                'status' =>0,
                'currenpPge' =>$this->currenpPge,
                'totalPage' =>0,
                'total' =>0,
                'data' =>$ret,
            ];
            \Log::info('[admin]No problem data - adminid:'.$this->input['adminid']);
        }
    }

    protected function _initPram(){
        $this->currenpPge = !empty($this->input['currenpPge'])?$this->input['currenpPge']:1;
        $this->num = !empty($this->input['num'])?$this->input['num']:20;
        $this->order = !empty($this->input['order'])?$this->input['order']:'created_at';
        $this->orderBy = !empty($this->input['orderBy'])?$this->input['orderBy']:'DESC';
        $this->input['status'] = !empty($this->input['status']) ? $this->input['status'] : 0;
    }

    protected function _where($istotal){
        $db = DB::table('questions')->select('users.display_name','questions.is_hot','questions.id','questions.status','questions.subject','questions.uid','questions.answered','questions.viewed','questions.updated_at','questions.created_at','questions.stared');

        $db->join('users', 'questions.uid', '=', 'users.id');

        if(!empty($this->input['display_name'])){
            $db->where('users.display_name','like','%'.$this->input['display_name'].'%');
        }

        if(!empty($this->input['subject'])){
            $db->where('questions.subject','like','%'.$this->input['subject'].'%');
        }

        if(!empty($this->input['is_hot']) && count($this->input['is_hot']) != 2){
            foreach ($this->input['is_hot'] as $k=>$v){
                $db->where('questions.is_hot', $v);
            }
        }

        if(!empty($this->input['stime'])){
            $db->where('questions.created_at','>=',$this->input['stime']);
        }

        if(!empty($this->input['etime'])){
            $db->where('questions.created_at','<=',$this->input['etime']);
        }

        $db->where('questions.status',$this->input['status']);

        if(!empty($this->input['sanswered'])){
            $db->where('questions.answered','>=',$this->input['sanswered']);
        }

        if(!empty($this->input['eanswered'])){
            $db->where('questions.answered','<=',$this->input['eanswered']);
        }
        if($istotal){
            $ret = $db->count();
        }else{
            $ret = $db->orderBy($this->order, $this->orderBy)->skip(($this->currenpPge - 1) * $this->num)->take($this->num)->get();
        }
        return $ret;
    }

    protected function historyData($ret){

        foreach ($ret as $k =>$v){
            $html = '';
            $res = DB::table('questions_history')->select('questions_history.*','users.display_name')->join('users', 'questions_history.adminid', '=', 'users.id')->where('questions_history.questions_id',$v->id)->get();
            if(!empty($res)){
                foreach ($res as $kk=>$vv){
                    $html .= '<p> '.$vv->display_name.' | '.($vv->type == 1 ?'编辑':'删除').' | '.$vv->created_at.'</p>';
                }
            }else{
                $html .= '<p> 无操作记录</p>';
            }
            $ret[$k]->caozuo = $html;
        }
        return $ret;
    }

    protected function del(){
        
        $this->validadordel();
        if($this->passes()){
            $this->dodel();
            $this->delsphinx();
        }
    }

    protected function validadordel(){
        $rules = [
            'id'=>"required|integer|exists:questions,id",

        ];
        $messages = [
            'id.required'=>'id不能为空',
            'id.integer'=>'id数据类型不正确',
            'id.exists'=>'未知id数据',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('id')) {
                $this->errors->add('id', $messages->first('id'));
            }
        }
    }

    protected function dodel(){
        DB::beginTransaction();

        $uid = DB::table('questions')->select('uid')->where('id',$this->input['id'])->first();

        $ret1 = DB::table('questions')->where('id',$this->input['id'])->update(['status' => 1]);
        //删除问题相关通知
        $questionCount = DB::table('notifications')
            ->where(['type'=>3,'associate_id'=>$this->input['id']])
            ->whereIn('show_type',array(21,22,23,24,25))
            ->count();
        $ret2 = true;
        $ret3 = true;
        $ret4 = true;
        if($questionCount > 0){
            $ret2 = DB::table('notifications')
                ->where(['type'=>3,'associate_id'=>$this->input['id']])
                ->whereIn('show_type',array(21,22,23,24,25))
                ->delete();
            //删除与此问题相关回答通知
            $answerIds = DB::table('answers')
                ->where('question_id',$this->input['id'])
                ->lists('id');
            $answerCount = DB::table('notifications')
                ->where('type',3)
                ->whereIn('show_type',array(26,27))
                ->whereIn('associate_id',$answerIds)
                ->count();
            if($answerCount > 0){
                $ret3 = DB::table('notifications')
                    ->where('type',3)
                    ->whereIn('show_type',array(26,27))
                    ->whereIn('associate_id',$answerIds)
                    ->delete();
                //删除与此问题相关评论通知
                $answerCommentIds = DB::table('answer_comments')
                    ->whereIn('answer_id',$answerIds)
                    ->lists('id');
                $answerCommentCount = DB::table('notifications')
                    ->where('type',3)
                    ->whereIn('show_type',array(28))
                    ->whereIn('associate_id',$answerCommentIds)
                    ->count();
                if($answerCommentCount > 0){
                    $ret4 = DB::table('notifications')
                        ->where('type',3)
                        ->whereIn('show_type',array(28))
                        ->whereIn('associate_id',$answerCommentIds)
                        ->delete();
                }
            }
        }
        $ret5 = DB::table('questions_history')->insert([
            'questions_id' => $this->input['id'],
            'type' => 2,
            'adminid' =>$this->input['adminid'],
            'created_at' =>date('Y-m-d H:i:s',time()),
        ]);
        $content = '您的问题已经被删除';
        $us = Notification::sendNotify($this->input['adminid'],$uid->uid,3,23,$this->input['id'],$content,0);

        $ret = DB::table('user_analysis')->select('question','answer','reputation')->where('uid',$uid->uid)->first();
        $ret6 = true;
        if(!empty($ret) && $ret->question > 0){
            $ret6 = DB::table('user_analysis')->where('uid',$uid->uid)->decrement('question',1);
        }

        $answerUIds = DB::table('answers')->where('question_id',$this->input['id'])->lists('uid');
        $ret7 = true;
        if(!empty($answerUIds)){
            if($ret->answer > 0){
                $ret7 = DB::table('user_analysis')->whereIn('uid',$answerUIds)->decrement('answer',1);
            }
        }

        if(!empty($answerIds)){
            foreach ($answerIds as $kkk=>$vvv){
                $ret8 = true;
                $answerVotesCount = DB::table('answer_votes')
                    ->where('answers_id',$vvv)
                    ->count();
                if(!empty($answerVotesCount)) {
                    $anserUid = DB::table('answers')->select('uid')->where('id', $vvv)->first();
                    if($ret->reputation >=$answerVotesCount){
                        $ret8 = DB::table('user_analysis')->where('uid', $anserUid->uid)->decrement('reputation', $answerVotesCount);
                    }else{
                        DB::table('user_analysis')->where('uid', $anserUid->uid)->update(['reputation' => 0]);
                    }
                    if (!$ret8) {
                        $this->status = 400;
                        $this->description = '参数错误';
                        $this->accepted = false;
                        $this->errors->add('id', '删除失败');
                        DB::rollBack();
                    }
                }
            }

        }
        //tags -1
        $is = DB::table('question_tags')->where('question_id', $this->input['id'])->get();
        if($is){
            foreach ($is as $k=>$v){
                $deTag = DB::table('tags')->where('id',$v->tag_id)->decrement('tagged_answers',1);
                if(!$deTag){
                    $this->status = 400;
                    $this->description = '参数错误';
                    $this->accepted = false;
                    $this->errors->add('id','删除失败');
                    DB::rollBack();
                    return 0;
                }
            }
        }
        if(!$ret1 || !$ret2 || !$ret3 || !$ret4 || !$ret5 || !$ret6 || !$ret7|| $us['status'] != 1){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','删除失败');
            DB::rollBack();
            \Log::error('[admin]Delete the problem,Failed to update the database,problemid is '.$this->input['id'].' - adminid:'.$this->input['adminid']);
        }else{
            DB::commit();
            \Log::info('[admin]Delete the problem,problemid uid is '.$this->input['id'].'- adminid:'.$this->input['adminid']);
        }
    }
    
    public function delsphinx(){
        SphinxQL::create($this->connection)->query('delete from questions where id = '.$this->input['id'])->execute();
    }


    
    public function getQuestionsTags($id){
        $data = [];
        $ret = DB::table('question_tags')->where('question_id',$id)->get();
        if(!empty($ret)){
            foreach ($ret as $k=>$v){
                $data[$v->tag_id] = $v->question_id;
            }
        }
        return $data;
    }

    protected function edit(){
        $this->validadoredit();
        if($this->passes()){
            $this->doedit();
//            $this->delsphinx();
            $this->editsphinx();
        }
    }


    protected function validadoredit(){
        $rules = [
            'subject' => "required|unique:questions,subject,{$this->input['id']}|between:0,50",
            'detail' => 'required',
            'tag' => 'required|array',
        ];
        $messages = [
            'subject.required'=>'标题不能为空',
            'subject.between'=>'标题应为0到50个字符',
            'subject.unique'=>'标题已经存在',
            'detail.required'=>'内容不能为空',
//            'detail.between'=>'内容应为0到1000个字符',
            'tag.required'=>'标签不能为空',
            'tag.array'=>'标签数据格式不正确',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('subject')) {
                $this->errors->add('subject', $messages->first('subject'));
            }

            if ($messages->has('detail')) {
                $this->errors->add('detail', $messages->first('detail'));
            }
            if ($messages->has('tag')) {
                $this->errors->add('tag', $messages->first('tag'));
            }
        }

        if($this->passes()){
            if(count($this->input['tag']) > 5){
                $this->status = 400;
                $this->description = '参数错误';
                $this->accepted = false;
                $this->errors->add('tag', '标签个数不能超过5个');
                return 0;
            }
            foreach ($this->input['tag'] as $k=>$v){
                $ret = DB::table('tags')->where('id', $v)->first();
                if(!$ret){
                    $this->status = 400;
                    $this->description = '参数错误';
                    $this->accepted = false;
                    $this->errors->add('tag', '标签数据不正确');
                    return 0;
                }
            }

        }
    }

    protected function doedit(){
        DB::beginTransaction();

        $ret1 = DB::table('questions')->where('id',$this->input['id'])->update([
            'subject' => $this->input['subject'],
            'detail' => $this->input['detail'],
        ]);

        $ret2 = DB::table('questions_history')->insert([
            'questions_id' => $this->input['id'],
            'type' => 1,
            'adminid' =>$this->input['adminid'],
            'created_at' =>date('Y-m-d H:i:s',time()),
        ]);
        $ret3 = true;
        $is = DB::table('question_tags')->where('question_id', $this->input['id'])->get();
        if($is){
            foreach ($is as $k=>$v){
                $deTag = DB::table('tags')->where('id',$v->tag_id)->decrement('tagged_answers',1);
                if(!$deTag){
                    $this->status = 400;
                    $this->description = '参数错误';
                    $this->accepted = false;
                    $this->errors->add('id','保存失败');
                    DB::rollBack();
                    return 0;
                }
            }
            $ret3 = DB::table('question_tags')->where('question_id', $this->input['id'])->delete();
        }

        foreach ($this->input['tag'] as $k=>$v){
            $ret = DB::table('question_tags')->insert([
                'question_id' => $this->input['id'],
                'tag_id' => $v,
                'created_at' =>date('Y-m-d H:i:s',time()),
                ]);
            $inTag = DB::table('tags')->where('id',$v)->increment('tagged_answers',1);
            if(!$ret || !$inTag){
                $this->status = 400;
                $this->description = '参数错误';
                $this->accepted = false;
                $this->errors->add('id','保存失败');
                DB::rollBack();
                return 0;
            }
        }
        $content = '编辑了问题';
        $uid = DB::table('questions')->select('uid')->where('id',$this->input['id'])->first();
        $us = Notification::sendNotify($this->input['adminid'],$uid->uid,3,22,$this->input['id'],$content,0);
        if(!$ret2 || !$ret3){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','保存失败');
            DB::rollBack();
            \Log::error('[admin]Edit the problem,Failed to update the database,problemid is '.$this->input['id'].' - adminid:'.$this->input['adminid']);
        }else{
            DB::commit();
            \Log::info('[admin]Edit the problem,problemid is '.$this->input['id'].' - adminid:'.$this->input['adminid']);

        }
    }

    public function editsphinx(){
        $id = $this->input['id'];
        $subject =  $this->input['subject'];
        $ret = SphinxQL::create($this->connection)->query("select * from questions where id=".$id)->execute();
        if(!empty($ret)){
            $sq = SphinxQL::create($this->connection)->replace()->into('questions');
            $sq->value('id', $id)->value('subject', addslashes($subject));
            $sq->execute();
        }else{
           SphinxQL::create($this->connection)->query("insert into questions values ({$id},'".addslashes($subject)."')")->execute();

        }
    }

    protected function hot(){
        $this->validadorhot();
        if($this->passes()){
            $this->dohot();
        }
    }


    protected function validadorhot(){
        $rules = [
            'id' => 'required|array',
            'type' => 'required|in:0,1',
        ];
        $messages = [
            'id.required'=>'请选择被推介的问题',
            'id.array'=>'id数据格式不正确',
            'type.required'=>'类型数据不能为空',
            'type.in'=>'类型数据格式不正确',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('id')) {
                $this->errors->add('id', $messages->first('id'));
            }
            if ($messages->has('type')) {
                $this->errors->add('type', $messages->first('type'));
            }
        }

        if($this->passes()){
            if($this->input['type'] == 1){
                if(count($this->input['id']) > 3){
                    $this->status = 400;
                    $this->description = '参数错误';
                    $this->accepted = false;
                    $this->errors->add('id', '热门问题最多推介三个');
                    return 0;
                }

                $res =  DB::table('questions')->where('is_hot', 1)->where('status', 0)->get();
                if(count($res) + count($this->input['id']) > 3){
                    $this->status = 400;
                    $this->description = '参数错误';
                    $this->accepted = false;
                    $this->errors->add('id', '请先取消之前热门问题，再推介该问题为热门');
                    return 0;
                }
            }


            foreach ($this->input['id'] as $k=>$v){
                $ret = DB::table('questions')->where('id', $v)->first();
                if(!$ret){
                    $this->status = 400;
                    $this->description = '参数错误';
                    $this->accepted = false;
                    $this->errors->add('id', 'id数据不正确');
                    return 0;
                }
            }

        }
    }

    protected function dohot(){
        DB::beginTransaction();
        foreach ($this->input['id'] as $k=>$v){
            $ret = DB::table('questions')->where('id',$v)->update([
                'is_hot' => $this->input['type'],
            ]);
            if(!$ret){
                $this->status = 400;
                $this->description = '参数错误';
                $this->accepted = false;
                $this->errors->add('id','操作失败');
                DB::rollBack();
                return 0;
            }
        }
        DB::commit();
    }
}
