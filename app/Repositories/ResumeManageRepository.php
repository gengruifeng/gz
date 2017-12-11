<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Validator;
use Log;
use App\Utils\HttpStatus;
use App\Repositories\Repository;
use App\Repositories\RepositoryInterface;

use App\Entity\CvArchives;
use App\Utils\Pagination;

class ResumeManageRepository extends Repository implements RepositoryInterface
{

    public $dofunction;

    public $doreturn;

    public function contract()
    {
        $funtion = $this->dofunction;
        $this->$funtion();
    }

    /**
     * 返回结果
     * @return array
     */
    public function wrap(){
        $wrapper = [];
        if (!$this->passes()) {
            $wrapper = [
                'error_id' => $this->status,
                'description' => $this->description,
                'error_name' => HttpStatus::$statusTexts[$this->status],
                'errors' => $this->errors->getErrors(),
            ];
        }
        return $wrapper;
    }

    /**
     * 简历管理列表
     * @return array
     */
    public function resumelist(){
        $pageSize = $this->input['pageSize'];
        $page = empty($this->input['page'])?1:$this->input['page'];
        if(intval($page)<1){
            $page = 1;
        }
        $resumelist = DB::table('cv_archives')
            ->where(['uid'=>$this->input['uid'],'delete'=>0])
            ->orderby("updated_at","desc")
            ->take($pageSize)
            ->skip(($page-1)*$pageSize)
            ->get();
        if(empty($resumelist)){
            $this->status = 404;
            $this->description = "没有数据了！";
            $this->accepted = false;
        }
        if($this->passes()){
            return $resumelist;
        }
    }
    


    /**
 * 选择简历模板
 * @return array
 */
    public function resumeSelect(){
        $result = [];
        $persons= DB::table('cv_persons')
            ->select('id','name','mobile','email','gender','birthday','province','city','resumeavatar','status','updated_at')
            ->where('uid',$this->input['uid'])
            ->first();
        if($persons === null){
            $this->status = 400;
            $this->description = '简历信息不完善';
            $this->accepted = false;
        }else{
            if(time() > strtotime($persons->birthday) ){
                $persons->age = ceil((time()-strtotime($persons->birthday))/(86400*365));
            }else{
                $persons->age = 0;
            }
            $persons->birthday = date("Y",strtotime($persons->birthday))."年".date("n",strtotime($persons->birthday))."月";
            $result['persons'] = $persons;
            $result['experiences'] = !empty($this->selectExperiences($persons->id))?$this->selectExperiences($persons->id):[];
            $result['educations'] = !empty($this->selectEducations($persons->id))?$this->selectEducations($persons->id):[];
            $result['interests'] = !empty($this->selectInterests($persons->id))?$this->selectInterests($persons->id):[];
            $result['honors'] = !empty($this->selectHonors($persons->id))?$this->selectHonors($persons->id):[];
            $result['projects'] = !empty($this->selectProjects($persons->id))?$this->selectProjects($persons->id):[];
            $result['advices'] = !empty($this->selectAdvices($persons->id))?$this->selectAdvices($persons->id):[];
            $result['diplomas'] = !empty($this->selectDiplomas($persons->id))?$this->selectDiplomas($persons->id):[];
        }
        return $result;
    }

    /**
     * 个人经历
     * @return array
     */
    private function selectExperiences($uid){
        $experiences = DB::table('cv_experiences')
            ->select('from','to','company','position','jobdescription','updated_at')
            ->where('cvid',$uid)
            ->orderby('from','desc')
            ->get();
        if($experiences != null){
            foreach($experiences as $experience){
                $experience->experiencetime = date("Y.n",strtotime($experience->from))."-".date("Y.n",strtotime($experience->to));
            }
        }
        return $experiences;
        
    }

    /**
     * 教育背景
     * @return array
     */
    private function selectEducations($uid){
        $educations = DB::table('cv_educations')
            ->select('enrolled','graduated','school','department','education','success','updated_at')
            ->where('cvid',$uid)
            ->orderby('enrolled','desc')
            ->get();
        if($educations != null){
            foreach($educations as $education){
                $education->educationtime = date("Y.n",strtotime($education->enrolled))."-".date("Y.n",strtotime($education->graduated));
            }
        }
        return $educations;
    }

    /**
     * 兴趣爱好
     * @return array
     */
    private function selectInterests($uid){
        return DB::table('cv_interests')
            ->select('interests','updated_at')
            ->where('cvid',$uid)
            ->first();
    }

    /**
     * 荣誉奖项
     * @return array
     */
    private function selectHonors($uid){
        $honors = DB::table('cv_honors')
            ->select('received_at','award','updated_at')
            ->where('cvid',$uid)
            ->get();
        if($honors != null){
            foreach($honors as $honor){
                $honor->honortime = date("Y",strtotime($honor->received_at));
                $honor->yearmonth = date("Y",strtotime($honor->received_at))."年".date("n",strtotime($honor->received_at))."月";
            }
        }
        return $honors;
    }

    /**
     * 技能证书
     * @return array
     */
    private function selectDiplomas($uid){
        return DB::table('cv_diplomas')
            ->select('certificate','supplementary','achivement','updated_at')
            ->where('cvid',$uid)
            ->get();
    }

    /**
     * 个人作品
     * @return array
     */
    private function selectProjects($uid){
        $projects = DB::table('cv_projects')
            ->select('worked_at','title','subtitle','description','updated_at')
            ->where('cvid',$uid)
            ->get();
        if($projects != null){
            foreach($projects as $project){
                $project->projecttime = date("Y.m",strtotime($project->worked_at));
            }
        }
        return $projects;
    }

    /**
     * 求职意向
     * @return array
     */
    private function selectAdvices($uid){
        return DB::table('cv_advices')
            ->select('word_period','city','position','salary','employment_type','job_type','updated_at')
            ->where('cvid',$uid)
            ->first();
    }

    /**
     * 保存简历模板
     * @return array
     */
    private function resumeSave(){
        $this->checkresumeParameters();
        if($this->passes()){
            $cvArchives = new CvArchives;
            $cvArchives->uid = $this->input['uid'];
            $cvArchives->cvid = $this->input['cvid'];
            $cvArchives->title = $this->input['title'];
            $cvArchives->model = $this->input['resumemodel'];
            if(!$cvArchives->save()){
                Log::error("保存简历失败，原因为数据添加失败，用户ID为 ".$this->input['uid'].",简历UID为".$this->input['cvid']);
                $this->status = 500;
                $this->description = '数据添加失败';
                $this->accepted = false;
            }
        }
    }

    /**
     * 删除简历
     * @return array
     */
    private function resumeDelete(){
        $cvArchives =CvArchives::find($this->input['id']);
        if($cvArchives->uid ==$this->input['uid']){
            $cvArchives->delete = 1;
            if(!$cvArchives->save()){
                Log::error("删除简历失败，原因为简历已被删除");
                $this->status = 500;
                $this->description = '删除数据失败';
                $this->accepted = false;
                $this->errors->add($this->input['id'], $this->description);
            }
        }else{
            $this->status = 401;
            $this->description = '无权限删除';
            $this->accepted = false;
            $this->errors->add($this->input['id'], $this->description);
        }
    }

    /**
     * 修改简历模板
     * @return array
     */
    private function resumeUpdateTitle(){
        if(!empty($this->input['title'])){
            $cvArchives =CvArchives::find($this->input['id']);
            if($cvArchives->uid == $this->input['uid']){
                if($cvArchives != null){
                    $cvArchives->title = $this->input['title'];
                    if(!$cvArchives->save()){
                        Log::error("修改简历标题失败，原因为数据写入失败，用户ID为 ".$this->input['uid'].",简历标题为".$this->input['title']);
                        $this->status = 500;
                        $this->description = '修改标题失败';
                        $this->accepted = false;
                        $this->errors->add($this->input['id'], $this->description);
                    }
                }
            }else{
                $this->status = 401;
                $this->description = '无权限修改';
                $this->accepted = false;
                $this->errors->add($this->input['id'], $this->description);
            }
        }else{
            $this->status = 400;
            $this->description = '请填写简历标题';
            $this->accepted = false;
            $this->errors->add($this->input['id'], $this->description);
        }

    }


    /**
     * 验证简历参数
     * @return array
     */
    private function checkresumeParameters()
    {
        $rules = [
            'cvid'=>'required|exists:cv_persons,id',
            'title' => 'required',
        ];
        $messages = [
            'cvid.required'=>'简历信息不存在',
            'cvid.exists'=>'简历信息不存在',
            'title.required'=>'请更新您的简历标题',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('cvid')) {
                $this->errors->add('cvid', $messages->first('cvid'));
            }
            if ($messages->has('title')) {
                $this->errors->add('title', $messages->first('title'));
            }
        }
    }


    /**
     * 下载简历
     * @return array
     */
    public function resumeDownload($id){
        $result = [];
        $persons= DB::table('cv_persons')
            ->select('id','name','mobile','email','gender','birthday','province','city','resumeavatar','status','updated_at')
            ->where('id',$id)
            ->first();

        if($persons === null){
            $this->status = 400;
            $this->description = '简历信息不完善';
            $this->accepted = false;
        }else{
            if(time() > strtotime($persons->birthday) ){
                $persons->age = ceil((time()-strtotime($persons->birthday))/(86400*365));
            }else{
                $persons->age = 0;
            }
            $persons->birthday = date("Y",strtotime($persons->birthday))."年".date("n",strtotime($persons->birthday))."月";
            $result['persons'] = $persons;
            $result['experiences'] = !empty($this->selectExperiences($persons->id))?$this->selectExperiences($persons->id):[];
            $result['educations'] = !empty($this->selectEducations($persons->id))?$this->selectEducations($persons->id):[];
            $result['interests'] = !empty($this->selectInterests($persons->id))?$this->selectInterests($persons->id):[];
            $result['honors'] = !empty($this->selectHonors($persons->id))?$this->selectHonors($persons->id):[];
            $result['projects'] = !empty($this->selectProjects($persons->id))?$this->selectProjects($persons->id):[];
            $result['advices'] = !empty($this->selectAdvices($persons->id))?$this->selectAdvices($persons->id):[];
            $result['diplomas'] = !empty($this->selectDiplomas($persons->id))?$this->selectDiplomas($persons->id):[];
        }
        return $result;
    }

}
