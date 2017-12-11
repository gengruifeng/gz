<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Repositories\PersonalRespository;
use App\Repositories\NoticeRespository;
use App\Repositories\SearchUsersRepository;
use App\Repositories\SearchTagsRepository;
use App\Repositories\QuestionsNolineRepository;
use Illuminate\Support\Facades\Input;
use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProfileController extends Controller
{
    const PAGESIZE = 10;
    /**
     * 个人中心页面展示
     *
     * @param void
     *
     * @return void
     */
    public function personalInfo(Request $request)
    {
        $uid = $request->security()->get('uid');
        if (null === $uid) {
            return redirect(sprintf('/login'));
        }
        $input = Input::all();
        $input['uid']=$uid;
        $repo = new PersonalRespository($input);
        $res = $repo->contract();

        return view('users.personal_center',[
            'personalCenterInfo'=>$res,
            'uid'=>$uid,
        ]);
    }

    /**
     * 他人个人中心页面展示
     *
     * @param void
     *
     * @return void
     */
    public function otherInfo(Request $request, $id)
    {
        $input = Input::all();
        $input['uid']=$id;
        $input['loginId'] = $request->security()->get('uid');
        $user = User::select('id','display_name')->where('id',$id)->first();
        if($input['loginId'] == $id){
            return redirect(sprintf('/profile'));
        }
        if (null === $user) {
            return view('errors.404');
        }
        $repo = new PersonalRespository($input);
        $res = $repo->contract();
        return view('users.personal_center',[
            'personalCenterInfo'=>$res,
        ]);
    }

    /**
     * 粉丝页面展示
     *
     * @param void
     *
     * @return void
     */
    public function follower(Request $request)
    {
        $input = $request->all();
        $loginId = $request->security()->get('uid');
        $input['loginId'] = $loginId;
        $questionnoline = new QuestionsNolineRepository();
        $questionnoline->contract();
        $repo = new PersonalRespository($input);
        $fansInfo = $repo->selectUserFans($loginId);
        $user = $repo->getUserInfoById($loginId);
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
    public function following(Request $request)
    {
        $loginId = $request->security()->get('uid');
        $input['loginId'] = $loginId;
        $questionnoline = new QuestionsNolineRepository();
        $questionnoline->contract();
        $repo = new PersonalRespository($input);
        $res = $repo->selectUserFollowing($loginId);
        $user = $repo->getUserInfoById($loginId);
        return view('personal.following',[
            'followingInfo'=>$res,
            'userInfo'=>$user,
            'loginId'=>$loginId
        ]);
    }


    /*
     * 通知-问答信息页面展示
     */
    public function askMsg()
    {
        return view('notice.ask');
    }

    /*
     * 通知-问答信息页面分页数据
     */
    public function askMsgPage(Request $request)
    {
        $userid = $request->security()->get('uid');
        $input = $request->all();
        $input['pageSize'] = self::PAGESIZE;
        $noticeRespository = new NoticeRespository($input);
        $notifications = $noticeRespository->askMsg($userid);
        if (! $noticeRespository->passes()) {
            return response()->json($noticeRespository->wrap(), $noticeRespository->status);
        }
        return view('notice._ask')->with('notifications',$notifications);
    }

    /*
     * 通知-文章信息页面展示
     */
    public function articleMsg()
    {
        return view('notice.article');
    }

    /*
     * 通知-文章信息页面分页数据
     */
    public function articleMsgPage(Request $request)
    {
        $userid = $request->security()->get('uid');
        $input = $request->all();
        $input['pageSize'] = self::PAGESIZE;
        $noticeRespository = new NoticeRespository($input);
        $notifications = $noticeRespository->articleMsg($userid);
        if (! $noticeRespository->passes()) {
            return response()->json($noticeRespository->wrap(), $noticeRespository->status);
        }
        return view('notice._article')->with('notifications',$notifications);
    }

    /*
     * 通知-私信页面展示
     */
    public function privateMsg(Request $request)
    {
        $userid = $request->security()->get('uid');
        return view('notice.private',[
            'loginId'=>$userid,
        ]);
    }

    /*
     * 通知-私信页面分页数据
     */
    public function privateMsgPage(Request $request)
    {
        $userid = $request->security()->get('uid');
        $input = $request->all();
        $input['pageSize'] = self::PAGESIZE;
        $repo = new NoticeRespository($input);
        $dialogs = $repo->privateMsg($userid);
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return view('notice._private',[
            'notifications'=>$dialogs,
            'loginId'=>$userid,
        ]);
    }

    /*
     * 通知-私信详情页面展示
     */
    public function privateLetterDetail(Request $request,$dialogId)
    {
        $loginId = $request->security()->get('uid');
        $noticeRespository = new NoticeRespository();
        $result = $noticeRespository->privateLetterDetail($loginId,$dialogId);
        if (! $noticeRespository->passes()) {
            if($noticeRespository->status == 403){
                throw new UnauthorizedHttpException('no my messages');
            }
            if($noticeRespository->status == 404){
                throw new NotFoundHttpException('not found');
            }
        }

        return view('notice.private_detail',[
            'contents'=>$result['contents'],
            'loginId'=>$loginId,
            'user'=>$result['user']
        ]);
    }

    /*
     * 通知-系统通知页面展示
     */
    public function systemMsg(Request $request)
    {
        $uid = $request->security()->get('uid');
        $repo = new NoticeRespository();
        $repo->systemMsg($uid);
        return view('notice.system');
    }

    /*
     * 通知-系统通知分页数据
     */
    public function systemMsgPage(Request $request)
    {
        $userid = $request->security()->get('uid');
        $input = $request->all();
        $input['pageSize'] = self::PAGESIZE;
        $repo = new NoticeRespository($input);
        $notifications = $repo->systemMsgPage($userid);
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return view('notice._system')->with('notifications',$notifications);
    }
    

    /*
     * 查询用户信息
     */
    public function selectUser(Request $request)
    {
        $permit = ['q'];
        $input = $request->only($permit);
        $loginId = $request->security()->get('uid');
        $repo = new SearchUsersRepository($input);
        $repo->contract();
        $results = $repo->wrap();
        if(!empty($results)){
            $userId=[];
            foreach($results as $value ){
                $userId[] = $value['id'];
            }
            $users = User::select( 'id','avatar')->whereIn('id',$userId)->get();
            foreach ($results as $k=>$val){
                if($val['id'] == $loginId){
                    unset($results[$k]);
                }else{
                    foreach($users as $user){
                        if($user->id == $val['id']){
                            $results[$k]['avatar'] = $user->avatar;
                        }
                    }
                }
            }
        }
        return response()->json($results);
}


    /**
     * 个人中心 提问
     * @param Request
     *
     * @return html
     */
    public function question(Request $request)
    {
        $input = $request->all();
        $respository = new PersonalRespository();
        $userinfo = $respository->getUserInfo($input['uid']);
        $page = isset($input['page'])?$input['page']:1;
        $pagesize = self::PAGESIZE;
        $res =$respository->selectUserQuestions($userinfo->id,$page,$pagesize);
        if (! $respository->passes()) {
            return response()->json($respository->wrap(), $respository->status);
        }
        $result['info'] = $res;
        $result['userId'] = $userinfo->id;
        return view('personal._question')->with('info',$result);
    }

    /**
     * 个人中心 回答
     * @param Request
     *
     * @return html
     */
    public function answer(Request $request)
    {
        $input = $request->all();
        $respository = new PersonalRespository();
        $userinfo = $respository->getUserInfo($input['uid']);
        $page = isset($input['page'])?$input['page']:1;
        $pagesize = self::PAGESIZE;
        $res =$respository->selectUserAnswered($userinfo->id,$page,$pagesize);
        if (! $respository->passes()) {
            return response()->json($respository->wrap(), $respository->status);
        }
        $result['info'] = $res;
        $result['userId'] = $userinfo->id;
        return view('personal._answer')->with('info',$result);

    }

    /**
     * 个人中心 文章
     * @param Request
     *
     * @return html
     */
    public function article(Request $request)
    {
        $input = $request->all();
        $uid = $request->security()->get('uid');
        $respository = new PersonalRespository();
        $userinfo = $respository->getUserInfo($input['uid']);
        $page = isset($input['page'])?$input['page']:1;
        $pagesize = self::PAGESIZE;
        $res =$respository->selectUserArticle($uid,$userinfo->id,$userinfo->display_name,$userinfo->occupation,$page,$pagesize);
        if (! $respository->passes()) {
            return response()->json($respository->wrap(), $respository->status);
        }
        $result['info'] = $res;
        $result['userId'] = $userinfo->id;
        return view('personal._article')->with('info',$result);

    }

    /**
     * 个人中心 收藏
     * @param Request
     *
     * @return html
     */
    public function collect(Request $request)
    {
        $input = $request->all();
        $respository = new PersonalRespository();
        $userinfo = $respository->getUserInfo($input['uid']);
        $page = isset($input['page'])?$input['page']:1;
        $pagesize = self::PAGESIZE;
        $res =$respository->selectUserCollect($userinfo->id,$page,$pagesize);
        if (! $respository->passes()) {
            return response()->json($respository->wrap(), $respository->status);
        }
        $result['info'] = $res;
        $result['userId'] = $userinfo->id;
        return view('personal._collect')->with('info',$result);
    }

    /**
     * 个人中心 关注
     * @param Request
     *
     * @return html
     */
    public function follow(Request $request)
    {
        $input = $request->all();
        $respository = new PersonalRespository();
        $userinfo = $respository->getUserInfo($input['uid']);
        $page = isset($input['page'])?$input['page']:1;
        $pagesize = self::PAGESIZE;
        $res =$respository->selectUserFollow($userinfo->id,$page,$pagesize);
        if (! $respository->passes()) {
            return response()->json($respository->wrap(), $respository->status);
        }
        $result['info'] = $res;

        $result['userId'] = $userinfo->id;
        return view('personal._follow')->with('info',$result);
        
    }

}
