<?php

namespace App\Repositories\Admin;

use App\Entity\Competence;
use App\Entity\UserProficiencies;
use App\Utils\Tree;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Utils\HttpStatus;
use App\Repositories\Repository;
use App\Repositories\RepositoryInterface;
use Log;

class CompetenceRepository extends Repository implements RepositoryInterface
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


    /**
     * 根据工作id获取用户权限
     */
    protected function getUserCon(){

        $ret =  Competence::whereIn('id', function ($query) {
            $query->select('competence_id')
                ->from('competence_group')
                ->where('group_id', $this->input['group_id']);
        })->where('level', '<=',1 )->orderBy('order','desc')->get();
        if(!empty($ret)){
            $ret = $ret->toArray();
            foreach ($ret as $k=>$v){
                if($v['level'] == 1){
                    $subCon = DB::table('competence')->select('con')->where('pid',$v['id'])->where('is_default', 1)->first();
                    if(!empty($subCon)){
                        $ret[$k]['con'] = $subCon->con;
                    }
                }
                $breadcrumbs = explode('-',$v['breadcrumbs']);
                $ret[$k]['module'] = !empty($breadcrumbs[0])?$breadcrumbs[0]:'';
                $ret[$k]['column'] = !empty($breadcrumbs[1])?$breadcrumbs[1]:'';

            }
            $this->returnData = Tree::menu_tree(0,$ret);
        }
    }

    /**
     * 获取当前的url
     */
    protected function getCurrent(){
        $name = \Route::currentRouteName();
        
        $ret = DB::table('competence')->select('breadcrumbs')->where('url_name',$name)->first();
        if(!empty($ret)){
            $breadcrumbs = explode('-',$ret->breadcrumbs);
            $this->returnData['module'] = !empty($breadcrumbs[0])?$breadcrumbs[0]:'';
            $this->returnData['column'] = !empty($breadcrumbs[1])?$breadcrumbs[1]:'';
            $this->returnData['m'] = $this->getCompetenceName($breadcrumbs[0]);
            $this->returnData['g'] = $this->getCompetenceName($breadcrumbs[1]);
            $this->returnData['l'] = $this->getCompetenceName($breadcrumbs[2]);
        }
    }

    /**
     * 获取权限名称
     * @param $id
     * @return string
     */
    protected function getCompetenceName($id){
        $name = '';
        $ret = DB::table('competence')->select('name')->where('id',$id)->first();
        if(!empty($ret->name)){
            $name = $ret->name;
        }
        return $name;
    }
    /**
     * 获取所有的权限
     */
    protected function getCompetenceAll(){
        $ret =  Competence::all()->toArray();
        $ret = collect($ret)->sortByDesc('order');
        if(!empty($ret)){
            $arr = Tree::menu_tree(0,$ret);
            $data = $this->initDataAll($arr);

            $this->returnData = $data;
        }else{
            $this->returnData = [];
        }
    }

    public function initDataAll($arr){
        $data =[];
        foreach ($arr as $k=>$v){
            $data[$k]['name'] =$v['name'];
            $data[$k]['level'] =$v['level'];
            $data[$k]['con'] =$v['con'];
            $data[$k]['url_name'] =$v['url_name'];
            $data[$k]['order'] =$v['order'];
            $data[$k]['is_default'] =$v['is_default'];
            if(!empty($v['childs'])){
                $data[$k]['type'] = 'folder';
                $data[$k]['additionalParameters']['id'] = $v['id'];
                $data[$k]['additionalParameters']['children'] = $this->initDataAll($v['childs']);
            }else{
                $data[$k]['type'] = 'item';
                $data[$k]['additionalParameters']['id'] = $v['id'];

            }
        }
        return $data;
    }

    public function add(){
        $this->validadoradd();
        if($this->passes()){
            $this->addData();
        }
    }

    protected function validadoradd(){
        $rules = [
            'pid'=>"required",
            'name'=>"required|unique:competence,name",
            'con'=>'unique:competence,con',
            'url_name'=>"unique:competence,url_name",
            'order'=>"integer",
            'is_default'=>'in:1,0',

        ];
        $messages = [
            'id.required'=>'id不能为空',
            'name.required'=>'权限名称不能为空',
            'name.unique'=>'权限名称已经存在',
            'con.required'=>'url不能为空',
            'con.unique'=>'url已经存在',
            'url_name.required'=>'url别名不能为空',
            'url_name.unique'=>'url别名已经存在',
            'order.integer'=>'排序类型不正确',
            'is_default.in'=>'是否默认页类型不正确',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('pid')) {
                $this->errors->add('pid', $messages->first('pid'));
            }

            if ($messages->has('name')) {
                $this->errors->add('name', $messages->first('name'));
            }

            if ($messages->has('con')) {
                $this->errors->add('con', $messages->first('con'));
            }

            if ($messages->has('url_name')) {
                $this->errors->add('url_name', $messages->first('url_name'));
            }

            if ($messages->has('order')) {
                $this->errors->add('order', $messages->first('order'));
            }

            if ($messages->has('is_default')) {
                $this->errors->add('is_default', $messages->first('is_default'));
            }
        }
    }

    public function addData(){

        $data = $this->initData();

        if($data['is_default'] == 1){
            DB::table('competence')->where('pid',$data['pid'])->where('is_default', 1)->update(['is_default'=>0]);
        }

        $id = DB::table('competence')->insertGetId($data);

        if(!$id){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','入库失败');
        }else{
            $ret = DB::table('competence')->select('id','level','pid')->where('id',$data['pid'])->first();
            if(!empty($ret) && $ret->level != 0){
                $ret1 = DB::table('competence')->select('id','level','pid')->where('id',$ret->pid)->first();
                if(!empty($ret1) && $ret1->level != 0){
                    $ret2 = DB::table('competence')->select('id','level','pid')->where('id',$ret1->pid)->first();
                }
            }

            $level = !empty($ret2->id)?$ret2->id.'-':'';
            $level .= !empty($ret1->id)?$ret1->id.'-':'';
            $level .= !empty($ret->id)?$ret->id.'-':'';
            $level .= $id;
            DB::table('competence')->where('id',$id)->update(['breadcrumbs'=>$level]);
        }


    }

    public function initData(){
        $data = [
            'pid' =>$this->input['pid'],
            'name' =>$this->input['name'],
            'con' =>$this->input['con'],
            'url_name' =>$this->input['url_name'],
            'order' =>$this->input['order'],
            'is_default' =>!empty($this->input['is_default'])?$this->input['is_default']:0,
        ];

        if($data['pid'] == 0){
            $data['level'] = 0;
        }else{
            $ret = DB::table('competence')->select('id','level')->where('id',$data['pid'])->first();
            $data['level'] = (int)$ret->level+1;
        }

        return $data;
    }

    public function getone(){
        $ret = DB::table('competence')->where('id',$this->input['id'])->first();
        if(!empty($ret)){
            $this->returnData = $ret;
        }else{
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','您查的信息不存在');
        }

    }


    public function edit(){
        $this->validadoredit();
        if($this->passes()){
            $this->updateData();
        }
    }

    public function updateData(){

        $data = $this->initData();

        if($data['is_default'] == 1){
            DB::table('competence')->where('pid',$data['pid'])->where('is_default', 1)->update(['is_default'=>0]);
        }
        $id = DB::table('competence')->where('id',$this->input['id'])->update($data);

        if(!$id){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','入库失败');
        }
    }

    protected function validadoredit(){
        $rules = [
            'pid'=>"required",
            'name'=>"required|unique:competence,name,{$this->input['id']}",
            'con'=>"unique:competence,con,{$this->input['id']}",
            'url_name'=>"unique:competence,url_name,{$this->input['id']}",
            'order'=>"integer",
            'is_default'=>'in:1,0',

        ];
        $messages = [
            'id.required'=>'id不能为空',
            'name.required'=>'权限名称不能为空',
            'name.unique'=>'权限名称已经存在',
            'con.required'=>'url不能为空',
            'con.unique'=>'url已经存在',
            'url_name.required'=>'url别名不能为空',
            'url_name.unique'=>'url别名已经存在',
            'order.integer'=>'排序类型不正确',
            'is_default.in'=>'是否默认页类型不正确',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('pid')) {
                $this->errors->add('pid', $messages->first('pid'));
            }

            if ($messages->has('name')) {
                $this->errors->add('name', $messages->first('name'));
            }

            if ($messages->has('con')) {
                $this->errors->add('con', $messages->first('con'));
            }

            if ($messages->has('url_name')) {
                $this->errors->add('url_name', $messages->first('url_name'));
            }

            if ($messages->has('order')) {
                $this->errors->add('order', $messages->first('order'));
            }
            if ($messages->has('is_default')) {
                $this->errors->add('is_default', $messages->first('is_default'));
            }
        }
    }
    
    public function del(){
        $this->validadordel();
        if($this->passes()){
            $ret = DB::table('competence')->where('id',$this->input['id'])->delete();
            if(!$ret){
                $this->status = 400;
                $this->description = '参数错误';
                $this->accepted = false;
                $this->errors->add('id','删除失败');
            }
        }
    }


    protected function validadordel(){
        $rules = [
            'id'=>"required|integer|unique:competence,pid",

        ];
        $messages = [
            'id.required'=>'id不能为空',
            'id.integer'=>'id数据类型不正确',
            'id.unique'=>'请先删除子权限，再删除本权限',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('id')) {
                $this->errors->add('id', $messages->first('id'));
            }
        }
    }
}
