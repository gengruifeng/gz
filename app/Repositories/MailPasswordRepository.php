<?php

namespace App\Repositories;

use Validator;
use App\Entity\User;
use Hash;
use Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Entity\UserSession;
use Cookie;
use Log;

class MailPasswordRepository extends Repository implements RepositoryInterface
{

    protected $error_name = '';

    protected $mail;

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
            'code' => 'required',
            'password'=>'required|confirmed|regex:/^[\w_]{6,16}$/',
            'password_confirmation' => 'required|regex:/^[\w_]{6,16}$/',
        ];

        $message = [
            'code.required' => 'token值不能为空！',
            'password.required'=>'密码不能为空',
            'password.confirmed'=>'密码输入不一致',
            'password.regex'=>'密码为6-16位字符，只能输入英文、数字、“_”',
            'password_confirmation.required'=>'确认密码不能为空',
            'password_confirmation.regex'=>'确认密码为6-16位字符，只能输入英文、数字、“_”',
        ];

        $validator = Validator::make($this->input, $rules, $message);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->error_name = 'Bad Request';
            $this->accepted = false;
            $messages = $validator->errors();

            if ($messages->has('code')) {
                $this->errors->add('code', $messages->first('code'));
            }
            if ($messages->has('password')) {
                $this->errors->add('password', $messages->first('password'));
            }
            if ($messages->has('password_confirmation')) {
                $this->errors->add('password_confirmation', $messages->first('password_confirmation'));
            }

        }
        $this->_validatorMailServer();
    }


    /**
     * 邮箱找回参数内部参数验证
     */
    protected function _validatorMailServer()
    {
        if ($this->passes()) {
            try {
                $token = Crypt::decrypt($this->input['code']);
                $tokenArr = explode('_grf_',$token);
                if(time() - (int)$tokenArr[1] > 15*60){
                    throw new DecryptException('邮箱token值已经超时，请重新发送邮件验证！',400);
                }
                $ret = User::where('email',$tokenArr[0])->get()->first();
                if(!empty($ret)){
                    $this->mail = $tokenArr[0];
                }else{
                    throw new DecryptException('token值有误，请重新发送请重新发送邮件验证短信验证！',400);
                }

            } catch (DecryptException $e) {
                $msg = $e->getCode() != 0 ? $e->getMessage(): 'token值未能解密!';
                $this->status = 400;
                $this->description = '参数错误';
                $this->error_name = 'Bad Request';
                $this->accepted = false;
                $this->errors->add('token', $msg);
            }
        }

    }

    /**
     * 修改密码
     */
    protected function _updatePassword()
    {
        $re = User::where('email', $this->mail)->update([
            'passcode' => Hash::make($this->input['password']),
            'error_num' => 0,
            'error_time' =>0,
        ]);
        if (!$re) {
            $this->status = 500;
            $this->description = '写入数据库失败';
            $this->error_name = 'Internal Server Error';
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
