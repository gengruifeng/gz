<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use Illuminate\Support\Facades\Input;

use App\Entity\ProvinceCity;

use App\Repositories\ResumeRepository;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

use DB;

class ResumeController extends Controller
{
    /**
     * 首次进入简历
     *
     * @param void
     *
     * @return Response
     */
    public function resume(Request $request)
    {
        $uid = $request->security()->get('uid');
        $persons= DB::table('cv_persons')
            ->select('id','name','mobile','email','gender','birthday','resumeavatar','status','province','city','updated_at')
            ->where('uid','=',$uid)
            ->first();
        if(empty($persons)){
            $province = ProvinceCity::where('parentid' , 0)->get();
            $city = ProvinceCity::where('level' , 2)->get();
            return view('resume.resume_persons',
                [
                    'persons' => $persons,
                    'province' => $province,
                    'city' => $city
                ]
            );
        }else{
            if($persons->status == 2){
                //个人经历
                $experiences = DB::table('cv_experiences')
                    ->select('from','to','company','position','jobdescription','updated_at')
                    ->where('cvid','=',$persons->id)
                    ->get();
                //教育背景
                $educations = DB::table('cv_educations')
                    ->select('enrolled','graduated','school','department','education','success','updated_at')
                    ->where('cvid','=',$persons->id)
                    ->get();
                //兴趣爱好
                $interests = DB::table('cv_interests')
                    ->select('interests','updated_at')
                    ->where('cvid','=',$persons->id)
                    ->first();
                //荣誉奖项
                $honors = DB::table('cv_honors')
                    ->select('received_at','award','updated_at')
                    ->where('cvid','=',$persons->id)
                    ->get();
                //技能证书
                $diplomas = DB::table('cv_diplomas')
                    ->select('certificate','supplementary','achivement','updated_at')
                    ->where('cvid','=',$persons->id)
                    ->get();
                //$diplomas = DB::table('cv_certificates')
                //    ->select('certificate','achivement','updated_at')
                //    ->where('cvid','=',$persons->id)
                //    ->get();
                //个人作品
                $projects = DB::table('cv_projects')
                    ->select('worked_at','title','subtitle','description','updated_at')
                    ->where('cvid','=',$persons->id)
                    ->get();
                //求职意向
                $advices = DB::table('cv_advices')
                    ->select('word_period','city','position','salary','employment_type','job_type','updated_at')
                    ->where('cvid','=',$persons->id)
                    ->first();
                return view('resume.resume',
                    [
                        'persons' =>empty( $persons)?[]: $persons,
                        'experiences' => empty($experiences)?[]:$experiences,
                        'educations' => empty($educations)?[]:$educations,
                        'interests' => empty($interests)?[]:$interests,
                        'honors' => empty($honors)?[]:$honors,
                        'projects' => empty($projects)?[]:$projects,
                        'advices' => empty($advices)?[]:$advices,
                        'diplomas' => empty($diplomas)?[]:$diplomas,
                    ]
                );
            }
            $province = ProvinceCity::where('parentid' , 0)->get();
            $city = ProvinceCity::where('level' , 2)->get();
            return view('resume.resume_persons',
                [
                    'persons' => $persons,
                    'province' => $province,
                    'city' => $city
                ]
            );
        }
    }
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
        $persons = DB::table('cv_persons')
            ->select('id','name','mobile','status','email','gender','birthday','resumeavatar','province','city','updated_at')
            ->where('uid','=',$uid)
            ->first();
        if(empty($persons)){
            return redirect()->route('myresume');
        }
        if($persons->status==1){
            return redirect()->route('myresume');
        }
        $province = ProvinceCity::where('parentid' , 0)->get();
        $city = ProvinceCity::where('level' , 2)->get();
        $resumestatus= self::resumestatus($persons);
        return view('resume.revise_persons',
            [
                'resumestatus'=>$resumestatus,
                'persons'=>$persons,
                'province' => $province,
                'city' => $city
            ]
        );


    }
    /**
     * 添加教育背景
     *
     * @param void
     *
     * @return Response
     */
    public function myeducations(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id','resumeavatar','status')
            ->where('uid','=',$uid)
            ->first();
        if(empty($persons)){
            return redirect()->route('myresume');
        }
        if($persons->status==2){
            return redirect()->route('myresume');
        }
        $educations = $request->session()->get('educations');
        $dbcity = DB::table('cv_school')->select('cv_school.id','province_city.areaname','province_city.id')->join('province_city', 'cv_school.cityid', '=', 'province_city.id')->groupby('cv_school.cityid')->get();
        $dbcityall = DB::table('cv_school')->get();
        $school = [];
        foreach ($dbcity as $k=>$v){
            foreach ($dbcityall as $kk=>$vv){
                if($v->id == $vv->cityid){
                    $school[$vv->cityid]['name'] = $v->areaname;
                    $school[$vv->cityid]['sub'][] = $vv->name;
                }
            }
        }
        $i = count($educations);
        return view('resume.resume_educations',
            [
                'i' => $i,
                'educations' => $educations,
                'persons' => $persons,
                'school' => $school,
            ]
        );
    }
    /**
     * 个人经历
     *
     * @param void
     *
     * @return Response
     */
    public function myexperiences(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id','resumeavatar','status')
            ->where('uid','=',$uid)
            ->first();
        if(empty($persons)){
            return redirect()->route('myresume');
        }
        if($persons->status==2){
            return redirect()->route('myresume');
        }
        $educations = $request->session()->get('educations');
        if(empty($educations)){
            return redirect()->route('myeducations');
        }
        $experiences = [] ;
        $experiences = $request->session()->get('experiences');
        $dbpositionsroot = DB::table('cv_positions')
            ->select('id','pid','name')
            ->where('pid','=',0)
            ->orderby('order','desc')
            ->orderby('id','desc')
            ->get();
        $dbpositions = DB::table('cv_positions')
            ->select('id','pid','name')
            ->where('pid','<>',0)
            ->orderby('order','desc')
            ->orderby('id','desc')
            ->get();
        $positions = [];
        foreach ($dbpositionsroot as $k=>$v){
            foreach ($dbpositions as $kk=>$vv){
                if($v->id == $vv->pid){
                    $positions[$v->id][$vv->id]['name'] = $vv->name;
                }
            }

        }

        foreach ($positions as $k=>$v){
            foreach ($v as $kk=>$vv){
                foreach ($dbpositions as $kkk=>$vvv){
                    if($kk == $vvv->pid){
                        $positions[$k][$kk]['sub'][$vvv->id] = $vvv->name;
                    }
                }
            }
        }
        $i = count($educations);
        return view('resume.resume_experiences',
            [
                'i' => $i,
                'experiences' => $experiences,
                'positions' => $positions,
                'positionsroot' => $dbpositionsroot,
                'persons' => $persons,
            ]
        );
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
            ->select('id','resumeavatar','status')
            ->where('uid','=',$uid)
            ->first();
        if(empty($persons)){
            return redirect()->route('myresume');
        }
        if($persons->status==1){
            return redirect()->route('myresume');
        }
        $advices = DB::table('cv_advices')
            ->select('word_period','province','city','position','salary','employment_type','job_type','updated_at')
            ->where('cvid','=',$persons->id)
            ->first();
        if(!empty($advices)){
            $position = explode(';',$advices->position);
            $position = array_filter($position);
            $positionIndex = [];
            foreach ($position as $questionTag) {
                $positionIndex[$questionTag] = $questionTag;
            }
        }

        $city = ProvinceCity::where('level' , 2)->get();
        $dbpositionsroot = DB::table('cv_positions')
            ->select('id','pid','name')
            ->where('pid','=',0)
            ->orderby('order','desc')
            ->orderby('id','desc')
            ->get();
        $dbpositions = DB::table('cv_positions')
            ->select('id','pid','name')
            ->where('pid','<>',0)
            ->orderby('order','desc')
            ->orderby('id','desc')
            ->get();
        $province = ProvinceCity::where('parentid' , 0)->get();
        $positions = [];
        foreach ($dbpositionsroot as $k=>$v){
            foreach ($dbpositions as $kk=>$vv){
                if($v->id == $vv->pid){
                    $positions[$v->id][$vv->id]['name'] = $vv->name;
                }
            }
        }
        foreach ($positions as $k=>$v){
            foreach ($v as $kk=>$vv){
                foreach ($dbpositions as $kkk=>$vvv){
                    if($kk == $vvv->pid){
                        $positions[$k][$kk]['sub'][$vvv->id] = $vvv->name;
                    }
                    if (isset($positionIndex[$vvv->name])) {
                        $positionIndex[$vvv->name] = $vvv;
                    }
                }
            }
        }
        $resumestatus= self::resumestatus($persons);
        return view('resume.revise_advices',
            [
                'positionarr'=>empty($positionIndex)?[]:$positionIndex,
                'resumestatus' => $resumestatus,
                'advices' => $advices,
                'positions' => $positions,
                'city' => $city,
                'province' => $province,
                'positionsroot' => $dbpositionsroot,
            ]
        );

    }
    /**
     * 教育背景
     *
     * @param void
     *
     * @return Response
     */
    public function  educations(Request $request)
    {
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id','status')
            ->where('uid','=',$uid)
            ->first();
        if(empty($persons)){
            return redirect()->route('myresume');
        }
        if($persons->status==1){
            return redirect()->route('myresume');
        }
        $educations = DB::table('cv_educations')
            ->select('id','cvid','enrolled','graduated','school','department','success','education','updated_at')
            ->where('cvid','=',$persons->id)
            ->get();
        $majors = DB::table('cv_majors')
            ->select('id','name','updated_at')
            ->get();
        $dbcity = DB::table('cv_school')->select('cv_school.id','province_city.areaname','province_city.id')->join('province_city', 'cv_school.cityid', '=', 'province_city.id')->groupby('cv_school.cityid')->get();
        $school = [];
        $dbcityall = DB::table('cv_school')->get();
        foreach ($dbcity as $k=>$v){
            foreach ($dbcityall as $kk=>$vv){
                if($v->id == $vv->cityid){
                    $school[$vv->cityid]['name'] = $v->areaname;
                    $school[$vv->cityid]['sub'][] = $vv->name;
                }
            }
        }
        $resumestatus= self::resumestatus($persons);
        return view('resume.revise_educations',
            [
                'resumestatus' => $resumestatus,
                'school'=>$school,
                'educations' => $educations,
            ]
        );
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
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id','status')
            ->where('uid','=',$uid)
            ->first();
        if(empty($persons)){
            return redirect()->route('myresume');
        }
        if($persons->status==1){
            return redirect()->route('myresume');
        }
        $experiences = DB::table('cv_experiences')
            ->select('id','cvid','from','to','company','position','jobdescription','updated_at')
            ->where('cvid','=',$persons->id)
            ->get();
        $resumestatus= self::resumestatus($persons);
        return view('resume.revise_experiences',
            [
                'resumestatus'=>$resumestatus,
                'experiences' => $experiences,
            ]
        );
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
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id','status')
            ->where('uid','=',$uid)
            ->first();
        if(empty($persons)){
            return redirect()->route('myresume');
        }
        if($persons->status==1){
            return redirect()->route('myresume');
        }
        $diplomas = DB::table('cv_diplomas')
            ->select('id','cvid','certificate','achivement','supplementary','updated_at')
            ->where('cvid','=',$persons->id)
            ->get();
        $dbcity = DB::table('cv_certificates')
            ->select('id','name','pid')
            ->where('pid','=',0)
            ->get();
        $dbcitytow = DB::table('cv_certificates')
            ->select('id','name','pid')
            ->where('pid','<>',0)
            ->get();
        $school = [];
        foreach ($dbcity as $k=>$v){
            foreach ($dbcitytow as $kk=>$vv){
                if($v->id == $vv->pid){
                    $school[$vv->pid]['name'] = $v->name;
                    $school[$vv->pid]['sub'][] = $vv->name;
                }
            }
        }
        $resumestatus= self::resumestatus($persons);
        return view('resume.revise_diplomas',
            [
                'resumestatus'=>$resumestatus,
                'school' => $school,
                'diplomas' => $diplomas,
            ]
        );
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
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id','status')
            ->where('uid','=',$uid)
            ->first();
        if(empty($persons)){
            return redirect()->route('myresume');
        }
        if($persons->status==1){
            return redirect()->route('myresume');
        }
        $honors = DB::table('cv_honors')
            ->select('id','cvid','received_at','award','updated_at')
            ->where('cvid','=',$persons->id)
            ->get();
        $resumestatus= self::resumestatus($persons);
        return view('resume.revise_honors',
            [
                'resumestatus'=>$resumestatus,
                'honors' => $honors,
            ]
        );
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
        $uid = empty($request->security()->get('uid'))?'':$request->security()->get('uid');
        $persons = DB::table('cv_persons')
            ->select('id','status')
            ->where('uid','=',$uid)
            ->first();
        if(empty($persons)){
            return redirect()->route('myresume');
        }
        if($persons->status==1){
            return redirect()->route('myresume');
        }
        $projects = DB::table('cv_projects')
            ->select('id','cvid','worked_at','title','subtitle','description','updated_at')
            ->where('cvid','=',$persons->id)
            ->get();
        $resumestatus= self::resumestatus($persons);
        return view('resume.revise_projects',
            [
                'resumestatus' => $resumestatus,
                'projects' => $projects,
            ]
        );
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
            ->select('id','status')
            ->where('uid','=',$uid)
            ->first();
        if(empty($persons)){
            return redirect()->route('myresume');
        }
        if($persons->status==1){
            return redirect()->route('myresume');
        }
        $interests = DB::table('cv_interests')
            ->select('id','cvid','interests','updated_at')
            ->where('cvid','=',$persons->id)
            ->first();
        $resumestatus= self::resumestatus($persons);
        return view('resume.revise_interests',
            [
                'resumestatus' => $resumestatus,
                'interests' => $interests,
            ]
        );

    }
    /**
     * 状态
     *
     * @param void
     *
     * @return Response
     */
    static function resumestatus($persons)
    {

        //个人经历
        $status['experiences'] = empty(DB::table('cv_experiences')
            ->select('id')
            ->where('cvid','=',$persons->id)
            ->first())?1:2;
        //教育背景
        $status['educations'] = empty(DB::table('cv_educations')
            ->select('id')
            ->where('cvid','=',$persons->id)
            ->first())?1:2;
        //兴趣爱好
        $status['interests'] = empty(DB::table('cv_interests')
            ->select('id')
            ->where('cvid','=',$persons->id)
            ->first())?1:2;
        //荣誉奖项
        $status['honors'] = empty(DB::table('cv_honors')
            ->select('id')
            ->where('cvid','=',$persons->id)
            ->first())?1:2;
        //技能证书
        $status['diplomas'] = empty(DB::table('cv_diplomas')
            ->select('id')
            ->where('cvid','=',$persons->id)
            ->first())?1:2;
        //个人作品
        $status['projects'] = empty(DB::table('cv_projects')
            ->select('id')
            ->where('cvid','=',$persons->id)
            ->first())?1:2;
        //求职意向
        $status['advices'] = empty(DB::table('cv_advices')
            ->select('id')
            ->where('cvid','=',$persons->id)
            ->first())?1:2;

        return $status;
    }
}