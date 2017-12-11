<?php

namespace App\Repositories;

use Validator;
use App\Utils\Sms;
use App\Entity\User;
use Hash;
use Log;
use App\Entity\UserSession;
use Cookie;

class MobilePasswordRepository extends Repository implements RepositoryInterface
{


    protected $error_name = '';

    public function contract()
    {
        $this->_validator();
        if ($this->passes()) {
            $this->_updatePassword();
        }
    }

    /**
     * 返回错误码
     *
     * @return array
     */
    public function wrap()
    {
        $wrapper = [];
        if (!$this->passes()) {
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
    protected function _validator()
    {
        $rules = [
            'type' => 'in:1',
            'mobile' => 'required|regex:/^1[34578][0-9]{9}$/|exists:users,mobile',
            'password' => 'required|confirmed|regex:/^[\w_]{6,16}$/',
            'password_confirmation' => 'required|regex:/^[\w_]{6,16}$/',
            'code' => 'required',
            'template' => 'required|in:registered,retrieve,binding,updatepass',
        ];

        $message = [
            'type.in' => '修改类型数据格式有误！',
            'mobile.required' => '手机号不能为空！',
            'mobile.regex' => '手机格式不正确！',
            'password.required'=>'密码不能为空',
            'password.confirmed'=>'密码输入不一致',
            'password.regex'=>'密码为6-16位字符，只能输入英文、数字、“_”',
            'password_confirmation.required'=>'确认密码不能为空',
            'password_confirmation.regex'=>'确认密码为6-16位字符，只能输入英文、数字、“_”',
            'code.required' => '手机验证码不能为空！',
            'template.required' => '发送模板不能为空！',
            'template.in' => '发送模板数据格式有误！',

        ];

        if (!empty($this->input['type']) && $this->input['type'] == 1) {
            $rules['mobile'] = 'required|regex:/^1[34578][0-9]{9}$/|exists:users,mobile';
            $message['mobile.exists'] = '数据库中没有该手机号，请核对后再发送验证码!！';
        }
        $validator = Validator::make($this->input, $rules, $message);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->error_name = 'Bad Request';
            $this->accepted = false;
            $messages = $validator->errors();

            if ($messages->has('mobile')) {
                $this->errors->add('mobile', $messages->first('mobile'));
            }
            if ($messages->has('password')) {
                $this->errors->add('password', $messages->first('password'));
            }
            if ($messages->has('password_confirmation')) {
                $this->errors->add('password_confirmation', $messages->first('password_confirmation'));
            }
            if ($messages->has('code')) {
                $this->errors->add('code', $messages->first('code'));
            }
            if ($messages->has('template')) {
                $this->errors->add('template', $messages->first('template'));
            }
        }
        $this->_validatorMobileServer();
    }


    /**
     * 手机找回参数内部参数验证
     */
    protected function _validatorMobileServer()
    {
        if ($this->passes()) {
            $type = $this->input['template'];
            $ret = Sms::checkCode($this->input['mobile'], $type, $this->input['code']);
            if ($ret['status'] != 1) {
                $this->status = 400;
                $this->description = '参数错误';
                $this->error_name = 'Bad Request';
                $this->accepted = false;

                $this->errors->add('code', $ret['msg']);
            }
        }

    }

    /**
     * 修改密码
     */
    protected function _updatePassword()
    {
        $re = User::where('mobile', $this->input['mobile'])->update([
            'passcode' => Hash::make($this->input['password']),
            'error_num' => 0,
            'error_time' =>0,
        ]);
        if (!$re) {
            $this->status = 500;
            $this->description = '写入数据库失败';
            $this->error_name ='Internal Server Error';
            $this->accepted = false;
            return;
        }else{
            $userCookie = Cookie::get('user_session');
            UserSession::where('sid', $userCookie)->delete();
            Cookie::queue('isUpdated',1);
            Cookie::queue(Cookie::forget('user_session'));
        }
    }
}
