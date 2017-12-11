<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\UsersRegisterRespository;
use App\Repositories\UserSessionRepository;
use App\Repositories\UserLogoutRepository;
use App\Repositories\AuthSessionRepository;
use App\Repositories\SearchUsersRepository;
use App\Repositories\QuestionsAskRepository;
use App\Repositories\UserLoginRepository;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Mews\Captcha\Facades\Captcha;
use Sesionsion;
use Cookie;
use DB;
class UsersController extends Controller
{
    /**
     * User ID
     *
     * array []
     */
    private $uid = [];

    /**
     * Users login the app
     *
     * @param void
     *
     * @return void
     */
    public function login(Request $request)
    {
        $repoSession = new UserSessionRepository();
        $repoSession->contract();
        if ($repoSession->passes()) {
            return redirect('/');
        }
        $isUpdated = $request->session()->pull('isUpdated');
        $l = Input::get('l');
        return view('login')->with('isUpdated',$isUpdated)->with('l',$l);
    }

    /**
     * User logout the app
     *
     * @param Request
     *
     * @return Response
     */
    public function logout(Request $request)
    {
        $repoSession = new UserSessionRepository();
        $repoSession->contract();

        if ($repoSession->passes()) {
            $repoLogout = new UserLogoutRepository();
            $repoLogout->contract();

            return redirect()->route('login');
        }
        return redirect()->route('login');
    }

    /**
     * 邮箱激活
     *
     * @param void
     *
     * @return void
     */
    public function emailactive(Request $request)
    {
        $repo = new UsersRegisterRespository($request->all());
        $res = $repo->emailActive();
        if (!empty($res['id'])) {
            return redirect(sprintf('/emailinfoadd'));
        }
        if(!empty($res['status'])){
            if($res['status'] == 1){
                return redirect(sprintf('/login'));
            }
            if($res['status'] == 2){
                return redirect(sprintf('/emailinfoadd'));
            }
            if($res['status'] == 3){
                return view('users.activeTimeOut');
            }
        }
    }

    /**
     * 生成图形验证码
     *
     * @param void
     *
     * @return void
     */
    public function code()
    {
        ob_clean();
        return Captcha::create();
    }

    /**
     * 手机号注册页面
     *
     * @param void
     *
     * @return void
     */
    public function registerMobile()
    {
        return view('users.check_mobile');
    }

    /**
     * 邮箱注册页面
     *
     * @param void
     *
     * @return void
     */
    public function registerEmail()
    {
        return view('users.check_email');
    }

    /**
     * 邮箱注册信息补全
     *
     * @param void
     *
     * @return void
     */
    public function emailInfoAdd(Request $request)
    {
        $id = $request->session()->get('user_register_id');
        if(empty($id)){
            return redirect('/');
        }
        return view('users.register_email')->with('id',$id);
    }

    /**
     * 手机注册信息-密码
     *
     * @param void
     *
     * @return void
     */
    public function mobileInfoAdd(Request $request)
    {
        $mobile =  $request->session()->get('user_mobile');
        if(empty($mobile)){
            return redirect('/');
        }
        return view('users.register_mobile')->with('mobile',$mobile);
    }

    /**
     * 个人信息
     *
     * @param void
     *
     * @return void
     */
    public function personalInfoAdd(Request $request)
    {
        $userRegisterId =  $request->session()->get('user_register_id');
        if(empty($userRegisterId)){
            return redirect('/');
        }
        $authinfo = $request->session()->get('authinfo');
        return view('users.register_personal',[
            'userRegisterId'=>$userRegisterId,
            'username' => empty($authinfo['display_name'])?"":$authinfo['display_name'],
        ]);
    }

    /**
     * 擅长领域
     *
     * @param void
     *
     * @return void
     */
    public function beGoodAt(Request $request)
    {
        $userRegisterId =  $request->session()->get('user_register_id');
        if(empty($userRegisterId)){
            return redirect('/');
        }
        $response = new UsersRegisterRespository();
        $goodat = $response->beGoodAt();
        return view('users.register_goodat',[
            'userRegisterId'=>$userRegisterId,
            'goodat'=>$goodat,
        ]);
    }

    /**
     * 重新发送邮件页面
     *
     * @param void
     *
     * @return void
     */
    public function sendEmailAgain()
    {
        return view('users.sendemail_again');
    }

    /**
     * 邮箱注册确认页面
     *
     * @param void
     *
     * @return void
     */
    public function mailConfirm(Request $request)
    {
        $email =  $request->session()->get('user_email');
        if(empty($email)){
            return redirect('/');
        }
        return view('users.mail_confirm')->with('email',$email);
    }

    /**
     * 第三方登录回调页面.
     *
     * @param uid
     *
     * @return array
     */
    public function authcallback(Request $request){
        //获取用户ID
        $educations = $request->session()->get('authinfo');
        if(empty($educations)){
            return redirect()->route('login');
        }
        $AuthSessionRepository = new AuthSessionRepository($educations);
        $AuthSessionRepository->contract();
        return redirect()->route('settings');
    }
    /**
     * 第三方登录注册页面.
     *
     * @param uid
     *
     * @return array
     */
    public function authregister(Request $request){
    $educations = $request->session()->get('authinfo');
        if(empty($educations)){
            return redirect()->route('login');
        }
        return view('users.auth_callback');
    }

    /**
     * Search Users
     *
     * @param void
     *
     * @return void
     */
    public function query(Request $request)
    {
        $permit = ['q'];
        $input = $request->only($permit);

        $repo = new SearchUsersRepository($input);
        $repo->contract();

        return response()->json($repo->wrap());
    }
    /**
     * Search Users
     *
     * @param void
     *
     * @return void
     */
    public function card(Request $request,$id)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid'=>$uid,
            'carduid'=>$id,
        ];
        $repo = new QuestionsAskRepository($input);
        $repo->dofunction = 'card';
        $repo->contract();
        if (! $repo->passes()) {
            throw new NotFoundHttpException();
        }
        return view('users.card',['userinfo'=>$repo->info]);
    }

}
