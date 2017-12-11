<?php

namespace App\Http\Controllers\Ajax;

use App\Repositories\UserInfoRepository;
use App\Repositories\AuthRepository;
use App\Repositories\MobileBindingRepository;

use App\Repositories\UserProficienciesRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Utils\Upload;

class AccountController extends Controller
{

    /**
     * 擅长领域提交
     * @return json
     */
    public function doSave(Request $request){
        $uid = $request->security()->get('uid');
        $input = Input::all();
        $input['uid'] = $uid;
        $UserProficienciesRepository =new UserProficienciesRepository($input);
        $UserProficienciesRepository->dofunction='subUserTags';
        $UserProficienciesRepository->contract();
        if (! $UserProficienciesRepository->passes()) {
            return response()->json($UserProficienciesRepository->wrap(), $UserProficienciesRepository->status);
        }
        return response('');
    }

    /**
     * 用户信息提交
     * @return json
     */
    public function subUserInfo(Request $request){
        $uid = $request->security()->get('uid');
        $permit = [
            'display_name',
            'occupation',
            'slogan',
            'gender',
            'province',
            'city',
            'company',
            'position',
            'school',
            'department',
            'birthday',
        ];
        $input = $request->only($permit);
        $input['uid'] = $uid;
        $UserInfoRepository =new UserInfoRepository($input);
        $UserInfoRepository->contract();
        if (! $UserInfoRepository->passes()) {
            return response()->json($UserInfoRepository->wrap(), $UserInfoRepository->status);
        }
        return response('');
    }

    /**
     * 擅长领域-提交分类
     * @return json
     */
    public function subcategory(){
        $userProficienciesRepository = new UserProficienciesRepository(Input::all());
        $userProficienciesRepository ->dofunction = 'subcategory';
        $userProficienciesRepository->contract();

        if (! $userProficienciesRepository->passes()) {
            return response()->json($userProficienciesRepository->wrap(), $userProficienciesRepository->status);
        }
        return response()->json($userProficienciesRepository->returnData(), $userProficienciesRepository->status);
    }

    /**
     * 擅长领域-提交用户标签
     * @return json
     */
    public function subUserTags(Request $request){
        $uid = $request->security()->get('uid');
        $input = Input::all();
        $input['uid'] = $uid;
        $userProficienciesRepository = new UserProficienciesRepository($input);
        $userProficienciesRepository ->dofunction = 'subUserTags';
        $userProficienciesRepository->contract();

        if (! $userProficienciesRepository->passes()) {
            return response()->json($userProficienciesRepository->wrap(), $userProficienciesRepository->status);
        }
        return response('');
    }

    /**
     * 解绑第三方账号
     *
     * @return json
     *
     */
    public function deloauth(Request $request){
        $AuthRepository =new AuthRepository(Input::all());
        $AuthRepository->contract();
        if (! $AuthRepository->passes()) {
            return response()->json($AuthRepository->wrap(), $AuthRepository->status);
        }
        return response('');
    }

    /**
     * 用户头像
     */
    public function avatarupload(Request $request){

        $permit = ['img'];
        $input = $request->only($permit);
        $newName = '';
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $input['img'], $result)){
            $type = $result[2];
            $newName = date('YmdHis').rand(1000,9999).'.'.$type;
            $path = 'avatars';
            $path120 = $path.'/120/';

            if(!is_dir($path120)){
                mkdir($path120,0777,true);
            }

            $path30 = $path.'/30/';
            if(!is_dir($path30)){
                mkdir($path30,0777,true);
            }
            $path60 = $path.'/60/';
            if(!is_dir($path60)){
                mkdir($path60,0777,true);
            }
            $new_file30 = "avatars/30/{$newName}";
            $new_file60 = "avatars/60/{$newName}";
            $new_file120 = "avatars/120/{$newName}";
            file_put_contents($new_file30, base64_decode(str_replace($result[1], '', $input['img'])));
            file_put_contents($new_file60, base64_decode(str_replace($result[1], '', $input['img'])));
            file_put_contents($new_file120, base64_decode(str_replace($result[1], '', $input['img'])));

        }
        $uid = $request->security()->get('uid');
        $UserInfoRepository =new UserInfoRepository(['uid' => $uid]);
        $UserInfoRepository->updateAvatar($newName);
        if (! $UserInfoRepository->passes()) {
            return response()->json($UserInfoRepository->wrap(), $UserInfoRepository->status);
        }
        return response('');
    }

    /**
     * 手机绑定
     * @return json
     */
    public function doBindingMobilre(Request $request){
        $uid = $request->security()->get('uid');
        $input = Input::all();
        $input['uid'] = $uid;
        $MobilebindingRepository =new MobileBindingRepository($input);
        $MobilebindingRepository->contract();
        if (! $MobilebindingRepository->passes()) {
            return response()->json($MobilebindingRepository->wrap(), $MobilebindingRepository->status);
        }
        return response('');
    }
}
