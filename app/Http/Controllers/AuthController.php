<?php

namespace App\Http\Controllers;

use App\Repositories\AuthRepository;
use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

use App\Utils\Upload;

use Socialite;
class AuthController extends Controller
{
    /**
     * 访问第三方接口
     *
     * $service 第三方名称 例： QQ 、新浪、微信
     */
    public function redirectToProvider(Request $request,$service)
    {
        return Socialite::with($service)->redirect();
    }
    /**
     * 访问第三方回调函数
     *
     * $service 第三方名称 例： QQ 、新浪、微信
     */
    public function handleProviderCallback(Request $request,$service)
    {
        if(!empty(Input::get('error'))){
            return redirect()->route('login');
        }
        $uid = $request->security()->get('uid');
        $user = Socialite::driver($service)->user();
        //QQ第三方登录，获取用户信息

        if($service == "qq"){
//            $client = new Client(['verify' => false]);  //忽略SSL错误
//            $response = $client->get($user->avatar);  //保存远程url到文件
//            dd($response);
            //取出所需要的信息
            preg_match_all("/[\x{4e00}-\x{9fa5}A-Za-z0-9_\-]+/u",$user->user['nickname'],$arr);
            $nickname = null;
            foreach ($arr[0] as $val){
                $nickname.=$val;
            }
            //$a = implode(" ",$arr);
            $userInfo = [
                'nickname' => $nickname,
                'qqname' =>$user->user['nickname'],
                'openid' => $user->id,
                'gender' => $user->user['gender'],
                'access_token' => $user->token,
                'refresh_token' => $user->refreshToken,
                'avatar' => $user->avatar,
                'figureurl'=> $user->user['figureurl'],
                'type' => $service,
                'uid' => $uid,
            ];
        }
        //微信第三方登录，获取用户信息
        if($service == "weixinweb"){
            //取出所需要的信息
            preg_match_all("/[\x{4e00}-\x{9fa5}A-Za-z0-9_\-]+/u",$user->user['nickname'],$arr);
            $nickname = null;
            foreach ($arr[0] as $val){
                $nickname.=$val;
            }
            //$a = implode(" ",$arr);
            $userInfo = [
                'nickname' => $nickname,
                'weixinname' =>$user->user['nickname'],
                'openid' => $user->id,
                'headimgurl' => $user->user['headimgurl'],
                'access_token' => $user->token,
                'refresh_token' => $user->refreshToken,
                'sex' => $user->user['sex']=$user->user['sex']==1?'男':'女',
                'province' => $user->user['province'],
                'city' => $user->user['city'],
                'country' => $user->user['country'],
                'unionid' => $user->user['unionid'],
                'type' => $service,
                'uid' => $uid,
            ];
        }
        //微博第三方登录，获取用户信息
        if($service == "weibo"){
            //判断性别
            if($user->user['gender']!='m' && $user->user['gender']!='f'){
                $user->user['gender'] = '保密';
            }else{
                $user->user['gender']=$user->user['gender']=='m'?'男':'女';
            }
            //取出所需要的信息
            $userInfo = [
                'screen_name' => $user->user['screen_name'],
                'nickname' => $user->user['screen_name'],
                'sinaid' => $user->id,
                'expires_in' => $user->accessTokenResponseBody['expires_in'],
                'access_token' => $user->token,
                'refresh_token' => $user->refreshToken,
                'gender' => $user->user['gender'],
                'location' => $user->user['location'],
                'description' => $user->user['description'],
                'blog_url' => $user->user['url'],
                'profile_image_url' => $user->user['profile_image_url'],
                'type' => $service,
                'uid' => $uid,
            ];
        }
        $AuthRepository = new AuthRepository($userInfo);
        $uid = $AuthRepository->contract();
        if (! $AuthRepository->passes()) {
            echo "<script>alert('此账号已经被绑定啦!');location.href='/account/oauth';</script>";
            exit;
        }
        if($uid['type'] == 1){
             $request->session()->put('authinfo', $userInfo);
             return redirect()->route('authregister');
        }
        $userInfo['uid'] = $uid['uid'];
        $request->session()->put('authinfo', $userInfo);
        //绑定 and 登录
        return redirect()->route('authcallback');





    }
}