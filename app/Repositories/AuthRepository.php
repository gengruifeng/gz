<?php

namespace App\Repositories;

use Log;
use Validator;

use Illuminate\Support\Facades\DB;
use App\Entity\UserQq;
use App\Entity\UserWeixin;
use App\Entity\UserSina;
use App\Entity\User;

use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;

use Illuminate\Support\Facades\Crypt;


use App\Utils\HttpStatus;

class AuthRepository extends Repository implements RepositoryInterface
{

    //用户ID
    private $uid= [];
    /**
     * 第三方登录业务
     *
     * {@inheritdoc}
     */
    public function contract()
    {
        
        Log::info('Showing user profile for user: 101');
        //QQ第三方登录
        if($this->input['type'] == "qq"){
            $this->QQ_login();
        }
        //微信第三方登录
        if($this->input['type'] == "weixinweb"){
            $this->Weixin_login();
        }
        //微博第三方登录
        if($this->input['type'] == "weibo"){
            $this->Weibo_login();
        }
        //QQ解绑
        if($this->input['type'] == "unbundlingqq"){
            $this->validate();
            $this->Qq_unbundling();
        }
        //微博解绑
        if($this->input['type'] == "unbundlingweibo"){
            $this->validate();
            $this->Weibo_unbundling();
        }
        //微信解绑
        if($this->input['type'] == "unbundlingweixin"){
            $this->validate();
            $this->Weixin_unbundling();
        }
        return $this->biz;
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
                'errors' => $this->errors->getErrors()
            ];
        }

        return $wrapper;
    }
    /**
     *
     * QQ第三方登录 .
     *
     */
    public function QQ_login()
    {
            $Userqq = new UserQq();
            //查询openid是否存在
            $QQres = $Userqq->where('openid',$this->input['openid'])->first();
            if( empty($QQres) ){
                //判断是新增还是绑定用户
                if(!empty($this->input['uid'])){
                    $userres = $Userqq->where('uid',$this->input['uid'])->first();
                    if(!empty($userres)){
                        $type = 1;
                    }else{
                        $Userqq->uid = $this->input['uid'];
                        $Userqq->nickname = $this->input ['qqname'];
                        $Userqq->openid = $this->input ['openid'];
                        $Userqq->gender = $this->input ['gender'];
                        $Userqq->access_token = $this->input ['access_token'];
                        $Userqq->refresh_token = empty($this->input ['refresh_token'])?" ":$this->input ['refresh_token'];
                        $Userqq->avatar = $this->input ['avatar'];
                        $Userqq->figureurl = $this->input ['figureurl'];
                        $Userqq->save();
                    }
                }else{
                    $type = 1;
                }
            } elseif(!empty($this->input['uid'])){
                if($QQres->uid != $this->input['uid']){
                    $this->accepted = false;
                }
            }
            if($this->passes()){
                $this->biz = [
                    'type' => empty($type)?"":$type,
                    'uid' =>empty($QQres->uid)?$this->input['uid']:$QQres->uid ,
                ];
            }

    }
    /**
     *
     * 微信第三方登录 .
     *
     */
    public function Weixin_login()
    {
        $UserWeixin= new UserWeixin();
        //查询openid是否存在
        $Weixinres = $UserWeixin->where('openid',$this->input['openid'])->first();
        if( empty($Weixinres) ){
            //判断是新增还是绑定用户
            if(!empty($this->input['uid'])){
                //如果session存在用户ID那么则是用户绑定
                $uid = $this->input['uid'];
                $UserWeixin->uid = $uid;
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
                $UserWeixin->save();
            }else{
                $type = 1;
            }
        } elseif(!empty($this->input['uid'])){
            if($Weixinres->uid != $this->input['uid']){
                $this->accepted = false;
            }
        }
        if($this->passes()){
            $this->biz = [
                'type' => empty($type)?"":$type,
                'uid' =>empty($Weixinres->uid)?$this->input['uid']:$Weixinres->uid ,
            ];
        }
    }
    /**
     *
     * 微博第三方登录 .
     *
     */
    public function Weibo_login()
    {
        $UserSina= new UserSina();
        //查询openid是否存在
        $Sinares = $UserSina->where('sinaid',$this->input['sinaid'])->first();
        if( empty($Sinares) ){
            //判断是新增还是绑定用户
            if(!empty($this->input['uid'])){
                $uid = $this->input['uid'];
                $UserSina->uid = $uid;
                $UserSina->screen_name = $this->input ['screen_name'];
                $UserSina->sinaid = $this->input ['sinaid'];
                $UserSina->profile_image_url = $this->input ['profile_image_url'];
                $UserSina->access_token = $this->input ['access_token'];
                $UserSina->gender = $this->input ['gender'];
                $UserSina->location = $this->input ['location'];
                $UserSina->description = $this->input ['description'];
                $UserSina->blog_url = $this->input ['blog_url'];
                $UserSina->expires_in = $this->input ['expires_in'];
                $UserSina->save();
            }else{
                $type = 1;
            }
        } elseif(!empty($this->input['uid'])){
            if($Sinares->uid != $this->input['uid']){
                $this->accepted = false;
            }
        }
        if($this->passes()){
            $this->biz = [
                'type' => empty($type)?"":$type,
                'uid' =>empty($Sinares->uid)?$this->input['uid']:$Sinares->uid ,
            ];
        }
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
            'type' => 'required',
            'uid' => 'required',
        ];
        $messages = [
            'type.required' => '参数错误',
            'uid.required' => '参数错误',
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '请输入信息';
            $this->accepted = false;

            $messages = $validator->errors();

            if ($messages->has('uid')) {
                $this->errors->add('subject', $messages->first('uid'));
            }

            if ($messages->has('type')) {
                $this->errors->add('detail', $messages->first('type'));
            }
        }
    }
    /**
     *
     * QQ登录解绑 .
     *
     */
    public function Qq_unbundling()
    {
        if ($this->passes()) {
            $Userqq = new UserQq();
            //查询openid是否存在
            $QQres = $Userqq->where('uid',$this->input['uid'])->delete();
            if(!$QQres){
                $this->accepted = false;
                $this->status = 500;
                $this->description = '发生一个内部错误';
            }
        }
    }
    /**
     *
     * weibo登录解绑 .
     *
     */
    public function Weibo_unbundling()
    {
        if ($this->passes()) {
            $sina = new UserSina();
            //查询openid是否存在
            $sinares = $sina->where('uid',$this->input['uid'])->delete();
            if(!$sinares){
                $this->accepted = false;
                $this->status = 500;
                $this->description = '发生一个内部错误';
            }
        }
    }
    /**
     *
     * 微信登录解绑 .
     *
     */
    public function Weixin_unbundling()
    {
        if ($this->passes()) {
            $weixin = new UserWeixin();
            //查询openid是否存在
            $weixinres = $weixin->where('uid','=',$this->input['uid'])->delete();
            if(!$weixinres){
                $this->accepted = false;
                $this->status = 500;
                $this->description = '发生一个内部错误';
            }
        }
    }
}
