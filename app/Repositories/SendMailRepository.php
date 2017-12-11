<?php

namespace App\Repositories;

use Validator;
use App\Utils\Email;
use Crypt;
use Log;
use App\Entity\User;

class SendMailRepository extends Repository implements RepositoryInterface
{

    protected $error_name = '';

    public function contract()
    {

        $this->_validator();
        if ($this->passes()) {
            $this->_sendEmail();
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
    protected function _validator(){
        $rules = [
            'type' => 'in:1',
            'isTable' => 'in:1,2',
            'mail' => 'required|email',
            'code' => "required_if:type,1|captcha",
            'template' => 'required|in:updatepassword,bindingmail,activeuser,setmail',
        ];

        $message = [
            'type.in' => '发送类型数据格式有误！',
            'isTable.in' => '是否检索表数据格式有误！',
            'mail.required' => '邮箱不能为空！',
            'mail.email' => '邮箱格式不正确！',
            'code.required_if' => "验证码不能为空！",
            'code.captcha' => "验证码错误！",
            'template.required' => '发送模板不能为空！',
            'template.in' => '发送模板数据格式有误！',

        ];
        if (!empty($this->input['isTable']) && $this->input['isTable'] == 1) {
            $rules['mail'] = 'required|email|exists:users,email';
            $message['mail.exists'] = '没有找到该邮箱！';
        }else if (!empty($this->input['isTable']) && $this->input['isTable'] == 2){
            $ret = User::where('email',$this->input['mail'])->get()->first();
            if(!empty($ret)){
                $this->status = 400;
                $this->description = '参数错误';
                $this->error_name = 'Bad Request';
                $this->accepted = false;
                $this->errors->add('mail', '邮箱已经存在！');
            }
        }
        $validator = Validator::make($this->input, $rules, $message);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->error_name = 'Bad Request';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('type')) {
                $this->errors->add('type', $messages->first('type'));
            }

            if ($messages->has('mail')) {
                $this->errors->add('mail', $messages->first('mail'));
            }

            if ($messages->has('code')) {
                $this->errors->add('code', $messages->first('code'));
            }
            if ($messages->has('isTable')) {
                $this->errors->add('isTable', $messages->first('isTable'));
            }
            if ($messages->has('template')) {
                $this->errors->add('template', $messages->first('template'));
            }
        }
    }

    /**
     * 发送邮件
     * @param $mail
     */
    protected function _sendEmail(){
        //根据模板id 重置邮件模板变量
        $function = $this->input['template'];
        $datas = $this->$function();
        $result = Email::send($this->input['mail'],$this->input['template'],$datas,config('mail.template.'.$this->input['template']));
        if($result['status']!= 1){
            $this->status = 400;
            $this->description = $result['msg'];
            $this->accepted = false;
        }
    }

    /**
     * 组合updatepassword模板数据
     */
    protected function updatepassword(){
        $ulr = '/forgot/emailfill/token/'.Crypt::encrypt($this->input['mail'].'_grf_'.time());
        return ['url' => $ulr];
    }

    /**
     * 组合bindingmail模板数据
     */
    protected function bindingmail(){
        $ulr ='/mailbinding/token/'.Crypt::encrypt($this->input['mail'].'_grf_'.time());
        $email = $this->input['mail'];
        return ['url' => $ulr, 'email' => $email];
    }

    /**
     * 组合activeeuser模板数据
     */
    protected function activeuser(){
        $url = '/users/emailactive?code='.Crypt::encrypt($this->input['mail'].'_grf_'.time());
        return ['url' => $url];
    }

    /**
     * 组合setmail模板数据
     */
    protected function setmail(){
        $ulr = '/account/setemailthree/token/'.Crypt::encrypt($this->input['mail'].'_grf_'.time());
        return ['url' => $ulr,'email' => $this->input['mail']];
    }
}
