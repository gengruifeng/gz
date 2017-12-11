<?php

namespace App\Repositories;

use Validator;
use Log;
use App\Entity\User;
use Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class MailBindingRepository extends Repository implements RepositoryInterface
{

    protected $msg = '';

    protected $email = '';

    /**
     * 通过token值绑定用户邮箱
     */
    public function contract()
    {
        $this->_validator();
        if ($this->passes()) {
            $this->_updateMail();
        }
    }
    /**
     * 返回结果
     * @return array
     */
    public function wrap()
    {
        $wrapper = [
            'email' => $this->email,
            'msg' => $this->msg,
        ];
        return $wrapper;
    }

    /**
     * 验证token值
     */
    protected function _validator(){

        $rules = [
            'token' => 'required',
        ];

        $message = [
            'token.required' => 'token值不能为空！',
        ];

        $validator = Validator::make($this->input, $rules, $message);

        if ($validator->fails()) {
            $this->accepted = false;
            $messages = $validator->errors();
            $this->msg = $messages->first('token');
        }
        $this->_validatorMailServer();

    }

    /**
     * 邮箱找回参数内部参数验证
     */
    protected function _validatorMailServer()
    {
        $uid = $this->input['uid'];
        if ($this->passes()) {
            try {
                $token = Crypt::decrypt($this->input['token']);
                $tokenArr = explode('_grf_',$token);
                $ret = User::select('id','email')->where('email',$tokenArr[0])->get()->first();
                if(!empty($ret) && $ret->id != $uid){
                    $this->email = $tokenArr[0];
                    throw new DecryptException('邮箱已经存在，请重新绑定邮件！',400);
                }elseif(!empty($ret) && $ret->id == $uid){
                    $this->email = $tokenArr[0];

                    throw new DecryptException('完成绑定邮箱！',200);
                }else{
                    $this->email = $tokenArr[0];
                }
                if(time() - (int)$tokenArr[1] > 15*60){
                    $this->email = $tokenArr[0];
                    throw new DecryptException('邮箱token值已经超时，请重新发送邮件验证！',400);
                }
            } catch (DecryptException $e) {
                $this->msg = $e->getCode() != 0 ? $e->getMessage(): 'token值未能解密!';
                $this->accepted = false;
            }
        }

    }

    /**
     * 根据uid更新邮箱地址
     */
    protected function _updateMail(){
        $uid = $this->input['uid'];
        $ret = User::where('id', $uid)->update(['email' => $this->email]);
        if(!$ret){
            $this->msg = '绑定失败，请重新绑定！';
            $this->accepted = false;
        }
    }
}
