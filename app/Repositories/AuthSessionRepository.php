<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

use Log;
use Validator;
use Cookie;
use Request;

use App\Entity\UserSession;
use App\Utils\HttpStatus;

use DB;

class AuthSessionRepository extends Repository implements RepositoryInterface
{

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
     * 第三方用户持久登录
     *
     * {@inheritdoc}
     */
    public function contract()
    {
        $this->sessionId = $this->generateSessionId();

        if ($this->passes()) {
            $attributes = [
                'uid' => $this->input['uid'],
            ];
            $Userres = DB::table('users')->where('id',$this->input['uid'])->first();
            //判断用户是否绑定手机号
            if(empty($Userres->mobile) && empty($Userres->email)){
                $this->accepted = false;
                $this->biz = $attributes;
                return ;
            }
            $session = new UserSession;

            $session->sid = $this->sessionId;
            $session->value = base64_encode(serialize($attributes));
            $session->activity = $session->logged_in = time();
            $session->remember_me = 1;
            $session->ip = Request::ip();

            $session->save();

            Cookie::queue('user_session', $this->sessionId,1440 * 15);
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
     * Get a new, random session ID.
     *
     * @return string
     */
    protected function generateSessionId()
    {
        return sha1(uniqid('', true).Str::random(25).microtime(true));
    }
}
