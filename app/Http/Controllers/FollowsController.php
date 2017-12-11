<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;
use App\Repositories\PersonalRespository;
use App\Repositories\QuestionsNolineRepository;
use App\Entity\User;

class FollowsController extends Controller
{
    /**
     * 粉丝页面展示
     *
     * @param void
     *
     * @return void
     */
    public function follower(Request $request,$userid)
    {
        $input = Input::all();
        $loginId = $request->security()->get('uid');
        $user = User::select('id','display_name')->where('id',$userid)->first();
        if($userid == $loginId){
            return redirect(sprintf('/follower'));
        }
        if (null === $user) {
            return view('errors.404');
        }
        $questionnoline = new QuestionsNolineRepository();
        $questionnoline->contract();
        $input['loginId'] = $loginId;
        $repo = new PersonalRespository($input);
        $fansInfo = $repo->selectUserFans($userid);
        $user = $repo->getUserInfoById($userid);
        return view('personal.fans',[
            'fansInfo'=>$fansInfo,
            'userInfo'=>$user,
            'loginId'=>$loginId
        ]);
    }

    /**
     * 关注页面展示
     *
     * @param void
     *
     * @return void
     */
    public function following(Request $request,$userid)
    {
        $input = Input::all();
        $loginId = $request->security()->get('uid');
        $user = User::select('id','display_name')->where('id',$userid)->first();
        if($userid == $loginId){
            return redirect(sprintf('/following'));
        }
        if (null === $user) {
            return view('errors.404');
        }
        $questionnoline = new QuestionsNolineRepository();
        $questionnoline->contract();
        $input['loginId'] = $loginId;
        $repo = new PersonalRespository($input);
        $res = $repo->selectUserFollowing($userid);
        $user = $repo->getUserInfoById($userid);
        return view('personal.following',[
            'followingInfo'=>$res,
            'userInfo'=>$user,
            'loginId'=>$loginId,
            'flag' =>1
        ]);
    }
}
