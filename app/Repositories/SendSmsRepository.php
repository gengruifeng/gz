<?php

namespace App\Repositories;

use Validator;
use Log;
use App\Utils\Sms;
use App\Entity\User;

class SendSmsRepository extends Repository implements RepositoryInterface
{


    protected $error_name = '';

    public function contract(){
        $this->_validator();

        if($this->passes()){
            $this->_sendMobile();
        }
    }
    /**
     * 返回错误码
     *
     * @return array
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
     * 立即找回密码参数层验证
     *
     */
    protected function _validator(){
        $rules = [
            'type' => 'in:1',
            'isTable' => 'in:1,2',
            'template' => "required|in:registered,retrieve,binding,updatepass",
            'mobile' => 'required|regex:/^1[34578][0-9]{9}$/',
            'code' => "required_if:type,1|captcha",
        ];

        $message = [
            'type.in' =>'发送类型数据格式有误！',
            'isTable.in' =>'是否检索表数据格式有误！',
            'template.required' =>'发送模板不能为空！',
            'template.in' =>'发送模板数据格式有误！',
            'mobile.required' =>'手机不能为空！',
            'mobile.regex' =>'手机格式不正确！',
            'code.required_if' => "验证码不能为空！",
            'code.captcha' => "验证码错误！",

        ];
        if(!empty($this->input['isTable']) && $this->input['isTable'] == 1){
            $rules['mobile'] = 'required|regex:/^1[34578][0-9]{9}$/|exists:users,mobile';
            $message['mobile.exists'] = '没有找到该手机号！';
        }else if (!empty($this->input['isTable']) && $this->input['isTable'] == 2){
            $ret = User::where('mobile',$this->input['mobile'])->get()->first();
            if(!empty($ret)){
                $this->status = 400;
                $this->description = '参数错误';
                $this->error_name = 'Bad Request';
                $this->accepted = false;
                $this->errors->add('mobile', '该手机号已经存在！');
            }
        }
        $validator = Validator::make($this->input, $rules,$message);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->error_name = 'Bad Request';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('type')) {
                $this->errors->add('type', $messages->first('type'));
            }

            if ($messages->has('template')) {
                $this->errors->add('template', $messages->first('template'));
            }

            if ($messages->has('mobile')) {
                $this->errors->add('mobile', $messages->first('mobile'));
            }

            if ($messages->has('code')) {
                $this->errors->add('code', $messages->first('code'));
            }
            if ($messages->has('isTable')) {
                $this->errors->add('isTable', $messages->first('isTable'));
            }
        }
    }

    /**
     * 发送手机
     * @param $mobile
     * @return bool or array
     */
    protected function _sendMobile(){
        $code = rand(100000,999999);
        $ret = Sms::send($this->input['mobile'],[$code,15],$this->input['template']);
        if($ret['status'] == 1){
            //验证码发送成功
            return true;
        }else{
            $this->status = 500;
            $this->description = $ret['msg'];
            $this->error_name = 'Bad Request';
            $this->accepted = false;
            $this->errors->add('mobile', $ret['msg']);

        }
    }
}
