<?php

namespace App\Http\ViewComposers;

use App\Entity\Competence;
use App\Repositories\Admin\CompetenceRepository;
use App\Repositories\NoticeRespository;
use Illuminate\Contracts\View\View;

use App\Repositories\UserInfoRepository;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Cookie;
class FavoritesComposer
{
    public function compose(View $view)
    {
        //获取公用头部的用户信息
        $user = new UserInfoRepository();
        $session = Cookie::get('user_session');
        $usersession = $user->UserArr($session);
        $userarr = empty($usersession) ? ['avatar'=>'', 'display_name'=>'', 'uid'=>''] :$usersession;
        $view->with('userarr', $userarr);
        if(!empty($userarr['group_id']) && $userarr['group_id'] != 0){
            $competenceRepository = new CompetenceRepository(['group_id' => $userarr['group_id']]);
            $competenceRepository->dofunction = 'getUserCon';
            $competenceRepository->contract();
            $view->with('conTree',$competenceRepository->returnData);

            $competenceRepository->dofunction = 'getCurrent';
            $competenceRepository->contract();
            $view->with('currentData',$competenceRepository->returnData);
        }
        if(!empty($usersession)){
            //获取未读通知条数
            $noticeRespository = new NoticeRespository();
            $noticeNum = $noticeRespository->getNoReadNoticeNum($userarr['uid'],$userarr['created_at']);
            $view->with('noticeNum',$noticeNum);
        }

    }
}
