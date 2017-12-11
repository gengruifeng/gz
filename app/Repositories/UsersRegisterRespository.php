<?php

namespace App\Repositories;

use App\Entity\UserAnalysis;
use App\Entity\UserEducations;
use App\Entity\UserProficiencies;
use App\Entity\UserSession;
use App\Entity\UserWork;
use App\Repositories\Repository;
use App\Repositories\RepositoryInterface;

use Illuminate\Support\Facades\DB;
use App\Utils\HttpStatus;
use App\Entity\User;
use App\Entity\UserQq;
use App\Entity\UserWeixin;
use App\Entity\UserSina;

use App\Utils\Sms;
use App\Utils\Email;
use Crypt;
use Cookie;
use Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Foolz\SphinxQL\Connection;
use Foolz\SphinxQL\SphinxQL;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UsersRegisterRespository extends Repository implements RepositoryInterface
{
    const PATTERN_DISPLAYNAME = "/^[a-zA-Z0-9_\x{4e00}-\x{9fa5}]{2,24}$/u";
    const PATTERN_PASSCODE ="/^[\w_]{6,16}$/";
    private $connection;
    
    /**
     * 用户注册
     *
     * {@inheritdoc}
     */
    public function contract()
    {
        $this->connection = new Connection();
        $this->connection->setParams(['host' => env('SPHINX_HOST'), 'port' => env('SPHINX_PORT')]);
        //验证手机号
        if ($this->input['registerType'] == 1) {
            $this->checkMobile();
        }
        //手机注册
        if ($this->input['registerType'] == 2) {
            $this->checkMobileParameters();
            if ($this->passes()) {
                $this->isCodeExist();
            }
            if($this->passes()){
                return $this->mobileRegister();
            }
        }
        //验证邮箱并发送邮件
        if ($this->input['registerType'] == 3) {
            $this->checkEmail();
            if ($this->passes()) {
                return $this->sendEmail();
            }
        }
        //邮箱注册
        if ($this->input['registerType'] == 4) {
            $this->checkEmailParameters();
            if ($this->passes()) {
                $this->isCodeExist();
                if ($this->passes()) {
                    return $this->emailRegister();
                }
            }
        }
        //完善个人信息
        if ($this->input['registerType'] == 5) {
            $this->checkInformationParameters();
            //邀请码
            if($this->passes()){
                $this->isCodeExist();
            }
            if ($this->passes()) {
                return $this->Information();
            }
        }
        
        //个人信息补全
        if ($this->input['registerType'] == 6) {
            $this->checkPersonalParameters();
            if ($this->passes()) {
                return $this->personalAdd();
            }
        }

        //擅长领域添加
        if ($this->input['registerType'] == 7) {
            return $this->goodAtInfoAdd();
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
     * Validate the email register parameters
     *
     * @param void
     *
     * @return void
     */
    private function checkMobileParameters()
    {
        $rules = [
            'passcode'=>'required|confirmed|regex:'.self::PATTERN_PASSCODE,
            'passcode_confirmation' => 'required',
        ];
        $messages = [
            'passcode.required'=>'密码不能为空',
            'passcode.confirmed'=>'密码输入不一致',
            'passcode.regex'=>'密码为6-16位字符，只能输入英文、数字、“_”',
            'passcode_confirmation.required'=>'确认密码不能为空',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('passcode')) {
                $this->errors->add('passcode', $messages->first('passcode'));
            }
            if ($messages->has('passcode_confirmation')) {
                $this->errors->add('passcode_confirmation', $messages->first('passcode_confirmation'));
            }
        }
    }


    /**
     * Validate the email register personal parameters
     *
     * @param void
     *
     * @return void
     */
    private function checkPersonalParameters()
    {
        $rules = [
            'display_name'=>'required|regex:'.self::PATTERN_DISPLAYNAME,
            'userid'=>'required|exists:users,id',
            'stepone' => 'required',
            'steptwo' => 'required',
        ];
        $messages = [
            'display_name.required'=>'名号不能为空',
            'display_name.regex'=>'名号为2-24位字符：支持中文、英文、数字、“_”',
            'userid.required'=>'用户不存在',
            'userid.exists'=>'用户不存在',
            'stepone.required'=>'该项不能为空',
            'steptwo.required'=>'该项不能为空',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        $userInfo = User::select('id')->where('display_name',$this->input['display_name'])->first();
        if($userInfo != null && $userInfo->id != $this->input['userid']){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('display_name', '名号已经被注册');
        }
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('display_name')) {
                $this->errors->add('display_name', $messages->first('display_name'));
            }

            if ($messages->has('stepone')) {
                $this->errors->add('stepone', $messages->first('stepone'));
            }

            if ($messages->has('steptwo')) {
                $this->errors->add('steptwo', $messages->first('steptwo'));
            }
        }

    }

    /**
     * Validate the email register parameters
     *
     * @param void
     *
     * @return void
     */
    private function checkEmailParameters()
    {
        $rules = [
            'verifycode' => 'required|captcha',
            'passcode'=>'required|confirmed|regex:'.self::PATTERN_PASSCODE,
            'passcode_confirmation' => 'required',
        ];
        $messages = [
            'verifycode.required' => '验证码不能为空',
            'verifycode.captcha' => '验证码不正确',
            'passcode.required'=>'密码不能为空',
            'passcode.confirmed'=>'密码输入不一致',
            'passcode.regex'=>'密码为6-16位字符，只能输入英文、数字、“_”',
            'passcode_confirmation.required'=>'确认密码不能为空',
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('verifycode')) {
                $this->errors->add('verifycode', $messages->first('verifycode'));
            }
            if ($messages->has('passcode')) {
                $this->errors->add('passcode', $messages->first('passcode'));
            }
            if ($messages->has('passcode_confirmation')) {
                $this->errors->add('passcode_confirmation', $messages->first('passcode_confirmation'));
            }
        }
    }

    /**
     * Validate the mobile
     *
     * @param void
     *
     * @return void
     */
    public function checkMobile()
    {
        $rules = [
            'mobile'=>'required|regex:/^1[34578][0-9]{9}$/|unique:users,mobile',
        ];
        $messages = [
            'mobile.required'=>'手机号码不能为空',
            'mobile.regex'=>'手机号码格式不正确',
            'mobile.unique'=>'手机号码已被注册',
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('mobile')) {
                $this->errors->add('mobile', $messages->first('mobile'));
            }
        }else{
            session(['user_mobile' => $this->input['mobile']]);
        }
    }
    /**
     * 验证个人注册信息
     *
     * @param void
     *
     * @return void
     */
    private function checkInformationParameters()
    {

            $rules = [
                'mobile'=>'required|unique:users,mobile',
                'password'=>'required|regex:'.self::PATTERN_PASSCODE,
            ];
            $messages = [
                'mobile.required'=>'手机不能为空',
                'mobile.unique'=>'手机号已注册，请用手机号密码登录！',
                'passcode.required'=>'密码不能为空',
                'passcode.regex'=>'密码为6-16位字符，只能输入英文、数字、“_”',
            ];
            $validator = Validator::make($this->input, $rules, $messages);
            if ($validator->fails()) {
                $this->status = 400;
                $this->description = '参数错误';
                $this->accepted = false;
                $messages = $validator->errors();
                if ($messages->has('mobile')) {
                    $this->errors->add('mobile', $messages->first('mobile'));
                }
                if ($messages->has('passcode')) {
                    $this->errors->add('passcode', $messages->first('passcode'));
                }
            }
    }

    /**
     * Validate the email
     *
     * @param void
     *
     * @return void
     */
    private function checkEmail()
    {
        $rules = [
            'email'=>'required|email|unique:users,email',
        ];
        $messages = [
            'email.required'=>'邮箱不能为空',
            'email.email'=>'邮箱格式不正确',
            'email.unique'=>'邮箱已经被注册',
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('email')) {
                $this->errors->add('email', $messages->first('email'));
            }
        }else{
            session(['user_email'=> $this->input['email']]);
        }
    }

    //手机号注册
    public function mobileRegister()
    {
        DB::beginTransaction();
        $ip = \Request::getClientIp();
        $user = new User;
        $user->mobile = $this->input['mobile'];
        $user->passcode = Hash::make($this->input['passcode']);
        $user->mobile_verified = 1;
        $user->avatar = 'head.png';
        $user->registered_ip = bindec(decbin(ip2long($ip)));
        $userResult = $user->save();
        //验证邀请码
        $referralCode = DB::table('referral_codes')
            ->where('code',$this->input['referral_code'])
            ->update(array('used'=>1));
        //短信验证码
        $verifyInfo = true;
        $verify = Sms::checkCode($this->input['mobile'],'registered',$this->input['verifycode']);
        if( $verify['status'] != 1 ){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('verifycode', $verify['msg']);
            $verifyInfo = false;
        }
        if ($userResult && $referralCode && $verifyInfo) {
            DB::commit();
            Sms::sendSmsSuccess($this->input['mobile'], [$this->input['mobile'], $this->input['passcode']], 106597);
            session([
                'user_register_id'=>$user->id,
            ]);
        } else {
            DB::rollback();
            $this->status = 500;
            $this->description = '写入数据失败';
            $this->accepted = false;
            return ;
        }
    }

    //个人信息补全
    public function personalAdd()
    {
        $user = User::find($this->input['userid']);
        if($user != null){
            DB::beginTransaction();
            $user->display_name = $this->input['display_name'];
            $user->occupation = $this->input['dateformat'];
            $user->gender = $this->input['gender'];
            $userResult = $user->save();
            $personalStatus = true;
            //用户统计表
            $userAnalysisInfo = UserAnalysis::where('uid',$this->input['userid'])->first();
            if($userAnalysisInfo == null){
                $userAnalysis = new UserAnalysis;
                $userAnalysis->uid = $this->input['userid'];
                $userAnalysisResult = $userAnalysis->save();
            }else{
                $userAnalysisInfo->follower = 0;
                $userAnalysisInfo->following = 0;
                $userAnalysisInfo->invitation = 0;
                $userAnalysisInfo->question = 0;
                $userAnalysisInfo->answer = 0;
                $userAnalysisInfo->profile_viewed = 0;
                $userAnalysisInfo->reputation = 0;
                $userAnalysisInfo->online = 0;
                $userAnalysisInfo->last_login = 0;
                $userAnalysisInfo->activity = 0;
                $userAnalysisResult = $userAnalysisInfo->save();
            }

            if($this->input['dateformat'] == 1){
                $userEducations = UserEducations::where('uid',$this->input['userid'])->first();
                if($userEducations === null){
                    $education = new UserEducations;
                    $education->uid = $this->input['userid'];
                    $education->school = $this->input['stepone'];
                    $education->department = $this->input['steptwo'];
                    $personalStatus = $education->save();
                }else{
                    $userEducations->school = $this->input['stepone'];
                    $userEducations->department = $this->input['steptwo'];
                    $personalStatus = $userEducations->save();
                }
            }

            if($this->input['dateformat'] == 2){
                $userWorks = UserWork::where('uid',$this->input['userid'])->first();
                if($userWorks === null){
                    $work = new UserWork;
                    $work->uid = $this->input['userid'];
                    $work->company = $this->input['stepone'];
                    $work->position = $this->input['steptwo'];
                    $personalStatus = $work->save();
                }else{
                    $userWorks->company = $this->input['stepone'];
                    $userWorks->position = $this->input['steptwo'];
                    $personalStatus = $userWorks->save();
                }
            }
            if ($userResult && $userAnalysisResult && $personalStatus) {
                DB::commit();
                $this->addSession($this->input['userid']);
                $this->sphinxAddDisplayName($this->input['userid'],$this->input['display_name']);
            }else{
                DB::rollback();
                $this->status = 500;
                $this->description = '写入数据失败';
                $this->accepted = false;
                return ;
            }
        }else{
            $this->status = 400;
            $this->description = '用户不存在';
            $this->accepted = false;
        }

    }

    //邮箱激活
    public function emailActive()
    {
        $input = explode('_',Crypt::decrypt($this->input['code']));
        if( time() < ($input[2] + 86400)){
            $data = [
                'email' => $input[0],
            ];
            $result=User::where('email',$data['email'])->first();
            if($result){
                if($result['email_verified'] == 1){
                    return [
                        'status'=>1,
                        'desc'=>'您的邮箱已激活，请直接登录',
                    ];   
                }else{
                    session(['user_register_id'=> $result->id]);
                    return [
                        'status'=>2,
                        'desc'=>'您的邮箱信息不全，请补充信息。'
                    ];  
                }
            }else{
                //数据入库
                $re = User::create($data);
                if(!$re){
                    $this->status = 500;
                    $this->description = '写入数据库失败';
                    $this->accepted = false;
                }else{
                    session(['user_register_id'=> $re->id]);
                }
                return [
                    'id'=>$re->id,
                ];
            }
        }else{
            return [
                'status'=>3,
                'desc'=>'验证邮件已失效',
            ];
        }
    }

    //邮箱注册执行
    public function emailRegister()
    {
        DB::beginTransaction();
        $ip = \Request::getClientIp();
        $user = User::find($this->input['id']);
        if($user != null){
            $user->passcode = Hash::make($this->input['passcode']);
            $user->registered_ip = bindec(decbin(ip2long($ip)));
            $user->email_verified = 1;
            $user->avatar = 'head.png';
            $userResult = $user->save();
            $referralCode = DB::table('referral_codes')
                ->where('code',$this->input['referral_code'])
                ->update(array('used'=>1));
            if ($userResult  && $referralCode) {
                DB::commit();
                session([
                    'user_register_id'=>$user->id,
                ]);
            } else {
                DB::rollback();
                $this->status = 500;
                $this->description = '写入数据失败';
                $this->accepted = false;
                return ;
            }  
        }
        
    }

    //发送邮件
    public function sendEmail()
    {
        $mailaddressee = $this->input['email'];
        $title = '请确认您在工作网的注册邮箱';
        $template = 'activeuser';
        $data = [
            'url'=>url('/emailactive?code='.Crypt::encrypt($mailaddressee.'_grf_'.time())),
        ];
        $result = Email::send($mailaddressee , $template , $data , $title );
        if($result['status'] !=1){
            $this->status = 400;
            $this->description = $result['msg'];
            $this->accepted = false;
        }
    }

    //绑定本站账号  目前为手机号
    public function Information()
    {
        $verify = Sms::checkCode($this->input['mobile'],'registered',$this->input['mobMsg']);
        if( $verify['status'] == 1 ){
            $User = new User();
            $Userres = $User->where('display_name',$this->input ['nickname'])->first();

            empty($Userres)?$display_name = $this->input ['nickname']:$display_name = $this->input ['nickname'].$this->input['type'].rand(1000,9999);

            $ip = \Request::getClientIp();
            $User->avatar = 'head.png';
            $User->mobile = $this->input['mobile'];
            $User->passcode = Hash::make($this->input['password']);
            $User->registered_ip = bindec(decbin(ip2long($ip)));
            $User->save();
            if(!$User->save()){
                $this->status = 500;
                $this->description = '写入数据库失败';
                $this->accepted = false;
            }else{
                session(['user_register_id'=> $this->input['uid']]);
            }
            //QQ第三方登录
            if($this->input['type'] == "qq"){
                $Userqq = new UserQq();
                $Userqq->uid = $User->id;
                $Userqq->nickname = $this->input ['qqname'];
                $Userqq->openid = $this->input ['openid'];
                $Userqq->gender = $this->input ['gender'];
                $Userqq->access_token = $this->input ['access_token'];
                $Userqq->refresh_token = empty($this->input ['refresh_token'])?" ":$this->input ['refresh_token'];
                $Userqq->avatar = $this->input ['avatar'];
                $Userqq->figureurl = $this->input ['figureurl'];
                if( !$Userqq->save()){
                    $this->status = 500;
                    $this->description = '服务器错误';
                    $this->accepted = false;
                }
            }
            //微信第三方登录
            if($this->input['type'] == "weixinweb"){
                $UserWeixin= new UserWeixin();
                $UserWeixin->uid = $User->id;
                $UserWeixin->nickname = $this->input ['weixinname'];
                $UserWeixin->openid = $this->input ['openid'];
                $UserWeixin->headimgurl = $this->input ['headimgurl'];
                $UserWeixin->access_token = $this->input ['access_token'];
                $UserWeixin->refresh_token = empty($this->input ['refresh_token'])?" ":$this->input ['refresh_token'];
                $UserWeixin->sex = $this->input ['sex'];
                $UserWeixin->province = $this->input ['province'];
                $UserWeixin->province = $this->input ['city'];
                $UserWeixin->province = $this->input ['country'];
                $UserWeixin->unionid = $this->input ['unionid'];
                if( !$UserWeixin->save()){
                    $this->status = 500;
                    $this->description = '服务器错误';
                    $this->accepted = false;
                }
            }
            //微博第三方登录
            if($this->input['type'] == "weibo"){
                $UserSina= new UserSina();
                $UserSina->uid = $User->id;
                $UserSina->screen_name = $this->input ['screen_name'];
                $UserSina->sinaid = $this->input ['sinaid'];
                $UserSina->profile_image_url = $this->input ['profile_image_url'];
                $UserSina->access_token = $this->input ['access_token'];
                $UserSina->gender = $this->input ['gender'];
                $UserSina->location = $this->input ['location'];
                $UserSina->description = $this->input ['description'];
                $UserSina->blog_url = $this->input ['blog_url'];
                $UserSina->expires_in = $this->input ['expires_in'];
                if( !$UserSina->save()){
                    $this->status = 500;
                    $this->description = '服务器错误';
                    $this->accepted = false;
                }
            }
            if ($this->passes()){
                 DB::table('referral_codes')
                    ->where('code',$this->input['referral_code'])
                    ->update(array('used'=>1));
                $this->biz =  [
                    'uid' => $User->id,
                    'display_name' =>$display_name
                ];
            }
        }else{
            $this->status = 400;
            $this->description = '验证码错误';
            $this->accepted = false;
            $this->errors->add('verifycode', $verify['msg']);
        }

    }
    //注册成功将display_name存入sphinx
    public function sphinxAddDisplayName($id,$displayName){
        $ret = SphinxQL::create($this->connection)->query("select * from users where id=".$id)->execute();
        if(!empty($ret)){
            $sphinx = SphinxQL::create($this->connection)->replace()->into('users');
            $sphinx->value('id', $id)->value('display_name', $displayName);
            $sphinx->execute();
        }else{
            SphinxQL::create($this->connection)->query("insert into users (id,display_name) values ({$id},'".$displayName."')")->execute();
        }
    }


    //将信息存入session
    public function addSession($uid){
        $datas=[
            'uid' => $uid,
        ];
        $sessionId = $this->generateSessionId();
        $userSession = new UserSession;
        $userSession->sid = $sessionId;
        $userSession->value = base64_encode(serialize($datas));
        $userSession->activity = time();
        $userSession->logged_in = time();
        $userSession->ip = \Request::getClientIp();
        $result = $userSession->save();
        if($result){
            Cookie::queue('user_session', $sessionId,30);
        }
    }

    /**
     * Get a new, random session ID.
     *
     * @return string
     */
    protected function generateSessionId(){
        return sha1(uniqid('', true).Str::random(25).microtime(true));
    }

    //擅长领域
    public function beGoodAt(){
        $categorys = DB::table("categories")->orderby('order','desc')->get();
        $data = [];
        if($categorys != null){
            foreach($categorys as $category){
                $tags = DB::table("tags")
                    ->join('categories_tags','tags.id','=','categories_tags.tag_id')
                    ->select('tags.id','tags.name','categories_tags.category_id')
                    ->where('categories_tags.category_id',$category->id)
                    ->get();
                if($tags != null){
                    $data['categorys'][] = [
                        'cateId'=>$category->id,
                        'cateName'=>$category->entity,
                    ];
                    $data['tags'][] = $tags;
                }
            }
        }
        return $data;
    }
    
    //添加擅长领域
    
    private function goodAtInfoAdd(){
        if(!empty($this->input['tagIds'])){
            $tagIds = $this->input['tagIds'];
            if(count($tagIds) >9){
                $this->status = 400;
                $this->description = '最多添加9个标签';
                $this->accepted = false;
            }
            if(empty($this->input['uid'])){
                $this->status = 404;
                $this->description = '用户不存在';
                $this->accepted = false;
            }
            if($this->passes()){
                foreach($tagIds as $tagId){
                    if(!empty($this->input['uid'])){
                        $userProficiencies = UserProficiencies::where('uid',$this->input['uid'])->where('tag_id',$tagId)->first();
                        if($userProficiencies === null){
                            $userProficiencies = new UserProficiencies;
                            $userProficiencies->uid = $this->input['uid'];
                            $userProficiencies->tag_id = $tagId;
                            if(!$userProficiencies->save()){
                                $this->status = 500;
                                $this->description = '写入数据库失败';
                                $this->accepted = false;
                            }else{
                                Session::forget('user_register_id');
                            }
                        }
                    }
                }
            }
        }
    }

    public function checkDisplayName($name){
        return DB::table("users")->where('display_name',$name)->first();
    }
    //验证邀请码
    public function isCodeExist(){
        if(!empty($this->input['referral_code'])){
            $codeInfo = DB::table("referral_codes")
                ->where('code',$this->input['referral_code'])
                ->first();
            if($codeInfo === null){
                $this->status = 400;
                $this->description = '邀请码不存在';
                $this->accepted = false;
                $this->errors->add('referral_code', '邀请码不存在');
            }else{
                if($codeInfo->used > 0){
                    $this->status = 400;
                    $this->description = '邀请码已使用';
                    $this->accepted = false;
                    $this->errors->add('referral_code', '邀请码已使用');
                }
            }
        }else{
            $this->status = 400;
            $this->description = '邀请码不能为空';
            $this->accepted = false;
            $this->errors->add('referral_code', '邀请码不能为空');
        }
    }

}
