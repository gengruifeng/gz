<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use Carbon\Carbon;

use Log;
use Hash;
use Validator;
use Cookie;
use Request;

use App\Entity\User;
use App\Entity\UserSession;

use App\Utils\HttpStatus;

class UserLoginRepository extends Repository implements RepositoryInterface
{
    const AUTH_TYPE_MOBILE = 'mobile';
    const AUTH_TYPE_EMAIL = 'email';

    /**
     * Authentication Type
     *
     * @var string
     */
    private $authType;

    /**
     * User Session ID
     *
     * @var string
     */
    protected $sessionId;

    /**
     * 提问业务
     *
     * {@inheritdoc}
     */
    public function contract()
    {
        $this->validate();
        $this->sessionId = $this->generateSessionId();
        if ($this->passes()) {
            $credential = User::where($this->authType, $this->input['auth_name'])->first();
            if (null === $credential) {
                $this->accepted = false;
                $this->status = 401;
                $this->description = '登录手机号码或邮箱不存在';
            }
        }

        if ($this->passes()) {
            if (5 <= $credential->error_num) {
                if((int)time()-(int)$credential->error_time < 3*3600){
                    $this->accepted = false;
                    $this->status = 403;
                    $this->description = '登录密码出错已达上限将锁定密码3小时，请<a class="newlink" href="/forgot/mobile">重置登录密码</a>后登录';
                }else{
                    $credential->error_num = 0;
                }

            }elseif((int)time()-(int)$credential->error_time > 3*3600){
                $credential->error_num = 0;
            }
        }

        if ($this->passes()) {
            if (! Hash::check($this->input['passcode'], $credential->passcode)) {
                $credential->error_num+=1;
                $credential->error_time = time();
                $credential->save();
                if (5 <= $credential->error_num) {
                    $this->accepted = false;
                    $this->status = 403;
                    $this->description = '登录密码出错已达上限将锁定密码3小时，请<a class="newlink" href="/forgot/mobile">重置登录密码</a>后登录';
                }else{
                    $this->accepted = false;
                    $this->status = 401;
                    $this->description = '帐号或密码不正确，还有'.(5-$credential->error_num).'次机会。'.'您还可以：<a class="newlink" href="/forgot/mobile">重置登录密码</a>';
                }
            }
        }

        if ($this->passes()) {
            if (1 === $credential->disabled) {
                $this->accepted = false;
                $this->status = 403;
                $this->description = '账户已禁用';
            }
        }
        if ($this->passes()) {
            if ("" === $credential->display_name) {
                $this->accepted = false;
                $this->status = 428;
                $this->description = '账户信息不全';
                session([
                    'user_register_id'=>$credential->id,
                ]);
            }
        }
        if ($this->passes()) {
            $this->biz = $credential->toArray();
            $attributes = [
                'uid' => $this->biz['id']
            ];
            $session = new UserSession;

            $session->sid = $this->sessionId;
            $session->value = base64_encode(serialize($attributes));
            $session->activity = $session->logged_in = time();
            $session->remember_me = '1' === $this->input['remember_me'] ? 1 : 0;
            $session->ip = Request::ip();
            $session->save();

            $credential->error_num =0;
            $credential->error_time = 0;
            $credential->save();
            Cookie::queue('user_session', $this->sessionId, '0' === $this->input['remember_me'] ? 30 : 1440 * 14);
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
     * Validate the input
     *
     * @param void
     *
     * @return void
     */
    private function validate()
    {
        $rules = [
            'auth_name' => 'required|regex:/^[0-9a-zA-Z@_\.]+$/',
            'passcode' => 'required|regex:/^[\w_]{6,16}$/',
            'remember_me' => 'regex:/^[01]$/',
        ];
        $messages = [
            'auth_name.required' => '请填写帐号名称',
            'auth_name.regex' => '账号名称不正确',
            'passcode.required' => '请填写密码',
            'passcode.regex' => '密码格式错误',
            'remember_me.regex' => '请选择是否记住我'
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '请输入信息';
            $this->accepted = false;

            $messages = $validator->errors();

            if ($messages->has('auth_name')) {
                $this->errors->add('auth_name', $messages->first('auth_name'));
            }

            if ($messages->has('passcode')) {
                $this->errors->add('passcode', $messages->first('passcode'));
            }

            if ($messages->has('remember_me')) {
                $this->errors->add('remember_me', $messages->first('remember_me'));
            }
        }

        if (1 === preg_match('/^1[34578][0-9]{9}$/', $this->input['auth_name'])) {
            $this->authType = self::AUTH_TYPE_MOBILE;
        } elseif (filter_var($this->input['auth_name'], FILTER_VALIDATE_EMAIL)) {
            $this->authType = self::AUTH_TYPE_EMAIL;
        } else {
            $this->status = 400;
            $this->description = '手机号码或邮箱无效';
            $this->accepted = false;

            $this->errors->add('auth_name', '手机号码或邮箱无效');
        }
    }

    /**
     * Get a new, random session ID.
     *
     * @return string
     */
    protected function generateSessionId()
    {
        return sha1(uniqid('', true).Str::random(25).microtime(true));
    }
}
