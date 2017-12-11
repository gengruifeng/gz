<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;

use Cookie;
use Crypt;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

use App\Repositories\UserLoginRepository;
use App\Repositories\UserLogoutRepository;
use App\Repositories\UserSessionRepository;
use App\Repositories\UsersRegisterRespository;

class UsersController extends Controller
{
    /**
     * Strong parameters
     *
     * @var array
     */
    private $permit = [
        'auth_name',
        'passcode',
        'remember_me',
    ];
    /**
     * Strong parameters
     *
     * @var array
     */
    private $phone = [];


    /**
     * Users login the app
     *
     * @param Request
     *
     * @return Response
     */
    public function login(Request $request)
    {

        $permit = [
            'auth_name',
            'passcode',
            'remember_me',
        ];
        $input = $request->only($permit);

        $repoSession = new UserSessionRepository();
        $repoSession->contract();

        if ($repoSession->passes()) {
            return response('');
        }

        $repoLogin = new UserLoginRepository($input);
        $repoLogin ->contract();

        if (! $repoLogin->passes()) {
            return response()->json($repoLogin->wrap(), $repoLogin->status);
        }

        return response('');
    }

    /**
     * 注册
     *
     * @param Illuminate\Http\Request
     *
     * @return Response
     */
    public function register(Request $request)
    {
        $input = $request->except('_token');
        $repo = new UsersRegisterRespository($input);
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        $request->session()->forget('authinfo');
        return  response('');
    }
    /**
     * 完善个人信息
     *
     * @param Illuminate\Http\Request
     *
     * @return Response
     */
    public function information(Request $request)
    {
        $authinfo = $request->session()->get('authinfo');
        $authinfo['mobile'] = Input::get('mobile');
        $authinfo['password'] = Input::get('password');
        $authinfo['mobMsg'] = Input::get('mobMsg');
        $authinfo['referral_code'] = Input::get('referral_code');
        $authinfo['registerType'] = Input::get('registerType');
        $repo = new UsersRegisterRespository($authinfo);
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        $request->session()->put('user_register_id',$repo->biz['uid']);
        $request->session()->put('authinfo',$repo->biz);
        return  response()->json('');
    }


    /**
     * 名号是否重复
     *
     * @param Illuminate\Http\Request
     *
     * @return Response
     */
    public function checkDisplayName(Request $request)
    {
        $repo = new UsersRegisterRespository();
        $result = $repo->checkDisplayName($request->only('display_name'));
        $isExist = [];
        if($result){
            $isExist['isExist'] = 1;
        }else{
            $isExist['isExist'] = 2;
        }

        return response()->json($isExist);
    }

    /**
     * get user info
     *
     * @param Request
     *
     * @return Response
     */
    public function getinfo(Request $request)
    {
        $uid = $request->security()->get('uid');
        if(empty($uid)){
            return response()->json(['error_id' => 403, 'description' => '未登录', 'error_name' => ''], 403);
        }
        $info = DB::table('users')->select('id' ,'display_name', 'avatar')->where('id',$uid)->first();

        if(empty($info)){
            return response()->json(['error_id' => 403, 'description' => '未登录', 'error_name' => ''], 403);
        }

        return response(['uid' => $info->id ,'display_name' => !empty($info->display_name)?$info->display_name :'head.png', 'avatar' => $info->avatar]);
    }
}
