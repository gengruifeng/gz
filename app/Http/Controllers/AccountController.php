<?php

namespace App\Http\Controllers;

use App\Entity\Categories;
use App\Entity\ProvinceCity;
use App\Entity\UserProficiencies;
use App\Entity\UserQq;
use App\Entity\UserWeixin;
use App\Entity\UserSina;
use App\Repositories\MailBindingRepository;
use App\Repositories\UserProficienciesRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Repositories\UserInfoRepository;
use Illuminate\Support\Facades\Redirect;
class AccountController extends Controller
{

    /**
     * 账户个人信息页
     * @param Request $request
     * @return mixed
     */
    public function settings(Request $request){
        $uid = $request->security()->get('uid');
        $province = ProvinceCity::where('parentid' , 0)->get();
        $city = ProvinceCity::where('level' , 2)->get();
        $UserInfoRepository =new UserInfoRepository(Input::all());
        $userData =  $UserInfoRepository->getInfo($uid);
        return view('users.account.information')->with('province',$province)->with('city',$city)->with('userData',$userData);
    }

    /**
     * 账户安全页
     * @param Request $request
     * @return mixed
     */
    public function safety(Request $request){
        $uid = $request->security()->get('uid');
        $UserInfoRepository =new UserInfoRepository(Input::all());
        $userData =  $UserInfoRepository->getInfo($uid);
        return view('users.account.security')->with('userData',$userData);
    }

    /**
     * 账户安全页-手机修改密码页
     * @param Request $request
     * @return mixed
     */
    public function safetymobile(Request $request){
        $uid = $request->security()->get('uid');
        $UserInfoRepository =new UserInfoRepository(Input::all());
        $userData =  $UserInfoRepository->getInfo($uid);
        $mobile = DB::table('users')->select('mobile')->where('id', $uid)->first();
        if(empty($mobile->mobile)){
            return  Redirect::to('account/safety');
        }
        return view('users.account.securityphonepass')->with('userData',$userData);
    }

    /**
     * 账户安全页-邮箱修改密码页
     * @param Request $request
     * @return mixed
     */
    public function safetyemail(Request $request){
        $uid = $request->security()->get('uid');
        $UserInfoRepository =new UserInfoRepository(Input::all());
        $userData =  $UserInfoRepository->getInfo($uid);
        return view('users.account.securityemailpass')->with('userData',$userData);
    }

    /**
     * 账户安全页-设置邮箱-1
     * @param Request $request
     * @return mixed
     */
    public function setemailone(Request $request){
        $uid = $request->security()->get('uid');
        $userinfo = DB::table('users')->select('email')->where('id',$uid)->first();
        if(!empty($userinfo->email)){
            return  Redirect::to('account/safety');
        }
        return view('users.account.setemailone');
    }

    /**
     * 账户安全页-设置邮箱-2
     * @param Request $request
     * @return mixed
     */
    public function setemailtwo(Request $request){
        return view('users.account.setemailtwo')->with('email',$request->email);
    }

    /**
     * 账户安全页-设置邮箱-3
     * @param Request $request
     * @return mixed
     */
    public function setemailthree(Request $request){
        $uid = $request->security()->get('uid');
        $mailBindingRepository = new MailBindingRepository(['token' => $request->token,'uid' => $uid]);
        $mailBindingRepository->contract();
        $returnData = $mailBindingRepository->wrap();
        return view('users.account.setemailthree')->with('returnData',$returnData);
    }

    /**
     * 账户安全页-设置手机号
     * @param Request $request
     * @return mixed
     */
    public function setmobile(Request $request){
        $uid = $request->security()->get('uid');
        $mobile = DB::table('users')->select('mobile')->where('id', $uid)->first();
        if(!empty($mobile->mobile)){
            return  Redirect::to('account/safety');
        }
        return view('users.account.setPhone');
    }

    /**
     * 账户安全页-设置手机号
     * @param Request $request
     * @return mixed
     */
    public function setmobileFinish(Request $request){
        $uid = $request->security()->get('uid');
        $UserInfoRepository =new UserInfoRepository(Input::all());
        $userData =  $UserInfoRepository->getInfo($uid);
        return view('users.account.setphonefinish')->with('userData',$userData);
    }

    /**
     * 账户安全页-更换手机号-one
     * @param Request $request
     * @return mixed
     */
    public function changeMobileOne(Request $request){
        $uid = $request->security()->get('uid');
        $UserInfoRepository =new UserInfoRepository(Input::all());
        $userData =  $UserInfoRepository->getInfo($uid);
        if(!empty($userData['mobile'])){
            $ret = DB::table('sms_code')->select('send_time')->where('mobile',$userData['mobile'])->where('type','binding')->where('status', 1)->first();
            if(empty($ret->send_time) || $ret->send_time <= time()- 60 * 15){

                return  Redirect::to('account/safety');
            }
        }else{
            return  Redirect::to('account/safety');
        }
        return view('users.account.changemobileone')->with('userData',$userData);
    }

    /**
     * 账户安全页-更换手机号-two
     * @param Request $request
     * @return mixed
     */
    public function changeMobileTwo(Request $request){
        $uid = $request->security()->get('uid');
        $UserInfoRepository =new UserInfoRepository(Input::all());
        $userData =  $UserInfoRepository->getInfo($uid);
        if(!empty($userData['mobile'])){
            $ret = DB::table('sms_code')->select('send_time','status')->where('mobile',$userData['mobile'])->where('type','binding')->first();

            if(empty($ret->send_time) || $ret->send_time <= time()- 60 * 15){
                return  Redirect::to('account/safety');
            }

            if($ret->status != 2){
                return  Redirect::to('account/changemobileone');
            }
        }else{
            return  Redirect::to('account/safety');
        }
        return view('users.account.changemobiletwo');
    }

    /**
     * 账户安全页-更换手机号-three
     * @param Request $request
     * @return mixed
     */
    public function changeMobileThree(Request $request){
        $uid = $request->security()->get('uid');
        $UserInfoRepository =new UserInfoRepository(Input::all());
        $userData =  $UserInfoRepository->getInfo($uid);
        return view('users.account.changemobilethree')->with('userData',$userData);
    }


    /**
     * 用户擅长页
     * @param Request $request
     * @return mixed
     */
    public function proficiency(Request $request){
        $uid = $request->security()->get('uid');
        $userProficienciesRepository = new UserProficienciesRepository(Input::all());
        $userTags = $userProficienciesRepository->getUserTags($uid);
        if(!$userTags){
            return  Redirect::to('account/setproficiency');
        }
        return view('users.account.usertags')->with('userTags',$userTags);
    }

    /**
     * 用户擅长页-设置
     * @param Request $request
     * @return mixed
     */
    public function setProficiency(){
        $UserInfoRepository =new UserInfoRepository();
        $proficiencyInfo =  $UserInfoRepository->proficiencyInfo();
        return view('users.account.setusertags')->with('proficiencyInfo',$proficiencyInfo);
    }

    /**
     * 用户头像
     */
    public function avatar(Request $request){
        $uid = $request->security()->get('uid');
        $avatar = DB::table('users')->select('avatar','id')->where('id', $uid)->first();
        return view('users.account.useravatar')->with('avatar',$avatar);
    }


    //绑定第三方账号
    public function oauth(Request $request){
        $uid = $request->security()->get('uid');
        // 判断用户绑定了那些第三方登录的账号
        $qq = UserQq::select('nickname')->where('uid',$uid)->get()->toarray();
        $weixin = UserWeixin::select('nickname')->where('uid',$uid)->get()->toarray();
        $sina = UserSina::select('screen_name')->where('uid',$uid)->get()->toarray();
        //第三方名称，用来展示到页面
        $name = [
            'qq' => empty($qq[0])?'':$qq[0]['nickname'],
            'weixin' => empty($weixin[0])?'':$weixin[0]['nickname'],
            'sina' => empty($sina[0])?'':$sina[0]['screen_name'],
            'uid'=> $uid,
        ];
        return view('users.account.account_oauth')->with('name',$name);
    }

    /**
     * 用户头像
     */
    public function showavatar(Request $request,$with,$id){
        $avatar = DB::table('users')->select('avatar')->where('id', $id)->first();
        header('Content-type: image/jpg');
        ob_end_clean();
        if(!in_array($with,array(120,30,60))){
            $image = file_get_contents('images/message/messageIcon_r3_c1.png');
            echo $image;exit;
        }

        if(empty($avatar->avatar)){
            $image = file_get_contents('images/message/messageIcon_r3_c1.png');
            echo $image;exit;
        }
        $date = substr($avatar->avatar,0,6);
        if(file_exists('uploads/home/avatar/'.$with.'/'.$date.'/'.$avatar->avatar)){

            $image = file_get_contents('uploads/home/avatar/'.$with.'/201608/'.$avatar->avatar);

            echo $image;exit;
        }else{
            $image = file_get_contents('images/message/messageIcon_r3_c1.png');
            echo $image;exit;

        }
    }
}
    