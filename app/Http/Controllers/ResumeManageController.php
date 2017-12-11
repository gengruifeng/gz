<?php

namespace App\Http\Controllers;

use App\Repositories\ResumeManageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;
use App\Repositories\PersonalRespository;
use App\Entity\CvArchives;

class ResumeManageController extends Controller
{
    /**
     * 简历管理列表页面
     *
     * @param void
     *
     * @return void
     */
    public function resumelist()
    {
        return view('resume.resume_manage');
    }

    /**
     * 简历管理列表分页页面
     *
     * @param void
     *
     * @return void
     */
    public function resumePageList(Request $request)
    {
        $uid = $request->security()->get('uid');
        $input = $request->all();
        $input['uid'] = $uid;
        $input['pageSize'] = 10;
        $repo = new ResumeManageRepository($input);
        $result = $repo->resumelist();
        if (! $repo->passes()) {
            return response()->json($repo->wrap(), $repo->status);
        }
        return view('resume._resume_manage',[
            'resumelist'=>$result,
        ]);
    }

    /**
     * 简历模板页面
     * @param void
     * @return void
     */
    public function resumeSelect(Request $request)
    {
        $uid = $request->security()->get('uid');
        $input = $request->all();
        $input['uid'] = $uid;
        $resumeManage = new ResumeManageRepository($input);
        $result = $resumeManage->resumeSelect();
        if(empty($result['persons']) || empty($result['experiences']) ||empty($result['educations'])){
            return redirect(sprintf('/myresume'));
        }
        if(!empty($result)){
            return view('resume.resume_choice',[
                'persons'=>$result['persons'],
                'experiences'=> $result['experiences'],
                'educations'=>$result['educations'],
                'interests'=>$result['interests'],
                'honors'=>$result['honors'],
                'projects'=>$result['projects'],
                'advices'=>$result['advices'],
                'diplomas'=>$result['diplomas'],
            ]);
        }
    }

    /**
     * 选择简历模板
     * @param void
     * @return void
     */
    public function resumeChoice(Request $request ,$name)
    {
        $uid = $request->security()->get('uid');
        $input = $request->all();
        $input['uid'] = $uid;
        $resumeManage = new ResumeManageRepository($input);
        $result = $resumeManage->resumeSelect();
        //信息补全 跳转我的简历
        if(empty($result['persons']) || empty($result['experiences']) ||empty($result['educations'])){
            return redirect(sprintf('/myresume'));
        }
        if(!in_array($name,['first','second','third','fourth','fifth']) || $name == "first"){
            return redirect(sprintf('/resume/select'));
        }
        if(!empty($result)){
            return view('resume.resume_'.$name,[
                'persons'=>$result['persons'],
                'experiences'=> $result['experiences'],
                'educations'=>$result['educations'],
                'interests'=>$result['interests'],
                'honors'=>$result['honors'],
                'projects'=>$result['projects'],
                'advices'=>$result['advices'],
                'diplomas'=>$result['diplomas'],
            ]);
        }
    }

    /**
     * 简历下载
     * @param void
     * @return void
     */
    public function download(Request $request,$id)
    {
        $uid = $request->security()->get('uid');
        $archive = CvArchives::find($id);
        if($archive !==null && $archive->uid == $uid){
            $agent=$_SERVER["HTTP_USER_AGENT"];
            if(strpos($agent,'MSIE') || strpos($agent,'rv:11.0')){
                $archive->title = urlencode($archive->title);
            }
            if($archive !== null && !empty($archive->cvid) && !empty($archive->model)){
                $resumeManage = new ResumeManageRepository();
                $result = $resumeManage->resumeDownload($archive->cvid);
                $result['persons']->avatar = base64_encode(file_get_contents(public_path().'/resume/'.$result['persons']->resumeavatar));
                $str = view('resumemodel.'.$archive->model,[
                    'persons'=>$result['persons'],
                    'experiences'=>$result['experiences'],
                    'educations'=>$result['educations'],
                    'interests'=>$result['interests'],
                    'honors'=>$result['honors'],
                    'projects'=>$result['projects'],
                    'advices'=>$result['advices'],
                    'diplomas'=>$result['diplomas'],
                    'xml'=>'<?xml version="1.0" encoding="utf-8"?>',
                    'progid'=>'<?mso-application progid="Word.Document"?>',
                ])->render();
                if($this->creatFile($str,$archive->model)){
                    return response('')->withHeaders([
                        'X-Accel-Redirect' => sprintf('/xml/'.$archive->model.'.xml'),
                        'Content-Type' => 'application/octet-stream',
                        'Content-Disposition' => 'attachment; filename='.$archive->title.'.doc',
                    ]);
                }
            }
        }
    }
    /**
     * 将数据写入文件
     */
    function creatFile( $content,$model )
    {
        $fileName = public_path()."/xml/".$model.".xml";
        //以读写方式打写指定文件，如果文件不存则创建
        if( ($TxtRes=fopen ($fileName,"w+")) === FALSE){
            return false;
        }
        $StrConents = $content;//要 写进文件的内容
        if(!fwrite ($TxtRes,$StrConents)){ //将信息写入文件
            fclose($TxtRes);
            return false;
        }
        fclose ($TxtRes);
        return true;
    }

}
