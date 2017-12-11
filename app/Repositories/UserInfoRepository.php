<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

use Validator;
use Log;
use App\Utils\HttpStatus;
use App\Entity\User;
use App\Entity\UserWork;
use App\Entity\UserEducations;
use App\Entity\UserProficiencies;
use App\Entity\UserProfiles;
use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;

class UserInfoRepository extends Repository implements RepositoryInterface
{

    private $connection;

    public function contract(){
        $this->connection = new Connection();
        $this->connection->setParams(['host' => env('SPHINX_HOST'), 'port' => env('SPHINX_PORT')]);
        $this->_validator();
        if ($this->passes()) {

            $this->_update();

            $this->editsphinx();
        }
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
                'errors' => $this->errors->getErrors()
            ];
        }
        return $wrapper;
    }


    /**
     * 参数层验证
     *
     */
    protected function _validator(){

            $rules = [
            'display_name' => "required|regex:/^[a-zA-Z0-9_\x{4e00}-\x{9fa5}]{2,24}$/u|unique:users,display_name,{$this->input['uid']}",//名号
            'occupation' => 'required',//状态
            'slogan' => 'required',//个性签名
            'gender' => 'required',//性别
            'province' => 'max:30',//省份
            'city' => 'max:30',//城市
            'company' => 'max:30',//公司
            'position' => 'max:30',//职位
            'school' => 'max:30',//学校
            'department' => 'max:50',//专业方向
            'birthday' =>'regex:/^\\d{4}-\\d{2}-\\d{2}/',
        ];
        $message = [
            'display_name.required' => '名号不能为空！',
            'display_name.unique' => '名号已经存在！',
            'display_name.regex' => '名号为2-24位字符：支持中文、英文、数字、“_”',
            'occupation.required' => '状态不能为空！',
            'slogan.required' => '个性签名不能为空！',
            'gender.required' => '性别不能为空！',
            'province.max' => '省份字符不能超过30个！',
            'city.max' => '城市字符不能超过30个！',
            'company.max' => '公司字符不能超过30个！',
            'position.max' => '职位字符不能超过30个！',
            'school.max' => '学校字符不能超过30个！',
            'department.max' => '专业方向字符不能超过50个！',
            'birthday.regex' => '日期格式不正确！',

        ];
        $validator = Validator::make($this->input, $rules, $message);
        if ($validator->fails()) {

            $this->status = 400;
            $this->description = '参数错误';
            $this->error_name = 'Bad Request';
            $this->accepted = false;
            $messages = $validator->errors();

            if ($messages->has('display_name')) {
                $this->errors->add('display_name', $messages->first('display_name'));
            }
            if ($messages->has('occupation')) {
                $this->errors->add('occupation', $messages->first('occupation'));
            }
            if ($messages->has('slogan')) {
                $this->errors->add('slogan', $messages->first('slogan'));
            }
            if ($messages->has('gender')) {
                $this->errors->add('gender', $messages->first('gender'));
            }
            if ($messages->has('province')) {
                $this->errors->add('province', $messages->first('province'));
            }
            if ($messages->has('city')) {
                $this->errors->add('city', $messages->first('city'));
            }
            if ($messages->has('company')) {
                $this->errors->add('company', $messages->first('company'));
            }
            if ($messages->has('position')) {
                $this->errors->add('position', $messages->first('position'));
            }
            if ($messages->has('school')) {
                $this->errors->add('school', $messages->first('school'));
            }
            if ($messages->has('department')) {
                $this->errors->add('department', $messages->first('department'));
            }
            if ($messages->has('birthday')) {
                $this->errors->add('birthday', $messages->first('birthday'));
            }
        }

    }

    /**
     * 更新个人信息
     */
    protected function _update(){
        $uid = $this->input['uid'];
        //开启事务
        DB::beginTransaction();
        $data =[
            'occupation' => $this->input['occupation'],
            'gender' => $this->input['gender'],
            'province' => !empty($this->input['province'])?$this->input['province']:'',
            'city' => !empty($this->input['city'])?$this->input['city']:'',
            'birthday' => !empty($this->input['birthday'])?$this->input['birthday']:'',
            'slogan' => !empty($this->input['slogan'])?$this->input['slogan']:'',
        ];

        if(!empty($this->input['display_name'])){
            $data['display_name'] = $this->input['display_name'];
        }
        $user = new User;
        $user->where('id',$uid)->update($data);

        $retThree =true;
        $Educations = new UserEducations;
        if($this->input['school'] || $this->input['department']){
            $ret = $Educations->where('uid',$uid)->first();
            if(empty($ret)){
                $Educations->uid = $uid;

                $Educations->school = !empty($this->input['school'])?$this->input['school']:'';
                $Educations->department = !empty($this->input['department'])?$this->input['department']:'';
                $retThree = $Educations->save();
            }else{
                $data = [
                    'school' =>!empty($this->input['school'])?$this->input['school']:'',
                    'department' =>!empty($this->input['department'])?$this->input['department']:'',
                ];
                $Educations->where('uid',$uid)->update($data);
            }
        }else{
            $ret = $Educations->where('uid',$uid)->first();
            if(!empty($ret)){
                DB::table('user_educations')->where('uid',$uid)->delete();
            };
        }

        $retFour = true;
        $Work = new UserWork;
        if($this->input['company'] || $this->input['position']){

            $ret = $Work->where('uid',$uid)->first();
            if(empty($ret)){
                $Work->uid = $uid;
                $Work->company = !empty($this->input['company'])?$this->input['company']:'';
                $Work->position = !empty($this->input['position'])?$this->input['position']:'';
                $retFour = $Work->save();
            }else{
                $data = [
                    'company' =>!empty($this->input['company'])?$this->input['company']:'',
                    'position' =>!empty($this->input['position'])?$this->input['position']:'',
                ];
                $Work->where('uid',$uid)->update($data);
            }
        }else{
            $ret = $Work->where('uid',$uid)->first();
            if(!empty($ret)){
                DB::table('user_works')->where('uid',$uid)->delete();
            };
        }

        if(!$retThree || !$retFour){
            DB::rollBack();
            $this->status = 400;
            $this->description = '参数错误';
            $this->error_name = 'Bad Request';
            $this->accepted = false;
            $this->errors->add('position', '写入数据库失败！');
        }

        DB::commit();

    }

    /**
     * 根据uid获取用户信息
     * @param $uid
     * @return array
     */
    public function getInfo($uid){
        $returnData = [];
        $userData =  DB::table('users')->select('mobile','email','display_name','occupation','gender','province','city','birthday','slogan','created_at')->where('id',$uid)->get();
        foreach ($userData as $value){
            $returnData['display_name'] = $value->display_name;
            $returnData['occupation'] = $value->occupation;
            $returnData['gender'] = $value->gender;
            $returnData['province'] = $value->province;
            $returnData['city'] = $value->city;
            $returnData['birthday'] = $value->birthday;
            $returnData['mobile'] = $value->mobile;
            $returnData['email'] = $value->email;
            $returnData['created_at'] = $value->created_at;
            $returnData['slogan'] = $value->slogan;

        }

        $work = DB::table('user_works')->select('company','position')->where('uid',$uid)->get();

        if(!empty($work)){
            foreach ($work as $value){
                $returnData['company'] = $value->company;
                $returnData['position'] = $value->position;
            }
        }

        $edu = DB::table('user_educations')->select('school','department')->where('uid',$uid)->get();

        if(!empty($edu)){
            foreach ($edu as $value){
                $returnData['school'] = $value->school;
                $returnData['department'] = $value->department;
            }
        }
        return $returnData;
    }

    /**
     * 擅长领域数据
     * @param $uid
     * @return array
     */
    public function proficiencyInfo(){
        return DB::table("categories")
            ->orderby('order','desc')
            ->get();
    }

    /**
     * 更新用户头像
     * @param $avatar
     */
    public function updateAvatar($avatar){

        $uid = $this->input['uid'];
        if(empty($avatar)){
            $this->status = 500;
            $this->description = '内部错误，请稍后再试！';
            $this->error_name = HttpStatus::$statusTexts[$this->status];
            $this->accepted = false;
            $this->errors->add('avatar', '内部错误，请稍后再试！！');
        }
        if($this->passes()){
            //删除之前的头像文件
            $res = DB::table('users')->select('avatar')->where('id',$uid)->first();
            if(!empty($res->avatar) && $res->avatar !='head.png'){
                @unlink ('avatars/30/'.$res->avatar);
                @unlink ('avatars/60/'.$res->avatar);
                @unlink ('avatars/120/'.$res->avatar);
            }
            $ret = DB::table('users')->where('id',$uid)->update(
                [
                    'avatar' => $avatar,
                ]
            );
            if(!$ret){
                $this->status = 500;
                $this->description = '内部错误，请稍后再试！';
                $this->error_name = HttpStatus::$statusTexts[$this->status];
                $this->accepted = false;
                $this->errors->add('avatar', '内部错误，请稍后再试！！');
            }
        }

    }
    /**
     * 根据uid获取基本用户信息
     * @param $uid
     * @return array
     */
    public function UserArr($usession){
        $timestamp=time();
        $returnData = [];
        if(empty($usession)){
            $this->accepted = false;
            $this->status = 400;
            $this->description = '无用户信息';
        }else{
            $usersession =  DB::table('user_sessions')->select('value','remember_me','logged_in','activity')->where('sid',$usession)->first();
        }
        if ($this->passes()) {
            if(empty($usersession)){
                $this->accepted = false;
                $this->status = 401;
                $this->description = '登录已过期';
            }else{
                if (1 === $usersession->remember_me && ($timestamp - $usersession->logged_in) > 86400 * 14) {
                    $this->accepted = false;
                    $this->status = 401;
                    $this->description = '请重新登录';
                }
            }

        }
        if ($this->passes()) {
            if (0 === $usersession->remember_me && ($timestamp - $usersession->activity) > 60 * 30) {
                $this->accepted = false;
                $this->status = 401;
                $this->description = '请重新登录';
            }
        }
        if($this->passes()){
            $uid=  @unserialize(base64_decode($usersession->value));
            $userData =  DB::table('users')->select('id','display_name','avatar','group_id','created_at')->where('id',$uid['uid'])->get();
            foreach ($userData as $value){
                $returnData['display_name'] = $value->display_name;
                $returnData['avatar'] = $value->avatar;
                $returnData['uid'] = $value->id;
                $returnData['group_id'] = $value->group_id;
                $returnData['created_at'] = !empty($value->created_at)?$value->created_at:null;
            }
        }
        return $returnData;
    }

    public function editsphinx(){
        $id = $this->input['uid'];
        $name =  $this->input['display_name'];
        $ret = SphinxQL::create($this->connection)->query("select * from users where id=".$id)->execute();

        if(!empty($ret)){
            $sq = SphinxQL::create($this->connection)->replace()->into('users');
            $sq->value('id', $id)->value('display_name', $name);
            $sq->execute();
        }else{
            SphinxQL::create($this->connection)->query("insert into users (id,display_name) values ({$id},'".$name."')")->execute();
        }
    }
}
