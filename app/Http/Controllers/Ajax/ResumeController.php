<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Entity\ArticleComment;

use App\Repositories\ResumeRepository;
use Illuminate\Support\Facades\Session;
use App\Utils\Upload;

use DB;

class ResumeController extends Controller
{
    /**
     * 个人信息
     *
     * @param void
     *
     * @return Response
     */
    public function persons(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid'=>$uid,
            'name'=> Input::get('display_name'),
            'mobile'=> Input::get('mobUsername'),
            'email' => Input::get('email'),
            'gender' => Input::get('sex'),
            'status' => empty(Input::get('status'))?2:Input::get('status'),
            'birthday' => empty(Input::get('birthday'))?"":Input::get('birthday'),
            'province' => Input::get('province'),
            'city' => Input::get('city'),
        ];
        $repo = new ResumeRepository($input);
        $repo->dofunction = 'persons';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json();
    }
    /**
     * 求职意向
     *
     * @param void
     *
     * @return Response
     */
    public function advices(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id')
            ->where('uid','=',$uid)
            ->first();
        $input = [
            'cvid'=> $persons->id,
            'word_period'=> Input::get('word_period'),
            'city'=> Input::get('city'),
            'position' => Input::get('position'),
            'salary' => Input::get('salary'),
            'province' => Input::get('province'),
            'employment_type' => Input::get('employment_type'),
            'job_type' => Input::get('job_type'),
        ];
        $repo = new ResumeRepository($input);
        $repo->dofunction = 'advices';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->biz);
    }
    /**
     * 教育背景
     *
     * @param void
     *
     * @return Response
     */
    public function educations(Request $request)
    {
        $educations = [];
        $educations[0] = [
            'enrolled' =>Input::get('time_start'),
            'graduated' => Input::get('time_end'),
            'school' => Input::get('school_name'),
            'department' => Input::get('expert_name'),
            'education' => Input::get('level'),
            'success' => Input::get('success'),

        ];
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id')
            ->where('uid','=',$uid)
            ->first();
        $input = [
            'cvid'=> $persons->id,
            'educations'=> $educations,
            'educationid' =>  Input::get('educationid'),
        ];
        $repo = new ResumeRepository($input);
        $repo->dofunction = 'educations';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json('');
    }
    /**
     * 个人经历
     *
     * @param void
     *
     * @return Response
     */
    public function experiences(Request $request)
    {
        $experiences= [];
        $experiences[0] = [
            'company' =>Input::get('company'),
            'position' => Input::get('position'),
            'from' => Input::get('time_start'),
            'to' => Input::get('time_end'),
            'jobdescription' => Input::get('jobdescription'),

        ];
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id')
            ->where('uid','=',$uid)
            ->first();
        $input = [
            'cvid'=> $persons->id,
            'experiences'=> $experiences,
            'experienceid' =>  Input::get('experienceid'),
        ];
        $repo = new ResumeRepository($input);
        $repo->dofunction = 'experiences';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->biz);
    }
    /**
     * 技能证书
     *
     * @param void
     *
     * @return Response
     */
    public function diplomas(Request $request)
    {
        $experiences= [];
        $diplomas[0] = [
            'certificate' =>Input::get('certificate-add'),
            'supplementary' =>Input::get('certificate-cope'),
            'achivement' => Input::get('achivement'),
        ];
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id')
            ->where('uid','=',$uid)
            ->first();
        $input = [
            'cvid'=> $persons->id,
            'diplomas'=>$diplomas,
            'diplomaid'=>Input::get('diplomaid'),
        ];
        $repo = new ResumeRepository($input);
        $repo->dofunction = 'diplomas';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->biz);
    }
    /**
     * 奖项荣誉
     *
     * @param void
     *
     * @return Response
     */
    public function honors(Request $request)
    {
        $honors= [];
        $honors[0] = [
            'received_at' =>Input::get('received_at'),
            'award' => Input::get('award'),
        ];
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id')
            ->where('uid','=',$uid)
            ->first();
        $input = [
            'cvid' => $persons->id,
            'honors' => $honors,
            'honorid' =>  Input::get('honorid'),
        ];
        $repo = new ResumeRepository($input);
        $repo->dofunction = 'honors';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->biz);
    }
    /**
     * 个人作品
     *
     * @param void
     *
     * @return Response
     */
    public function projects(Request $request)
    {
        $projects= [];
        $projects[0] = [
            'worked_at' =>Input::get('worked_at'),
            'title' => Input::get('title'),
            'subtitle' =>empty(Input::get('subtitle'))?"":Input::get('subtitle'),
            'description' => Input::get('description'),
        ];
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id')
            ->where('uid','=',$uid)
            ->first();
        $input = [
            'cvid' => $persons->id,
            'projects' => $projects,
            'projectid'=> Input::get('projectid'),
        ];
        $repo = new ResumeRepository($input);
        $repo->dofunction = 'projects';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->biz);
    }
    /**
     * 兴趣爱好
     *
     * @param void
     *
     * @return Response
     */
    public function interests(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id')
            ->where('uid','=',$uid)
            ->first();
        $input = [
            'cvid'=> $persons->id,
            'interests'=> Input::get('interests'),
        ];
        $repo = new ResumeRepository($input);
        $repo->dofunction = 'interests';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->biz);
    }
    /**
     * 简历照片
     *
     * @param void
     *
     * @return Response
     */
    public function resumeupload(Request $request)
    {

        $permit = ['img'];
        $input = $request->only($permit);
        $newName = '';
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $input['img'], $result)){
            $type = $result[2];
            $newName = date('YmdHis').rand(1000,9999).'.'.$type;
            $path = 'resume';

            if(!is_dir($path)){
                mkdir($path,0777,true);
            }
            $new_file30 = "{$path}/{$newName}";
            file_put_contents($new_file30, base64_decode(str_replace($result[1], '', $input['img'])));

        }

        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid'=>$uid,
            'resumeavatar'=> $newName,
        ];
        $repo = new ResumeRepository($input);
        $repo->dofunction = 'resumeupload';
        $repo->contract();
        return response('');
    }
    /**
     * 教育背景
     *
     * @param void
     *
     * @return Response
     */
    public function myeducations(Request $request)
    {

        $educations = [];
        for($j = 0;$j<Input::get('i');$j++){
            if(!empty(Input::get('school_name-'.$j))){
                $educations[$j]['school'] = Input::get('school_name-'.$j);
            }
            if(!empty(Input::get('expert_name-'.$j))){
                $educations[$j]['department'] = Input::get('expert_name-'.$j);            }

            if(!empty(Input::get('time_start-'.$j))){
                $educations[$j]['enrolled'] = Input::get('time_start-'.$j);
            }
            if(!empty(Input::get('time_end-'.$j))){
                $educations[$j]['graduated'] = Input::get('time_end-'.$j);
            }
            if(!empty(Input::get('level-'.$j))){
                $educations[$j]['education'] = Input::get('level-'.$j);
            }

        }
        $request->session()->put('educations', $educations);
        return response()->json('');
    }
    /**
     * 教育背景
     *
     * @param void
     *
     * @return Response
     */
    public function backexperiences(Request $request)
    {
        $experiences = [];
        for($j = 0;$j<Input::get('i');$j++){
            if(!empty(Input::get('company_name-'.$j))){
                $experiences[$j]['company'] = Input::get('company_name-'.$j);
            }
            if(!empty(Input::get('job_name-'.$j))){
                $experiences[$j]['position'] = Input::get('job_name-'.$j);
            }
            if(!empty(Input::get('time_start-'.$j))){
                $experiences[$j]['from'] = Input::get('time_start-'.$j);
            }
            if(!empty(Input::get('time_end-'.$j))){
                $experiences[$j]['to'] = Input::get('time_end-'.$j);
            }
        }
        $request->session()->put('experiences', $experiences);
        return response()->json('');
    }
    /**
     * 教育背景
     *
     * @param void
     *
     * @return Response
     */
    public function myexperiences(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id')
            ->where('uid','=',$uid)
            ->first();
        $experiences = [];
        for($j = 0;$j<Input::get('i');$j++){
            if(!empty(Input::get('company_name-'.$j))){
                $experiences[$j]['company'] = Input::get('company_name-'.$j);
            }
            if(!empty(Input::get('job_name-'.$j))){
                $experiences[$j]['position'] = Input::get('job_name-'.$j);
            }
            if(!empty(Input::get('time_start-'.$j))){
                $experiences[$j]['from'] = Input::get('time_start-'.$j);
            }
            if(!empty(Input::get('time_end-'.$j))){
                $experiences[$j]['to'] = Input::get('time_end-'.$j);
            }
        }
        $educations = $request->session()->get('educations');
        if(empty($educations)){
            return redirect()->route('myeducations');
        }
        $request->session()->forget('educations');
        $request->session()->forget('experiences');
        $input = [
            'uid' => $uid,
            'cvid'=> $persons->id,
            'educations'=> $educations,
            'experiences'=> $experiences,
        ];
        $repo = new ResumeRepository($input);
        $repo->dofunction = 'resumeinfo';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json();
    }
    /**
     * 删除教育背景
     *
     * @param void
     *
     * @return Response
     */
    public function educationdel(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id')
            ->where('uid','=',$uid)
            ->first();
        $input = [
            'cvid'=> $persons->id,
            'id'=> Input::get('id'),
        ];
        $repo = new ResumeRepository($input);
        $repo->dofunction = 'educationdel';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json();
    }
    /**
     * 删除个人经历
     *
     * @param void
     *
     * @return Response
     */
    public function experiencedel(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id')
            ->where('uid','=',$uid)
            ->first();
        $input = [
            'cvid'=> $persons->id,
            'id'=> Input::get('id'),
        ];
        $repo = new ResumeRepository($input);
        $repo->dofunction = 'experiencedel';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json();
    }
    /**
     * 删除技能证书
     *
     * @param void
     *
     * @return Response
     */
    public function diplomadel(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id')
            ->where('uid','=',$uid)
            ->first();
        $input = [
            'cvid'=> $persons->id,
            'id'=> Input::get('id'),
        ];
        $repo = new ResumeRepository($input);
        $repo->dofunction = 'diplomadel';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json();
    }
    /**
     * 删除奖项荣誉
     *
     * @param void
     *
     * @return Response
     */
    public function honordel(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id')
            ->where('uid','=',$uid)
            ->first();
        $input = [
            'cvid'=> $persons->id,
            'id'=> Input::get('id'),
        ];
        $repo = new ResumeRepository($input);
        $repo->dofunction = 'honordel';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json();
    }
    /**
     * 删除个人作品
     *
     * @param void
     *
     * @return Response
     */
    public function projectdel(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id')
            ->where('uid','=',$uid)
            ->first();
        $input = [
            'cvid'=> $persons->id,
            'id'=> Input::get('id'),
        ];
        $repo = new ResumeRepository($input);
        $repo->dofunction = 'projectdel';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json();
    }
    /**
     * 校检跳转简历页面
     *
     * @param void
     *
     * @return Response
     */
    public function checkselect(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $input = [
            'uid'=> $uid,
        ];
        $repo = new ResumeRepository($input);
        $repo->dofunction = 'checkselect';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json();
    }
    /**
     * 查询城市
     *
     * @param void
     *
     * @return Response
     */
    public function city(Request $request)
    {
        $input = [
            'pid'=> Input::get('pid'),
        ];
        $repo = new ResumeRepository($input);
        $repo->dofunction = 'city';
        $repo->contract();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return response()->json($repo->biz);
    }
}
