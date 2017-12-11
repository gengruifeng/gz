<?php

namespace App\Repositories\Admin;

use App\Entity\Competence;
use App\Entity\UserProficiencies;
use App\Utils\Tree;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Utils\HttpStatus;
use App\Repositories\Repository;
use App\Repositories\RepositoryInterface;
use Log;

class UserGroupRepository extends Repository implements RepositoryInterface
{

    public $dofunction;

    public $returnData;

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

    protected function add(){
        $this->validador();
        if($this->passes()){
            $this->addData();
        }
    }

    protected function validador($action = 'add'){
        $rules = [
            'name'=>"required|unique:user_groups,name",
        ];
        $messages = [
            'name.required'=>'用户组名称不能为空',
            'name.unique'=>'用户组已经存在',
        ];
        if($action != 'add'){
            $rules['id'] = "required|integer";
            $messages['id.required'] = 'id不能为空';
            $messages['id.integer'] = 'id数据格式不正确';
        }
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();

            if ($messages->has('name')) {
                $this->errors->add('name', $messages->first('name'));
            }

            if ($messages->has('id')) {
                $this->errors->add('id', $messages->first('id'));
            }
        }
    }

    protected function addData(){
        $id = DB::table('user_groups')->insertGetId(['name' => $this->input['name']]);

        if(!$id){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','入库失败');
        }
    }

    protected function editData(){
        DB::table('user_groups')->where('id',$this->input['id'])->update(['name' => $this->input['name']]);
    }

    protected function edit(){
        $this->validador();
        if($this->passes()){
            $this->editData();
        }
    }

    protected function getcon(){
        $this->getCompetenceAll();
    }

    /**
     * 获取所有的权限
     */
    protected function getCompetenceAll(){
        $ret = DB::table('competence')->select('id','pid as pId','name')->orderBy('order','desc')->get();
        if(!empty($ret)){

            $competence_id = $this->getUserCon();

            foreach ($ret as $k=>$v){
                $ret[$k]->open = 'true';

                if(in_array($v->id,$competence_id)){
                    $ret[$k]->checked = 'true';
                }
            }
            $this->returnData = $ret;
        }else{
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','没有权限');
        }
    }

    protected function getUserCon(){
        $userCon = DB::table('competence_group')->select('competence_id')->where('group_id',$this->input['id'])->get();
        $userConArr = [];
        if(!empty($userCon)){
            foreach ($userCon as $v){
                $userConArr[] = $v->competence_id;
            }
        }

        return $userConArr;
    }

    protected function saveUserCon(){
        $this->validadorCon();
        if($this->passes()){
            $this->updateUserCon();
        }
    }

    protected function validadorCon(){
        $rules = [
            'group_id'=>"required|exists:user_groups,id",
            'competence_id'=>"required|array",
        ];
        $messages = [
            'group_id.required'=>'用户组id不能为空',
            'group_id.exists'=>'用户组id数据不匹配',
            'competence_id.required'=>'权限id不能为空',
            'competence_id.array'=>'权限id数据格式不正确',
        ];

        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();

            if ($messages->has('group_id')) {
                $this->errors->add('group_id', $messages->first('group_id'));
            }

            if ($messages->has('group_id')) {
                $this->errors->add('group_id', $messages->first('group_id'));
            }
        }
    }

    protected function updateUserCon(){
        //开启事务
        DB::beginTransaction();
        $ret = DB::table('competence_group')->where('group_id',$this->input['group_id'])->get();
        if(!empty($ret)){
            $ret1 = DB::table('competence_group')->where('group_id',$this->input['group_id'])->delete();
            if(!$ret1){
                DB::rollBack();
                $this->status = 400;
                $this->description = '参数错误';
                $this->error_name = 'Bad Request';
                $this->accepted = false;
                $this->errors->add('id', '写入数据库失败！');
                return 0;
            }
        }

        foreach ($this->input['competence_id'] as $k=>$v){
            $ret2 = DB::table('competence_group')->insertGetId(['group_id' => $this->input['group_id'], 'competence_id' =>$v]);
            if(!$ret2){
                DB::rollBack();
                $this->status = 400;
                $this->description = '参数错误';
                $this->error_name = 'Bad Request';
                $this->accepted = false;
                $this->errors->add('id', '写入数据库失败！');
                return 0;
            }
        }
        DB::commit();
    }
}
