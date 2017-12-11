<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Validator;
use Log;
use App\Entity\User;
use App\Utils\Sms;

class MobileBindingRepository extends Repository implements RepositoryInterface
{

    protected $error_name;

    /*
     * 具体控制
     */
    public function contract(){

        $this->_validatorMobile();
        $this->_validatorMobileServer();
        if($this->input['type'] == 1){
            $this->_validatorEffective();
            if($this->passes()){
                $this->_uodateInfo();
            }
        }

    }

    /*
     *返回数据
     */
    public function wrap(){
        $wrapper = [];
        if (! $this->passes()) {
            $wrapper = [
                'error_id' => $this->status,
                'description' => $this->description,
                'error_name' => $this->error_name,
                'errors' => $this->errors->getErrors()
            ];
        }
        return $wrapper;
    }

    /**
     * 参数层验证
     */
    protected function _validatorMobile(){
        $rules = [
            'type' => 'required|in:1,2',
            'mobile' => 'required|regex:/^1[34578][0-9]{9}$/',
            'code' => 'required',
        ];

        $message = [
            'mobile.required' =>'手机不能为空！',
            'code.required' =>'验证码不能为空！',
            'mobile.regex' =>'手机格式不正确！',
            'type.required' =>'类型不能为空！',
            'type.in' =>'类型数据格式错误！',

        ];
        $validator = Validator::make($this->input, $rules,$message);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->error_name = 'Bad Request';
            $this->accepted = false;
            $messages = $validator->errors();

            if ($messages->has('mobile')) {
                $this->errors->add('mobile', $messages->first('mobile'));
            }

            if ($messages->has('code')) {
                $this->errors->add('code', $messages->first('code'));
            }

            if ($messages->has('type')) {
                $this->errors->add('type', $messages->first('type'));
            }


        }
    }

    /**
     * 数据库层验证
     */
    protected function _validatorMobileServer(){
        if($this->passes()) {
            $ret = User::where('mobile',$this->input['mobile'])->get()->first();
            if(!empty($ret) && $this->input['type'] == 1){
                $this->status = 400;
                $this->description = '参数错误';
                $this->error_name = 'Bad Request';
                $this->accepted = false;
                $this->errors->add('mobile', '手机已经存在！');
                return 0 ;
            }
            //验证码验证
            $type = 'binding';
            $ret = Sms::checkCode($this->input['mobile'], $type,$this->input['code']);
            if($ret['status'] != 1){
                $this->status = 400;
                $this->description = '参数错误';
                $this->error_name = 'Bad Request';
                $this->accepted = false;

                $this->errors->add('code', $ret['msg']);
            }
        }
    }

    /**
     * 根据id更新手机号
     * @param $type
     * @param $info
     */
    protected function _uodateInfo(){
        $uid = $this->input['uid'];
        $re = User::where('id', $uid)->update(['mobile' => $this->input['mobile']]);

        if(!$re){
            $this->status = 500;
            $this->error_name ='Internal Server Error';
            $this->description = '写入数据库失败';
            $this->accepted = false;
            return ;
        }
    }


    /**
     * 验证接口有效性
     */
    protected function _validatorEffective(){
        $uid = $this->input['uid'];
        $userData =  DB::table('users')->select('mobile')->where('id', $uid)->first();
        if(!empty($userData->mobile)){
            $ret = DB::table('sms_code')->select('send_time')->where('mobile',$userData->mobile)->where('type','binding')->where('status', 2)->first();

            if(empty($ret->send_time) || $ret->send_time <= time()- 60 * 15){
                $this->status = 403;
                $this->error_name ='Forbidden';
                $this->description = '验证过时，请重新操作';
                $this->errors->add('code', '验证过时，请重新操作');
                $this->accepted = false;
            }
        }
    }

}
