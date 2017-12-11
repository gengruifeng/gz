<?php

namespace App\Repositories;

use DB;
use Log;
use Validator;

use App\Utils\HttpStatus;
use App\Utils\Computing;

class ResumeRepository extends Repository implements RepositoryInterface
{
    /**
     * 调用方法
     *
     * $this->function()
     */
    public function contract()
    {
        Log::info('Showing user profile for user: 101');
        //验证tag标签是否唯一
        if ($this->passes()) {
            $funtion = $this->dofunction;
            $this->$funtion();
        }
        return $this->biz;
    }

    /**
     * Wrap the contract result to JSON object.
     *
     * {@inheritdoc}
     */
    public function wrap()
    {
        $wrapper = [];

        if (!$this->passes()) {
            $wrapper = [
                'error_id' => $this->status,
                'description' => $this->description,
                'error_name' => HttpStatus::$statusTexts[$this->status],
            ];

            if (!$this->errors->isEmpty()) {
                $errors = [];
                foreach ($this->errors->getErrors() as $key => $value) {
                    $errors[] = [
                        'input' => $key,
                        'message' => $value
                    ];
                }

                $wrapper['errors'] = $errors;
            }
        }

        return $wrapper;
    }

    /**
     * Validate the input
     *
     * @param void
     *
     * @return void
     */
    private function personsvalidate()
    {
        $rules = [
            'uid' => 'required',
            'mobile' => 'required|regex:/^1[34578][0-9]{9}$/|',
            'email' => 'required|email',
            'gender' => 'required',
            //'birthday' => 'required',
            'province' => 'required',
            'city' => 'required',
            'name'=>'required',
        ];
        $messages = [
            'uid.required' => '请登录',
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式不正确',
            'mobile.required' => '请输入手机号',
            'mobile.regex' => '手机号格式失败',
            'gender.required' => '请选择性别',
            //'birthday.required' => '请选择出生年月',
            'province.required' => '请选择省份',
            'city.required' => '请选择城市',
            'name.required' => '姓名不能为空'
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '请输入信息';
            $this->accepted = false;

            $messages = $validator->errors();

            if ($messages->has('name')) {
                $this->errors->add('name', $messages->first('name'));
            }
            if ($messages->has('mobile')) {
                $this->errors->add('mobile', $messages->first('mobile'));
            }
            if ($messages->has('email')) {
                $this->errors->add('email', $messages->first('email'));
            }
            if ($messages->has('gender')) {
                $this->errors->add('gender', $messages->first('gender'));
            }
            if ($messages->has('birthday')) {
                $this->errors->add('birthday', $messages->first('birthday'));
            }
            if ($messages->has('province')) {
                $this->errors->add('province', $messages->first('province'));
            }
            if ($messages->has('city')) {
                $this->errors->add('city', $messages->first('city'));
            }
        }
    }
    /**
     * 添加个人信息
     *
     * @return array
     */
    private function persons()
    {
        $persons = DB::table('cv_persons')
            ->select('id')
            ->where(
                'uid','=',$this->input['uid']
            )
            ->first();
        $this->personsvalidate();
        if($this->passes()){
            if(empty($persons)){
                $personsid = DB::table('cv_persons')->insertGetId(
                    [
                        'uid' => $this->input['uid'],
                        'name' => $this->input['name'],
                        'mobile' => $this->input['mobile'],
                        'email' => $this->input['email'],
                        'gender' => $this->input['gender'],
                        'birthday' => $this->input['birthday'],
                        'province' => $this->input['province'],
                        'resumeavatar' =>'head.png',
                        'city' => $this->input['city'],
                        'status' => $this->input['status'],
                        'created_at' => date('y-m-d h:i:s', time()),
                        'updated_at' => date('y-m-d h:i:s', time())
                    ]
                );
                if(!$personsid){
                    Log::error("添加个人简历失败,用户ID为 ".$this->input['uid']."");

                    $this->status = 404;
                    $this->description = "添加个人简介失败";
                    $this->accepted = false;
                }
                return ;
            }
            if($this->passes()){
                //执行修改
                $revisepersons = DB::table('cv_persons')
                    ->where([
                        ['uid','=',$this->input['uid']],
                        ['id','=',$persons->id]
                    ])
                    ->update(
                        [
                            'name' => $this->input['name'],
                            'mobile' => $this->input['mobile'],
                            'email' => $this->input['email'],
                            'gender' => $this->input['gender'],
                            'birthday' => $this->input['birthday'],
                            'province' => $this->input['province'],
                            'city' => $this->input['city'],
                            'status' => $this->input['status'],
                            'updated_at' => date('y-m-d h:i:s', time())
                        ]
                    );
                if(!$revisepersons){
                    Log::error("修改个人简历失败,用户ID为 ".$this->input['uid']."");

                    $this->status = 404;
                    $this->description = "修改个人简介失败";
                    $this->accepted = false;
                }
            }
            return ;
        }
    }
    /**
     * 修改用户简历图片
     *
     * @return array
     */
    private function resumeupload()
    {
        $persons = DB::table('cv_persons')
            ->select('id')
            ->where(
                'uid','=',$this->input['uid']
            )
            ->first();
        if(empty($persons)){
            $personsid = DB::table('cv_persons')->insert(
                [
                    'uid' => $this->input['uid'],
                    'resumeavatar' => $this->input['resumeavatar'],
                    'created_at' => date('y-m-d h:i:s', time()),
                    'updated_at' => date('y-m-d h:i:s', time())
                ]
            );
            if(!$personsid){
                Log::error("新增简历图片失败,用户ID为 ".$this->input['uid']."");

                $this->status = 404;
                $this->description = "新增简历图片失败";
                $this->accepted = false;
            }
            return ;
        }
        $res = DB::table('cv_persons')
            ->select('resumeavatar')
            ->where([
                ['uid','=',$this->input['uid']],
                ['id','=',$persons->id]
            ])->first();
        if(!empty($res->resumeavatar)){
            @unlink ('resume/'.$res->resumeavatar);
        }
        $revisepersons = DB::table('cv_persons')
            ->where([
                ['uid','=',$this->input['uid']],
                ['id','=',$persons->id]
            ])
            ->update(
                [
                    'resumeavatar' => $this->input['resumeavatar'],
                    'updated_at' => date('y-m-d h:i:s', time())
                ]
            );
        if($revisepersons){
            Log::error("修改简历图片失败,用户ID为 ".$this->input['uid'].",个人信息ID为 ".$persons->id."");

            $this->status = 404;
            $this->description = "修改简历图片失败";
            $this->accepted = false;
        }
    }

     /**
     * 简历基本信息
     *
     * @return array
     */
    private function resumeinfo()
    {
        $this->educations();
        $this->experiences();
        if ($this->passes()){
            $revisepersons = DB::table('cv_persons')
                ->where([
                    ['uid','=',$this->input['uid']],
                    ['id','=',$this->input['cvid']]
                ])
                ->update(
                    [
                        'status' => 2,
                        'updated_at' => date('y-m-d h:i:s', time())
                    ]
                );
        }
    }
    /**
     * 校检教育背景
     *
     * @param void
     *
     * @return void
     */
    private function educationsvalidate()
    {
        $educationsrules = [
            'enrolled' => 'required',
            'graduated' => 'required',
            'school' => 'required',
            'department' => 'required',
            'education' => 'required',
        ];
        $educationsmessages = [
            'enrolled.required' => '请选择入学年份',
            'graduated.required' => '请选择毕业年份',
            'school.required' => '请填写学校',
            'department.required' => '请填写专业',
            'education.required' => '请选择学历',
        ];
        //校检教育背景
        foreach ($this->input['educations'] as $val){
            $validator = Validator::make($val, $educationsrules, $educationsmessages);
            if ($validator->fails()) {
                $this->status = 400;
                $this->description = '请输入信息';
                $this->accepted = false;

                $messages = $validator->errors();

                if ($messages->has('enrolled')) {
                    $this->errors->add('enrolled', $messages->first('enrolled'));
                }
                if ($messages->has('education')) {
                    $this->errors->add('education', $messages->first('education'));
                }
                if ($messages->has('graduated')) {
                    $this->errors->add('graduated', $messages->first('graduated'));
                }
                if ($messages->has('school')) {
                    $this->errors->add('school', $messages->first('school'));
                }
                if ($messages->has('department')) {
                    $this->errors->add('department', $messages->first('department'));
                }
            }
        }
    }
    /**
     * 教育背景
     *
     * @return array
     */
    public function educations()
    {
        if(empty($this->input['educations'])){
            $this->status = 400;
            $this->description = "填写的信息为空";
            $this->accepted = false;
        }
        $this->educationsvalidate();
        if($this->passes()){
            //判断是添加教育背景还是修改
            if(empty( $this->input['educationid'])){
                foreach ($this->input['educations'] as $val){
                    $educations = DB::table('cv_educations')->insert(
                        [
                            'cvid' => $this->input['cvid'],
                            'enrolled' => $val['enrolled'],
                            'graduated' => $val['graduated'],
                            'school' => $val['school'],
                            'department' => $val['department'],
                            'education' => $val['education'],
                            'success' => empty($val['success'])?'':$val['success'],
                            'created_at' => date('y-m-d h:i:s', time()),
                            'updated_at' => date('y-m-d h:i:s', time())
                        ]
                    );
                    if(!$educations){
                        Log::error("添加教育背景失败,用户ID为 ".$this->input['uid']."，简历ID为 ".$this->input['cvid']."");
                        $this->status = 500;
                        $this->description = "添加教育背景失败";
                        $this->accepted = false;
                        break;
                    }
                }
                return ;
            }
            //执行修改
            $educations=DB::table('cv_educations')
                ->where([
                    ['cvid','=',$this->input['cvid']],
                    ['id','=',$this->input['educationid']]
                ])
                ->update(
                    [
                        'enrolled' => $this->input['educations'][0]['enrolled'],
                        'graduated' => $this->input['educations'][0]['graduated'],
                        'school' => $this->input['educations'][0]['school'],
                        'department' => $this->input['educations'][0]['department'],
                        'education' => $this->input['educations'][0]['education'],
                        'success' => $this->input['educations'][0]['success'],
                        'updated_at' => date('y-m-d h:i:s', time())
                    ]
                );
            if(!$educations){
                Log::error("修改教育背景失败,用户ID为 ".$this->input['uid']."，简历ID为 ".$this->input['cvid'].",教育背景ID ".$this->input['educationid']."");
                $this->status = 500;
                $this->description = "修改教育背景失败";
                $this->accepted = false;
            }
        }

    }
    /**
     * 校检个人经历
     *
     * @param void
     *
     * @return void
     */
    private function experiencesvalidate()
    {
        $experiencesrules = [
            'from' => 'required',
            'to' => 'required',
            'company' => 'required',
            'position' => 'required',
        ];
        $experiencesmessages = [
            'from.required' => '请选择起始年份',
            'to.required' => '请选择结束年份',
            'company.required' => '请填写公司名称',
            'position.required' => '请填写职位',
        ];

        foreach ($this->input['experiences'] as $val){
            $validator = Validator::make($val, $experiencesrules, $experiencesmessages);
            if ($validator->fails()) {
                $this->status = 400;
                $this->description = '请输入信息';
                $this->accepted = false;

                $messages = $validator->errors();

                if ($messages->has('from')) {
                    $this->errors->add('from', $messages->first('from'));
                }
                if ($messages->has('to')) {
                    $this->errors->add('to', $messages->first('to'));
                }
                if ($messages->has('company')) {
                    $this->errors->add('company', $messages->first('company'));
                }
                if ($messages->has('position')) {
                    $this->errors->add('position', $messages->first('position'));
                }
            }
        }
    }
    /**
     * 个人经历
     *
     * @return array
     */
    public function experiences()
    {
        if(empty($this->input['experiences'])){
            $this->status = 400;
            $this->description = "填写的信息为空";
            $this->accepted = false;
        }
        $this->experiencesvalidate();
        if($this->passes()){
            if(empty($this->input['experienceid'])){
                foreach ($this->input['experiences'] as $val){
                    $experiences = DB::table('cv_experiences')->insert(
                        [
                            'cvid'=>$this->input['cvid'],
                            'from' => $val['from'],
                            'to' => $val['to'],
                            'company' => $val['company'],
                            'position' => $val['position'],
                            'jobdescription' => empty($val['jobdescription'])?'':$val['jobdescription'],
                            'created_at' => date('y-m-d h:i:s', time()),
                            'updated_at' => date('y-m-d h:i:s', time())
                        ]
                    );
                    if(!$experiences){
                        Log::error("新增个人经历失败,用户ID为 ".$this->input['uid']."，简历ID为 ".$this->input['cvid']."");

                        $this->status = 500;
                        $this->description = "失败";
                        $this->accepted = false;
                        break;
                    }
                }
                return ;
            }
            //执行修改
           $experiences = DB::table('cv_experiences')
                ->where([
                    ['cvid','=',$this->input['cvid']],
                    ['id','=',$this->input['experienceid']]
                ])
                ->update(
                    [
                        'from' => $this->input['experiences'][0]['from'],
                        'to' => $this->input['experiences'][0]['to'],
                        'company' => $this->input['experiences'][0]['company'],
                        'position' => $this->input['experiences'][0]['position'],
                        'jobdescription' => $this->input['experiences'][0]['jobdescription'],
                        'updated_at' => date('y-m-d h:i:s', time())
                    ]
                );
            if(!$experiences){
                Log::error("修改个人经历失败,用户ID为 ".$this->input['uid']."，简历ID为 ".$this->input['cvid'].",个人经历ID为 ".$this->input['experienceid']."");

                $this->status = 500;
                $this->description = "失败";
                $this->accepted = false;
            }

        }
    }
    /**
     * 校检求职意向
     *
     * @param void
     *
     * @return void
     */
    private function advicesvalidate()
    {
        $experiencesrules = [
            'cvid' => 'required',
            'word_period' => 'required',
            'city' => 'required',
            'position' => 'required',
            'salary' => 'required',
            'employment_type' => 'required',
            'job_type' => 'required',
        ];
        $experiencesmessages = [
            'cvid.required' => '未获取到简历！',
            'word_period.required' => '请选择工作年限',
            'city.required' => '请填写期望城市',
            'position.required' => '请填写期望职位',
            'salary.required' => '请选择期望月薪',
            'employment_type.required' => '请填写工作性质',
            'job_type.required' => '请选择求职状态',
        ];

        $validator = Validator::make($this->input, $experiencesrules, $experiencesmessages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '请输入信息';
            $this->accepted = false;

            $messages = $validator->errors();

            if ($messages->has('cvid')) {
                $this->errors->add('cvid', $messages->first('cvid'));
            }
            if ($messages->has('from')) {
                $this->errors->add('from', $messages->first('from'));
            }
            if ($messages->has('to')) {
                $this->errors->add('to', $messages->first('to'));
            }
            if ($messages->has('company')) {
                $this->errors->add('company', $messages->first('company'));
            }
            if ($messages->has('position')) {
                $this->errors->add('position', $messages->first('position'));
            }
        }
    }
    /**
     * 求职意向
     *
     * @return array
     */
    public function advices()
    {
        $this->advicesvalidate();
        if($this->passes()){
            $advices =
                DB::table('cv_advices')
                ->select(
                    'word_period',
                    'city',
                    'position',
                    'salary',
                    'employment_type',
                    'job_type',
                    'updated_at'
                )
                ->where('cvid','=',$this->input['cvid'])
                ->first();
            //如果为空就添加，不为空则为修改
            if(empty($advices)){
                $addadvices = DB::table('cv_advices')->insert(
                    [
                        'cvid' => $this->input['cvid'],
                        'word_period' => $this->input['word_period'],
                        'province' => $this->input['province'],
                        'city' => $this->input['city'],
                        'position' => $this->input['position'],
                        'salary' => $this->input['salary'],
                        'employment_type' => $this->input['employment_type'],
                        'job_type' => $this->input['job_type'],
                        'created_at' => date('y-m-d h:i:s', time()),
                        'updated_at' => date('y-m-d h:i:s', time())
                    ]
                );
                if(!$addadvices){
                    Log::error("新增求职意向失败,用户ID为 ".$this->input['uid']."，简历ID为 ".$this->input['cvid']."");

                    $this->status = 500;
                    $this->description = "失败";
                    $this->accepted = false;
                }
                return ;
            }
            //执行修改
            $advices=DB::table('cv_advices')
                ->where([
                    ['cvid','=',$this->input['cvid']]
                ])
                ->update(
                    [
                        'word_period' => $this->input['word_period'],
                        'province' => $this->input['province'],
                        'city' => $this->input['city'],
                        'position' => $this->input['position'],
                        'salary' => $this->input['salary'],
                        'employment_type' => $this->input['employment_type'],
                        'job_type' => $this->input['job_type'],
                        'updated_at' => date('y-m-d h:i:s', time())
                    ]
                );
            if(!$advices){
                Log::error("修改求职意向失败,用户ID为 ".$this->input['uid']."，简历ID为 ".$this->input['cvid']."");

                $this->status = 500;
                $this->description = "失败";
                $this->accepted = false;
            }
        }
    }
    /**
     * 校检技能证书
     *
     * @param void
     *
     * @return void
     */
    private function diplomasvalidate()
    {
        $diplomasrules = [
            'achivement' => 'required',
        ];
        $diplomasmessages = [
            'achivement.required' => '成绩不能为空',
        ];
        //校检教育背景
        foreach ($this->input['diplomas'] as $val){
            $validator = Validator::make($val, $diplomasrules, $diplomasmessages);
            if ($validator->fails()) {
                $this->status = 400;
                $this->description = '请输入信息';
                $this->accepted = false;

                $messages = $validator->errors();

                if ($messages->has('achivement')) {
                    $this->errors->add('achivement', $messages->first('achivement'));
                }
            }
        }
    }
    /**
     * 技能证书
     *
     * @return array
     */
    public function diplomas()
    {
        if(empty($this->input['diplomas'])){
            $this->status = 400;
            $this->description = "填写的信息为空";
            $this->accepted = false;
        }
        $this->diplomasvalidate();
        if($this->passes()){
            //判断是添加还是修改
            if(empty( $this->input['diplomaid'])){
                foreach ($this->input['diplomas'] as $val){
                    $diplomas = DB::table('cv_diplomas')->insert(
                        [
                            'cvid' => $this->input['cvid'],
                            'certificate' => $val['certificate'],
                            'supplementary' => $val['supplementary'],
                            'achivement' => $val['achivement'],
                            'created_at' => date('y-m-d h:i:s', time()),
                            'updated_at' => date('y-m-d h:i:s', time())
                        ]
                    );
                    if(!$diplomas){
                        Log::error("新增技能证书失败,用户ID为 ".$this->input['uid']."，简历ID为 ".$this->input['cvid']."");

                        $this->status = 500;
                        $this->description = "失败";
                        $this->accepted = false;
                        break;
                    }
                }
                return ;
            }
            if(!empty($this->input['diplomas'][0]['supplementary'])){
                $this->input['diplomas'][0]['certificate'] = '';
            }
            //执行修改
           $diplomas = DB::table('cv_diplomas')
                ->where([
                    ['cvid','=',$this->input['cvid']],
                    ['id','=',$this->input['diplomaid']]
                ])
                ->update(
                    [
                        'certificate' => $this->input['diplomas'][0]['certificate'],
                        'achivement' => $this->input['diplomas'][0]['achivement'],
                        'supplementary' => $this->input['diplomas'][0]['supplementary'],
                        'updated_at' => date('y-m-d h:i:s', time())
                    ]
                );
            if(!$diplomas){
                Log::error("修改技能证书失败,用户ID为 ".$this->input['uid']."，简历ID为 ".$this->input['cvid'].",技能证书ID为 ".$this->input['diplomaid']."");

                $this->status = 500;
                $this->description = "失败";
                $this->accepted = false;
            }
        }
    }
    /**
     * 校检奖项荣誉
     *
     * @param void
     *
     * @return void
     */
    private function honorsvalidate()
    {
        $honorsrules = [
            'received_at' => 'required',
            'award' => 'required',
        ];
        $honorsmessages = [
            'received_at.required' => '请选择获取日期',
            'award.required' => '奖项内容不能为空',
        ];
        //校检教育背景
        foreach ($this->input['honors'] as $val){
            $validator = Validator::make($val, $honorsrules, $honorsmessages);
            if ($validator->fails()) {
                $this->status = 400;
                $this->description = '请输入信息';
                $this->accepted = false;

                $messages = $validator->errors();

                if ($messages->has('cvid')) {
                    $this->errors->add('cvid', $messages->first('cvid'));
                }
                if ($messages->has('received_at')) {
                    $this->errors->add('received_at', $messages->first('received_at'));
                }
                if ($messages->has('award')) {
                    $this->errors->add('award', $messages->first('award'));
                }
            }
        }
    }
    /**
     * 奖项荣誉
     *
     * @return array
     */
    public function honors()
    {
        if(empty($this->input['honors'])){
            $this->status = 400;
            $this->description = "填写的信息为空";
            $this->accepted = false;
        }
        $this->honorsvalidate();
        if($this->passes()){
            //判断是添加还是修改
            if(empty( $this->input['honorid'])){
                foreach ($this->input['honors'] as $val){
                    $honors= DB::table('cv_honors')->insert(
                        [
                            'cvid' => $this->input['cvid'],
                            'received_at' => $val['received_at'],
                            'award' => $val['award'],
                            'created_at' => date('y-m-d h:i:s', time()),
                            'updated_at' => date('y-m-d h:i:s', time())
                        ]
                    );
                    if(!$honors){
                        Log::error("新增奖项荣誉失败,用户ID为 ".$this->input['uid']."，简历ID为 ".$this->input['cvid']."");

                        $this->status = 500;
                        $this->description = "失败";
                        $this->accepted = false;
                        break;
                    }
                }
                return ;
            }
            //执行修改
            $honors = DB::table('cv_honors')
                ->where([
                    ['cvid','=',$this->input['cvid']],
                    ['id','=',$this->input['honorid']]
                ])
                ->update(
                    [
                        'received_at' => $this->input['honors'][0]['received_at'],
                        'award' => $this->input['honors'][0]['award'],
                        'updated_at' => date('y-m-d h:i:s', time())
                    ]
                );
            if(!$honors){
                Log::error("修改奖项荣誉失败,用户ID为 ".$this->input['uid']."，简历ID为 ".$this->input['cvid']."");

                $this->status = 500;
                $this->description = "修改奖项荣誉失败";
                $this->accepted = false;
            }
        }
    }
    /**
     * 校检个人作品
     *
     * @param void
     *
     * @return void
     */
    private function projectsvalidate()
    {
        $projectsrules = [
            'worked_at' => 'required',
            'title' => 'required',
            'description' => 'required',
        ];
        $projectsmessages = [
            'worked_at.required' => '请选择作品时间',
            'title.required' => '请输入作品名称',
            'description.required' => '请输入作品描述',
        ];
        //校检教育背景
        foreach ($this->input['projects'] as $val){
            $validator = Validator::make($val, $projectsrules, $projectsmessages);
            if ($validator->fails()) {
                $this->status = 400;
                $this->description = '请输入信息';
                $this->accepted = false;

                $messages = $validator->errors();

                if ($messages->has('cvid')) {
                    $this->errors->add('cvid', $messages->first('cvid'));
                }
                if ($messages->has('worked_at')) {
                    $this->errors->add('worked_at', $messages->first('worked_at'));
                }
                if ($messages->has('title')) {
                    $this->errors->add('title', $messages->first('title'));
                }
                if ($messages->has('description')) {
                    $this->errors->add('description', $messages->first('description'));
                }
            }
        }
    }
    /**
     * 个人作品
     *
     * @return array
     */
    public function projects()
    {
        if(empty($this->input['projects'])){
            $this->status = 400;
            $this->description = "填写的信息为空";
            $this->accepted = false;
        }
        $this->projectsvalidate();
        if($this->passes()){
            //判断是添加还是修改
            if(empty( $this->input['projectid'])){
                foreach ($this->input['projects'] as $val){
                    $projects = DB::table('cv_projects')->insert(
                        [
                            'cvid' => $this->input['cvid'],
                            'worked_at' => $val['worked_at'],
                            'title' => $val['title'],
                            'subtitle' => $val['subtitle'],
                            'description' => $val['description'],
                            'created_at' => date('y-m-d h:i:s', time()),
                            'updated_at' => date('y-m-d h:i:s', time())
                        ]
                    );
                    if(!$projects){
                        Log::error("新增个人作品失败,用户ID为 ".$this->input['uid']."，简历ID为 ".$this->input['cvid']."");

                        $this->status = 500;
                        $this->description = "失败";
                        $this->accepted = false;
                        break;
                    }
                }
                return ;
            }
            //执行修改
            $projects = DB::table('cv_projects')
                ->where([
                    ['cvid','=',$this->input['cvid']],
                    ['id','=',$this->input['projectid']]
                ])
                ->update(
                    [
                        'worked_at' => $this->input['projects'][0]['worked_at'],
                        'title' => $this->input['projects'][0]['title'],
                        'subtitle' => $this->input['projects'][0]['subtitle'],
                        'description' => $this->input['projects'][0]['description'],
                        'updated_at' => date('y-m-d h:i:s', time())
                    ]
                );
            if(!$projects){
                Log::error("修改个人作品失败,用户ID为 ".$this->input['uid']."，简历ID为 ".$this->input['cvid'].",个人作品ID为 ".$this->input['projectid']."");

                $this->status = 500;
                $this->description = "失败";
                $this->accepted = false;
            }
        }
    }
    /**
     * 校检兴趣爱好
     *
     * @param void
     *
     * @return void
     */
    private function interestsvalidate()
    {
        $interestsrules = [
            'cvid' => 'required',
            'interests' => 'required',
        ];
        $interestsmessages = [
            'cvid.required' => '未获取到简历！',
            'interests.required' => '请填写您的兴趣爱好',
        ];
        $validator = Validator::make($this->input, $interestsrules, $interestsmessages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '请输入信息';
            $this->accepted = false;

            $messages = $validator->errors();

            if ($messages->has('cvid')) {
                $this->errors->add('cvid', $messages->first('cvid'));
            }
            if ($messages->has('interests')) {
                $this->errors->add('interests', $messages->first('interests'));
            }
        }
    }
    /**
     * 兴趣爱好
     *
     * @return array
     */
    public function interests()
    {
        $this->interestsvalidate();
        if($this->passes()){
            $interests =
                DB::table('cv_interests')
                    ->select(
                        'id'
                    )
                    ->where('cvid','=',$this->input['cvid'])
                    ->first();
            //判断是添加还是修改
            if(empty( $interests)){
                $interest = DB::table('cv_interests')->insert(
                    [
                        'cvid' => $this->input['cvid'],
                        'interests' => $this->input['interests'],
                        'created_at' => date('y-m-d h:i:s', time()),
                        'updated_at' => date('y-m-d h:i:s', time())
                    ]
                );
                if(!$interest){
                    Log::error("新增兴趣爱好失败,用户ID为 ".$this->input['uid']."，简历ID为 ".$this->input['cvid']."");

                    $this->status = 500;
                    $this->description = "失败";
                    $this->accepted = false;
                }
                return ;
            }
            //执行修改
            $interests = DB::table('cv_interests')
                ->where([
                    ['cvid','=',$this->input['cvid']],
                    ['id','=',$interests->id,]
                ])
                ->update(
                    [
                        'interests' => $this->input['interests'],
                        'updated_at' => date('y-m-d h:i:s', time())
                    ]
                );
            if(!$interests){
                Log::error("修改兴趣爱好失败,用户ID为 ".$this->input['uid']."，简历ID为 ".$this->input['cvid'].",兴趣爱好ID为 ".$interests->id."");

                $this->status = 500;
                $this->description = "失败";
                $this->accepted = false;
            }
        }
    }
    /**
     * 删除教育背景
     *
     * @return array
     */
    public function educationdel()
    {
        if(empty( $this->input['id'])){
            $this->status = 404;
            $this->description = "参数有误";
            $this->accepted = false;
        }
        if($this->passes()){
            if(! DB::table('cv_educations')
                ->where([
                    ['cvid','=',$this->input['cvid']],
                    ['id','=',$this->input['id']]
                ])->delete()){
                Log::error("删除教育背景失败,用户ID为 ".$this->input['uid']."，教育背景ID为 ".$this->input['id']."");

                $this->status = 500;
                $this->description = "失败";
                $this->accepted = false;
            }
        }
    }
    /**
     * 删除个人经历
     *
     * @return array
     */
    public function experiencedel()
    {
        if(empty( $this->input['id'])){
            $this->status = 404;
            $this->description = "参数有误";
            $this->accepted = false;
        }
        if($this->passes()){
            if(! DB::table('cv_experiences')
                ->where([
                    ['cvid','=',$this->input['cvid']],
                    ['id','=',$this->input['id']]
                ])->delete()){
                Log::error("删除个人经历失败,用户ID为 ".$this->input['uid']."，个人经历ID为 ".$this->input['id']."");

                $this->status = 500;
                $this->description = "失败";
                $this->accepted = false;
            }
        }
    }
    /**
     * 删除技能证书
     *
     * @return array
     */
    public function diplomadel()
    {
        if(empty( $this->input['id'])){
            $this->status = 404;
            $this->description = "参数有误";
            $this->accepted = false;
        }
        if($this->passes()){
            if(! DB::table('cv_diplomas')
                ->where([
                    ['cvid','=',$this->input['cvid']],
                    ['id','=',$this->input['id']]
                ])->delete()){
                Log::error("删除技能证书失败,用户ID为 ".$this->input['uid']."，技能证书ID为 ".$this->input['id']."");

                $this->status = 500;
                $this->description = "失败";
                $this->accepted = false;
            }
        }
    }
    /**
     * 删除奖项荣誉
     *
     * @return array
     */
    public function honordel()
    {
        if(empty( $this->input['id'])){
            $this->status = 404;
            $this->description = "参数有误";
            $this->accepted = false;
        }
        if($this->passes()){
            if(! DB::table('cv_honors')
                ->where([
                    ['cvid','=',$this->input['cvid']],
                    ['id','=',$this->input['id']]
                ])->delete()){
                Log::error("删除奖项荣誉失败,用户ID为 ".$this->input['uid']."，简历ID为 ".$this->input['id']."");

                $this->status = 500;
                $this->description = "失败";
                $this->accepted = false;
            }
        }
    }
    /**
     * 删除个人作品
     *
     * @return array
     */
    public function projectdel()
    {
        if(empty( $this->input['id'])){
            $this->status = 404;
            $this->description = "参数有误";
            $this->accepted = false;
        }
        if($this->passes()){
            if(! DB::table('cv_projects')
                ->where([
                    ['cvid','=',$this->input['cvid']],
                    ['id','=',$this->input['id']]
                ])->delete()){
                Log::error("删除个人作品失败,用户ID为 ".$this->input['uid']."，个人作品ID为 ".$this->input['id']."");

                $this->status = 500;
                $this->description = "失败";
                $this->accepted = false;
            }
        }
    }
    /**
     * 校检跳转
     *
     * @return array
     */
    public function checkselect()
    {
        $persons = DB::table('cv_persons')
            ->select('id')
            ->where(
                'uid','=',$this->input['uid']
            )
            ->first();
        if(empty($persons)){
            $this->status = 402;
            $this->description = "未填写个人信息";
            $this->accepted = false;
        }
        if($this->passes()){
            $education = DB::table('cv_educations')
                ->select('id')
                ->where(
                    'cvid','=',$persons->id
                )
                ->first();
            if(empty($education)){
                $this->status = 402;
                $this->description = "未填写教育背景,";
                $this->accepted = false;
            }
            $experience = DB::table('cv_experiences')
                ->select('id')
                ->where(
                    'cvid','=',$persons->id
                )
                ->first();
            if(empty($experience)){
                $this->status = 402;
                $this->description .= "未填写个人经历";
                $this->accepted = false;
            }
        }

    }
    /**
     * 查询城市
     *
     * @return array
     */
    public function city()
    {
        $advices =
            DB::table('province_city')
                ->select(
                    'areaname',
                    'id'
                )
                ->where('parentid','=',$this->input['pid'])
                ->get();
        if (!empty($advices)){
            $this->biz = $advices;
        }
    }

}