<?php

namespace App\Repositories;

use Carbon\Carbon;

use Cookie;
use App\Entity\User;
use App\Entity\UserSession;

use App\Utils\HttpStatus;
use Illuminate\Support\Facades\DB;

class UserSessionRepository extends Repository implements RepositoryInterface
{
    /**
     * 认证登录
     *
     * {@inheritdoc}
     */
    public function contract()
    {
        $timestamp = time();
        $userCookie = Cookie::get('user_session');

        if (null === $userCookie) {
            $this->accepted = false;
            $this->status = 401;
            $this->description = '未登录';
        }

        if ($this->passes()) {
            $userSession = UserSession::where('sid', $userCookie)->first();
            if (null === $userSession) {
                $this->accepted = false;
                $this->status = 401;
                $this->description = '请重新登录';
            }
        }

        if ($this->passes()) {
            if (1 === $userSession->remember_me && ($timestamp - $userSession->logged_in) > 86400 * 14) {
                $this->accepted = false;
                $this->status = 401;
                $this->description = '请重新登录';
            }
        }

        if ($this->passes()) {
            if (0 === $userSession->remember_me && ($timestamp - $userSession->activity) > 60 * 30) {
                $this->accepted = false;
                $this->status = 401;
                $this->description = '请重新登录';
            }
        }

        if ($this->passes()) {
            $session = UserSession::where('sid', $userCookie)->update(['activity' => $timestamp]);
            $this->biz = @unserialize(base64_decode($userSession->value));
            if (0 === $userSession->remember_me) {
                Cookie::queue('user_session', $userCookie);
            }

            $userDuration = DB::table('user_analysis')->select('online')->where('uid', $this->biz['uid'])->first();
            if(!empty($userDuration)){
                DB::table('user_analysis')->where('uid', $this->biz['uid'])->update(
                    [
                        'online' =>(int) $userDuration->online + $timestamp - $userSession->activity,
                        'activity' =>$timestamp,
                    ]
                );
            }

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
}
