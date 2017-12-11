<?php

namespace App\Repositories\Admin;

use App\Entity\UserProficiencies;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Utils\HttpStatus;
use App\Repositories\Repository;
use App\Repositories\RepositoryInterface;
use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;
use Log;

class AccountRepository extends Repository implements RepositoryInterface
{

    public $dofunction;

    public $doreturn;

    public $currenpPge = 1;

    public $num = 10;

    public $order = 'created_at';

    public $orderBy = 'DESC';

    public $isDisabled = 0;

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
             $this->_initData($ret);
             $this->data = [
                 'status' =>1,
                 'currenpPge' =>$this->currenpPge,
                 'totalPage' =>ceil($totalret/$this->num),
                 'total' =>$totalret,
                 'next' =>$this->currenpPge +1,
                 'up' =>$this->currenpPge - 1,
                 'data' =>$ret,
             ];
             \Log::info('[admin]Getting the user list succeeded - adminid:'.$this->input['adminid']);
         }else{
             $this->data = [
                 'status' =>0,
                 'currenpPge' =>$this->currenpPge,
                 'totalPage' =>0,
                 'total' =>0,
                 'data' =>$ret,
             ];
             \Log::info('[admin]There is no user data record - adminid:'.$this->input['adminid']);
         }
     }

    protected function _initPram(){
        $this->currenpPge = !empty($this->input['currenpPge'])?$this->input['currenpPge']:1;
        $this->num = !empty($this->input['num'])?$this->input['num']:10;
        $this->order = !empty($this->input['order'])?$this->input['order']:'created_at';
        $this->orderBy = !empty($this->input['orderBy'])?$this->input['orderBy']:'DESC';
        $this->isDisabled = !empty($this->input['isDisabled'])?$this->input['isDisabled']:0;
    }

    protected function _where($istotal){
        $db = DB::table('users')->select('users.id','users.display_name','users.email','users.mobile','users.group_id','users.created_at');

        if(!empty($this->input['company']) || !empty($this->input['position'])){
            $db->join('user_works', 'users.id', '=', 'user_works.uid');
        }

        if(!empty($this->input['school']) || !empty($this->input['department'])){
            $db->join('user_educations', 'users.id', '=', 'user_educations.uid');
        }

        $db->where('users.disabled',$this->isDisabled);

        if(!empty($this->input['display_name'])){
            $db->where('users.display_name','like','%'.$this->input['display_name'].'%');
        }

        if(!empty($this->input['mobile'])){
            $db->where('users.mobile','like','%'.$this->input['mobile'].'%');
        }

        if(!empty($this->input['email'])){
            $db->where('users.email','like','%'.$this->input['email'].'%');
        }
        
        if(!empty($this->input['company'])){    
            $db->where('user_works.company','like','%'.$this->input['company'].'%');
        }

        if(!empty($this->input['position'])){
            $db->where('user_works.position','like','%'.$this->input['position'].'%');
        }

        if(!empty($this->input['school'])){
            $db->where('user_educations.school','like','%'.$this->input['school'].'%');
        }

        if(!empty($this->input['department'])){
            $db->where('user_educations.department','like','%'.$this->input['department'].'%');
        }

        if(!empty($this->input['occupation']) && count($this->input['occupation']) != 2){
            $db->where('users.occupation',$this->input['occupation'][0]);
        }

        if(!empty($this->input['gender']) && count($this->input['gender']) == 1){
            $db->where('users.gender',$this->input['gender'][0]);
        }elseif(!empty($this->input['gender']) && count($this->input['gender']) == 2){
            $db->where('users.gender',$this->input['gender'][0]);
            $db->orwhere('users.gender',$this->input['gender'][1]);
        }

        if(!empty($this->input['group_id']) && count($this->input['group_id']) == 1){
            $db->where('users.group_id',$this->input['group_id'][0]);
        }elseif(!empty($this->input['group_id']) && count($this->input['group_id']) == 2){
            $db->where('users.group_id',$this->input['group_id'][0]);
            $db->orwhere('users.group_id',$this->input['group_id'][1]);
        }

        if($istotal){
            $ret = $db->count();
        }else{
            $ret = $db->orderBy($this->order, $this->orderBy)->skip(($this->currenpPge - 1) * $this->num)->take($this->num)->get();
        }
        return $ret;
    }

    protected function _initData(&$ret){
        foreach ($ret as $k=>$v){
            $analysis = DB::table('user_analysis')->select('online','activity')->where('uid',$v->id)->first();
            if(!empty($analysis->activity)){
                $ret[$k]->activity = date('Y-m-d H:i:s',$analysis->activity);
            }else{
                $ret[$k]->activity = $v->created_at;
            }
            if(!empty($analysis)){
                $duration = (int)$analysis->online;
            }else{
                $duration = 0;
            }
            if($duration < 60){
                $ret[$k]->duration = '1分钟';
            }elseif($duration > 60){
                $ret[$k]->duration = ceil($duration/60).'分钟';
            }
//            elseif($duration > 3600){
//                $ret[$k]->duration = ceil($duration/3600).'小时';
//            }
            $ret[$k]->email = !empty($v->email)?$v->email:'-';
            $ret[$k]->mobile = !empty($v->mobile)?$v->mobile:'-';

            if(0 == $v->group_id){
                $ret[$k]->group_id = '普通用户';
            }else{
                $group = DB::table('user_groups')->select('name')->where('id',$v->group_id)->first();
                $ret[$k]->group_id = !empty($group->name)?$group->name:'';
            }
        }
    }


    /**
     * 封禁用户
     */
    protected function fengjinUser(){
        $this->validadorfj();
        if($this->passes()){
            $this->updatefj();
            $this->delsphinx();
        }
    }


    protected function validadorfj(){
        $rules = [
            'id' => 'required|integer',//名号
        ];
        $message = [
            'id.required' => 'id不能为空！',
            'id.integer' => 'id数据格式不正确！',
        ];
        $validator = Validator::make($this->input, $rules, $message);
        if ($validator->fails()) {

            $this->status = 400;
            $this->description = '参数错误';
            $this->error_name = 'Bad Request';
            $this->accepted = false;
            $messages = $validator->errors();

            if ($messages->has('id')) {
                $this->errors->add('id', $messages->first('id'));
            }

        }
        if($this->passes()){
            $ret = DB::table('users')->where('id',$this->input['id'])->count();
            if(!$ret){
                $this->errors->add('id', 'id验证不匹配！');
                $this->status = 400;
                $this->description = '参数错误';
                $this->error_name = 'Bad Request';
                $this->accepted = false;
            }
        }

    }

    /**
     * 执行封禁
     */
    protected function updatefj(){
        $ret = DB::table('users')->where('id', $this->input['id'])->update(['disabled' => 1]);
        if(!$ret){
            $this->errors->add('id', '内部错误！');
            $this->status = 400;
            $this->description = '参数错误';
            $this->error_name = 'Bad Request';
            $this->accepted = false;
            \Log::error('[admin]Block user,Failed to update the database,users uid is '.$this->input['id'].' - adminid:'.$this->input['adminid']);
        }else{
            \Log::info('[admin]Block user ,users uid is '.$this->input['id'].'- adminid:'.$this->input['adminid']);
        }

    }

    /**
     * 提交用户
     */
    public function subedit(){
        $this->validadorsubedit();
        if($this->passes()){
            $this->updateuser();

            if($this->input['disabled'] == 0){
                $this->editsphinx();
            }

        }
    }

    protected function validadorsubedit(){
        $rules = [
            'display_name' => "required|regex:/^[a-zA-Z0-9_\x{4e00}-\x{9fa5}]{2,24}$/u|unique:users,display_name,{$this->input['id']}",//名号
            'passcode'=>'regex:/^[\w_]{6,16}$/',
            'email'=>"email|unique:users,mobile,{$this->input['id']}",
            'mobile'=>"regex:/^1[34578][0-9]{9}$/|unique:users,mobile,{$this->input['id']}",
            'disabled'=>'in:1,0',
            'gender'=>'in:1,2,3',
        ];
        $messages = [
            'display_name.required' => '名号不能为空！',
            'display_name.regex' => '名号为4-24位字符：支持中文、英文、数字、“_”',
            'display_name.unique'=>'您的名号已被使用',
            'passcode.regex'=>'密码为6-16位字符，只能输入英文、数字、“_”',
            'email.email'=>'邮箱格式不正确',
            'email.unique'=>'邮箱已被使用',
            'mobile.regex'=>'手机格式不正确',
            'mobile.unique'=>'手机已被使用',
            'disabled.in'=>'封禁用户数据格式正确',
            'gender.in'=>'性别数据格式不正确',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('display_name')) {
                $this->errors->add('display_name', $messages->first('display_name'));
            }

            if ($messages->has('passcode')) {
                $this->errors->add('passcode', $messages->first('passcode'));
            }

            if ($messages->has('email')) {
                $this->errors->add('email', $messages->first('email'));
            }

            if ($messages->has('mobile')) {
                $this->errors->add('mobile', $messages->first('mobile'));
            }

            if ($messages->has('disabled')) {
                $this->errors->add('disabled', $messages->first('disabled'));
            }

            if ($messages->has('gender')) {
                $this->errors->add('gender', $messages->first('gender'));
            }
        }
    }

    /**
     * 更新用户信息
     */
    public function updateuser(){
        $udate = [
            'display_name' => $this->input['display_name'],
            'email' => $this->input['email'],
            'mobile' => $this->input['mobile'],
            'disabled' => $this->input['disabled'],
            'gender' => !empty($this->input['gender'])?$this->input['gender']:0,
            'group_id' => $this->input['group_id'],
        ];
        if(!empty($this->input['passcode'])){
            $udate['passcode'] =  Hash::make($this->input['passcode']);
        }

        $ret = DB::table('users')->where('id',$this->input['id'])->update($udate);
        \Log::info('[admin]edit user,users uid is '.$this->input['id'].' - adminid:'.$this->input['adminid']);
//        if(!$ret){
//            $this->status = 500;
//            $this->description = '写入数据库失败';
//            $this->accepted = false;
//            $this->errors->add('id','写入数据库失败');
//
//        }
    }

    public function delsphinx(){
        SphinxQL::create($this->connection)->query('delete from users where id = '.$this->input['id'])->execute();
    }

    public function editsphinx(){
        $id = $this->input['id'];
        $name =  $this->input['display_name'];
        $ret = SphinxQL::create($this->connection)->query("select * from users where id=".$id)->execute();

        if(!empty($ret)){
            $sq = SphinxQL::create($this->connection)->replace()->into('users');
            $sq->value('id', $id)->value('display_name', addslashes($name));
            $sq->execute();
        }else{
            SphinxQL::create($this->connection)->query("insert into users (id,display_name) values ({$id},'".addslashes($name)."')")->execute();
        }
    }
}
