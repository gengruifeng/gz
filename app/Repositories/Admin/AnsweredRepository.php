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


class AnsweredRepository extends Repository implements RepositoryInterface
{

    public $dofunction;

    public $doreturn;

    public $currenpPge = 1;

    public $num = 10;

    public $order = 'created_at';

    public $orderBy = 'DESC';

    public $data = [];


    public function contract()
    {
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
            $this->data = [
                'status' =>1,
                'currenpPge' =>$this->currenpPge,
                'totalPage' =>ceil($totalret/$this->num),
                'total' =>$totalret,
                'next' =>$this->currenpPge +1,
                'up' =>$this->currenpPge - 1,
                'data' =>$ret,
            ];
        }else{
            $this->data = [
                'status' =>0,
                'currenpPge' =>$this->currenpPge,
                'totalPage' =>0,
                'total' =>0,
                'data' =>$ret,
            ];
        }
    }

    protected function _initPram(){
        $this->currenpPge = !empty($this->input['currenpPge'])?$this->input['currenpPge']:1;
        $this->num = !empty($this->input['num'])?$this->input['num']:10;
        $this->order = !empty($this->input['order'])?$this->input['order']:'created_at';
        $this->orderBy = !empty($this->input['orderBy'])?$this->input['orderBy']:'DESC';
        $this->input['status'] = !empty($this->input['status']) ? $this->input['status'] : 0;
    }

    protected function _where($istotal){
        $db = DB::table('answers')->select('users.display_name','answers.id','answers.question_id','answers.detail','answers.vote_up','answers.commented','answers.created_at');

        $db->join('users', 'answers.uid', '=', 'users.id');

        $db->where('answers.question_id', $this->input['questionid']);
        if($istotal){
            $ret = $db->count();
        }else{
            $ret = $db->orderBy($this->order, $this->orderBy)->skip(($this->currenpPge - 1) * $this->num)->take($this->num)->get();
        }
        return $ret;
    }

    protected function del(){
        
        $this->validadordel();
        if($this->passes()){
            $this->dodel();
        }
    }

    protected function validadordel(){
        $rules = [
            'id'=>"required|integer|exists:answers,id",

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
        $info = DB::table('answers')->where('id',$this->input['id'])->first();

        $answerVotes = DB::table('answer_votes')->where('answers_id',$this->input['id'])->count();
        $userAnalysis = DB::table('user_analysis')->select('answer','reputation')->where('uid', $info->uid)->first();
        $ret3 = true;
        if($answerVotes){
            $ret3 = DB::table('user_analysis')->where('uid', $info->uid)->update([
                'answer' => $userAnalysis->answer-1,
                'reputation' => $userAnalysis->reputation-$answerVotes,
            ]);
        }

        $ret1 = DB::table('answer_votes')->where('answers_id',$this->input['id'])->delete();

        $answercomments = DB::table('answer_comments')->where('answer_id',$this->input['id'])->count();

        $ret4 = true;
        if($answercomments){
            $ret4 = DB::table('answer_comments')->where('answer_id',$this->input['id'])->delete();
        }

        $ret5 = DB::table('questions')->where('id',$info->question_id)->decrement('answered',1);

        $ret2 = DB::table('answers')->select('uid')->where('id',$this->input['id'])->delete();

        if(!$ret1 || !$ret2 || !$ret3 || !$ret4 || !$ret5){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','删除失败');
            DB::rollBack();
        }else{
            DB::commit();
        }
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
        }
    }


    protected function validadoredit(){
        $rules = [
            'id'=>"required|integer|exists:answers,id",
            'detail' => 'required',
        ];
        $messages = [
            'detail.required'=>'内容不能为空',
            'id.integer'=>'id格式不正确',
            'id.exists'=>'id错误',
            'id.required'=>'id不能为空',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('detail')) {
                $this->errors->add('detail', $messages->first('detail'));
            }
            if ($messages->has('id')) {
                $this->errors->add('id', $messages->first('id'));
            }
        }

    }

    protected function doedit()
    {
        DB::table('answers')->where('id', $this->input['id'])->update([
            'detail' => $this->input['detail'],
            'updated_at' => date('Y-m-d H:i:s',time()),
        ]);
    }

}
