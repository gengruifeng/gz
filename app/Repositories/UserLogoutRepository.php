<?php

namespace App\Repositories;

use Carbon\Carbon;

use Log;
use Validator;
use Cookie;
use App\Entity\UserSession;

use App\Utils\HttpStatus;

class UserLogoutRepository extends Repository implements RepositoryInterface
{
    /**
     * 认证退出登录
     *
     * {@inheritdoc}
     */
    public function contract()
    {
        $userCookie = Cookie::get('user_session');

        UserSession::where('sid', $userCookie)->delete();

        Cookie::queue(Cookie::forget('user_session'));
    }

    /**
     * Wrap the contract result to JSON object.
     *
     * {@inheritdoc}
     */
    public function wrap()
    {
    }
}
