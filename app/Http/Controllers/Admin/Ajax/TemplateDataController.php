<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Repositories\Admin\CertificateRepository;
use App\Repositories\Admin\CityRepository;
use App\Repositories\Admin\MajorRepository;
use App\Repositories\Admin\ProfessionsRepository;
use App\Repositories\Admin\PositionRepository;
use App\Repositories\Admin\SchoolRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class TemplateDataController extends Controller
{
    //

    /**
     * 获取去求职意向列表
     * @return json
     */
    public function professionsList(){
        $ProfessionsRepository= new ProfessionsRepository(Input::all());
        $ProfessionsRepository ->dofunction = 'getList';
        $ProfessionsRepository->contract();

        if (! $ProfessionsRepository->passes()) {
            return response()->json($ProfessionsRepository->wrap(), $ProfessionsRepository->status);
        }
        return response($ProfessionsRepository->data);
    }

    /**
     * 添加求职意向
     * @return Response
     */
    public function professionsadd(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;

        $ProfessionsRepository= new ProfessionsRepository($input);
        $ProfessionsRepository ->dofunction = 'add';
        $ProfessionsRepository->contract();

        if (! $ProfessionsRepository->passes()) {
            return response()->json($ProfessionsRepository->wrap(), $ProfessionsRepository->status);
        }
        return response('');
    }

    /**
     * 编辑求职意向
     * @return Response
     */
    public function professionsedit(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;

        $ProfessionsRepository= new ProfessionsRepository($input);
        $ProfessionsRepository ->dofunction = 'edit';
        $ProfessionsRepository->contract();

        if (! $ProfessionsRepository->passes()) {
            return response()->json($ProfessionsRepository->wrap(), $ProfessionsRepository->status);
        }
        return response('');
    }

    /**
     * 删除求职意向
     * @return Response
     */
    public function professionsDel(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;

        $ProfessionsRepository= new ProfessionsRepository($input);
        $ProfessionsRepository ->dofunction = 'del';
        $ProfessionsRepository->contract();

        if (! $ProfessionsRepository->passes()) {
            return response()->json($ProfessionsRepository->wrap(), $ProfessionsRepository->status);
        }
        return response('');
    }


    /**
     * 获取专业列表
     * @return json
     */
    public function majorList(){
        $MajorRepository= new MajorRepository(Input::all());
        $MajorRepository ->dofunction = 'getList';
        $MajorRepository->contract();

        if (! $MajorRepository->passes()) {
            return response()->json($MajorRepository->wrap(), $MajorRepository->status);
        }
        return response($MajorRepository->data);
    }

    /**
     * 添加专业
     * @return Response
     */
    public function majoradd(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;

        $MajorRepository= new MajorRepository($input);
        $MajorRepository ->dofunction = 'add';
        $MajorRepository->contract();

        if (! $MajorRepository->passes()) {
            return response()->json($MajorRepository->wrap(), $MajorRepository->status);
        }
        return response('');
    }

    /**
     * 编辑专业
     * @return Response
     */
    public function majoredit(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;

        $MajorRepository= new MajorRepository($input);
        $MajorRepository ->dofunction = 'edit';
        $MajorRepository->contract();

        if (! $MajorRepository->passes()) {
            return response()->json($MajorRepository->wrap(), $MajorRepository->status);
        }
        return response('');
    }

    /**
     * 删除专业
     * @return Response
     */
    public function majorDel(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;

        $MajorRepository= new MajorRepository($input);
        $MajorRepository ->dofunction = 'del';
        $MajorRepository->contract();

        if (! $MajorRepository->passes()) {
            return response()->json($MajorRepository->wrap(), $MajorRepository->status);
        }
        return response('');
    }

    /**
     * 获取去职位列表
     * @return json
     */
    public function positionList(){
        $PositionRepository= new PositionRepository(Input::all());
        $PositionRepository ->dofunction = 'getList';
        $PositionRepository->contract();

        if (! $PositionRepository->passes()) {
            return response()->json($PositionRepository->wrap(), $PositionRepository->status);
        }
        return response($PositionRepository->data);
    }

    /**
     * 添加职位
     * @return Response
     */
    public function positionAdd(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;

        $PositionRepository= new PositionRepository($input);
        $PositionRepository ->dofunction = 'add';
        $PositionRepository->contract();

        if (! $PositionRepository->passes()) {
            return response()->json($PositionRepository->wrap(), $PositionRepository->status);
        }
        return response('');
    }

    /**
     * 编辑职位
     * @return Response
     */
    public function positionEdit(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;

        $PositionRepository= new PositionRepository($input);
        $PositionRepository ->dofunction = 'edit';
        $PositionRepository->contract();

        if (! $PositionRepository->passes()) {
            return response()->json($PositionRepository->wrap(), $PositionRepository->status);
        }
        return response('');
    }

    /**
     * 删除职位
     * @return Response
     */
    public function positionDel(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;

        $PositionRepository= new PositionRepository($input);
        $PositionRepository ->dofunction = 'del';
        $PositionRepository->contract();

        if (! $PositionRepository->passes()) {
            return response()->json($PositionRepository->wrap(), $PositionRepository->status);
        }
        return response('');
    }

    /**
     * 获取去证书列表
     * @return json
     */
    public function certificateList(){
        $CertificateRepository= new CertificateRepository(Input::all());
        $CertificateRepository ->dofunction = 'getList';
        $CertificateRepository->contract();

        if (! $CertificateRepository->passes()) {
            return response()->json($CertificateRepository->wrap(), $CertificateRepository->status);
        }
        return response($CertificateRepository->data);
    }

    /**
     * 添加证书
     * @return Response
     */
    public function certificateAdd(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;

        $CertificateRepository= new CertificateRepository($input);
        $CertificateRepository ->dofunction = 'add';
        $CertificateRepository->contract();

        if (! $CertificateRepository->passes()) {
            return response()->json($CertificateRepository->wrap(), $CertificateRepository->status);
        }
        return response('');
    }

    /**
     * 编辑证书
     * @return Response
     */
    public function certificateEdit(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;

        $CertificateRepository= new CertificateRepository($input);
        $CertificateRepository ->dofunction = 'edit';
        $CertificateRepository->contract();

        if (! $CertificateRepository->passes()) {
            return response()->json($CertificateRepository->wrap(), $CertificateRepository->status);
        }
        return response('');
    }

    /**
     * 删除证书
     * @return Response
     */
    public function certificateDel(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $CertificateRepository= new CertificateRepository($input);
        $CertificateRepository ->dofunction = 'del';
        $CertificateRepository->contract();

        if (! $CertificateRepository->passes()) {
            return response()->json($CertificateRepository->wrap(), $CertificateRepository->status);
        }
        return response('');
    }

    /**
     * 获取去城市列表
     * @return json
     */
    public function cityList(){
        $CityRepository= new CityRepository(Input::all());
        $CityRepository ->dofunction = 'getList';
        $CityRepository->contract();

        if (! $CityRepository->passes()) {
            return response()->json($CityRepository->wrap(), $CityRepository->status);
        }
        return response($CityRepository->data);
    }

    /**
     * 添加城市
     * @return Response
     */
    public function cityAdd(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;

        $CityRepository= new CityRepository($input);
        $CityRepository ->dofunction = 'add';
        $CityRepository->contract();

        if (! $CityRepository->passes()) {
            return response()->json($CityRepository->wrap(), $CityRepository->status);
        }
        return response('');
    }

    /**
     * 编辑城市
     * @return Response
     */
    public function cityEdit(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $CityRepository= new CityRepository($input);
        $CityRepository ->dofunction = 'edit';
        $CityRepository->contract();

        if (! $CityRepository->passes()) {
            return response()->json($CityRepository->wrap(), $CityRepository->status);
        }
        return response('');
    }

    /**
     * 删除城市
     * @return Response
     */
    public function cityDel(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $CityRepository= new CityRepository($input);
        $CityRepository ->dofunction = 'del';
        $CityRepository->contract();

        if (! $CityRepository->passes()) {
            return response()->json($CityRepository->wrap(), $CityRepository->status);
        }
        return response('');
    }


    /**
     * 获取去院校列表
     * @return json
     */
    public function schoolList(){
        $SchoolRepository= new SchoolRepository(Input::all());
        $SchoolRepository ->dofunction = 'getList';
        $SchoolRepository->contract();

        if (! $SchoolRepository->passes()) {
            return response()->json($SchoolRepository->wrap(), $SchoolRepository->status);
        }
        return response($SchoolRepository->data);
    }

    /**
     * 添加院校
     * @return Response
     */
    public function schoolAdd(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;

        $SchoolRepository= new SchoolRepository($input);
        $SchoolRepository ->dofunction = 'add';
        $SchoolRepository->contract();

        if (! $SchoolRepository->passes()) {
            return response()->json($SchoolRepository->wrap(), $SchoolRepository->status);
        }
        return response('');
    }

    /**
     * 编辑院校
     * @return Response
     */
    public function schoolEdit(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $SchoolRepository= new SchoolRepository($input);
        $SchoolRepository ->dofunction = 'edit';
        $SchoolRepository->contract();

        if (! $SchoolRepository->passes()) {
            return response()->json($SchoolRepository->wrap(), $SchoolRepository->status);
        }
        return response('');
    }

    /**
     * 删除院校
     * @return Response
     */
    public function schoolDel(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $SchoolRepository= new SchoolRepository($input);
        $SchoolRepository ->dofunction = 'del';
        $SchoolRepository->contract();

        if (! $SchoolRepository->passes()) {
            return response()->json($SchoolRepository->wrap(), $SchoolRepository->status);
        }
        return response('');
    }
}
