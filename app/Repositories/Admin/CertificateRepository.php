<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/1
 * Time: 10:46
 */

namespace App\Repositories\Admin;

use App\Entity\UserProficiencies;
use App\Utils\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Utils\HttpStatus;
use App\Repositories\Repository;
use App\Repositories\RepositoryInterface;
use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;
use Log;


class CertificateRepository extends Repository implements RepositoryInterface
{

    public $dofunction;

    public $doreturn;

    public $currenpPge = 1;

    public $num = 10;

    public $order = 'order';

    public $orderBy = 'DESC';

    public $data = [];

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
     *获取用户列表
     */
    public function getList(){
        //初始化列表
        $this->_initPram();

        $ret = $this->_where(0);
        if(!empty($ret)){
            $totalret = $this->_where(1);
            $this->ininttet($ret);
            $this->data = [
                'status' =>1,
                'currenpPge' =>$this->currenpPge,
                'totalPage' =>ceil($totalret/$this->num),
                'total' =>$totalret,
                'next' =>$this->currenpPge +1,
                'up' =>$this->currenpPge - 1,
                'data' =>$ret,
            ];
        }else{
            $this->data = [
                'status' =>0,
                'currenpPge' =>$this->currenpPge,
                'totalPage' =>0,
                'total' =>0,
                'data' =>$ret,
            ];
        }
    }

    /**
     * 初始化列表参数
     */
    protected function _initPram(){
        $this->currenpPge = !empty($this->input['currenpPge'])?$this->input['currenpPge']:1;
        $this->num = !empty($this->input['num'])?$this->input['num']:10;
        $this->order = !empty($this->input['order'])?$this->input['order']:'order';
        $this->orderBy = !empty($this->input['orderBy'])?$this->input['orderBy']:'DESC';
    }

    /**
     * 获取数据源
     * @param $istotal 1为获取数据源的总条数0为是数据源
     * @return mixed
     */
    protected function _where($istotal){
        $db = DB::table('cv_certificates')->select('name','created_at','order','id','pid');

        if(!empty($this->input['name'])){
            $db->where('name','like','%'.trim($this->input['name']).'%');
        }

        if(!empty($this->input['pid'])){
            if($this->input['pid'] == -1){
                $db->where('pid','0');

            }else{
                $db->where('pid',$this->input['pid']);
            }

        }

        if($istotal){
            $ret = $db->count();
        }else{
            $ret = $db->orderBy($this->order, $this->orderBy)->orderBy('id', 'desc')->skip(($this->currenpPge - 1) * $this->num)->take($this->num)->get();
        }
        return $ret;
    }

    /*
     * 初始化结果
     */
    protected function ininttet(&$ret){
        if(!empty($ret)){
            foreach ($ret as $k=>$v){
                if($v->pid == 0){
                    $ret[$k]->type = '分类';
                }else{
                    $ret[$k]->type = '证书';
                }
            }
        }
    }

    /**
     * 添加
     */
    protected function add(){
        $this->validadoradd();
        if($this->passes()){
            $this->doadd();
        }
    }

    /**
     * 验证添加数据
     */
    protected function validadoradd()
    {
        $rules = [
            'name' => "required|unique:cv_certificates,name",
            'pid'=>"integer",
            'order'=>"integer",
        ];
        $messages = [
            'name.required' => '名称不能为空',
            'name.unique' => '名称已经存在',
            'pid.integer' => '分类数据类型不正确',
            'order.integer' => '排序数据为整数',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('name')) {
                $this->errors->add('name', $messages->first('name'));
            }
            if ($messages->has('pid')) {
                $this->errors->add('pid', $messages->first('pid'));
            }
            if ($messages->has('order')) {
                $this->errors->add('order', $messages->first('order'));
            }
        }
    }

    protected function doadd(){
        $insert = [
            'name' =>$this->input['name'],
            'pid'  => $this->input['pid'],
            'order' => !empty($this->input['order'])?$this->input['order']:0,
            'created_at' => date('Y-m-d H:i:s',time()),

        ];
        $ret = DB::table('cv_certificates')->insertGetId($insert);
        if(!$ret ) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id', '添加失败');
        }
        $this->input['id'] = $ret;
    }

    /**
     * 删除
     */
    protected function del(){

        $this->validadordel();
        if($this->passes()){
            $this->dodel();
        }
    }

    protected function validadordel(){
        $rules = [
            'id'=>"required|integer|exists:cv_certificates,id|unique:cv_certificates,pid",

        ];
        $messages = [
            'id.required'=>'id不能为空',
            'id.integer'=>'id数据类型不正确',
            'id.exists'=>'未知id数据',
            'id.unique'=>'该数据下有子分类，请处理后再删除。',
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

    protected function dodel(){

        $ret = DB::table('cv_certificates')->where('id',$this->input['id'])->delete();

        if(!$ret){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','删除失败');
        }
    }

    protected function edit(){
        $this->validadoredit();
        if($this->passes()){
            $this->doedit();
        }
    }


    protected function validadoredit(){
        $rules = [
            'name' => "required|unique:cv_certificates,name,{$this->input['id']}",
            'pid'=>"integer",
            'order'=>"integer",
        ];
        $messages = [
            'name.required' => '名称不能为空',
            'name.unique' => '名称已经存在',
            'pid.integer' => '分类数据类型不正确',
            'order.integer' => '排序数据为整数',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('name')) {
                $this->errors->add('name', $messages->first('name'));
            }
            if ($messages->has('pid')) {
                $this->errors->add('pid', $messages->first('pid'));
            }
            if ($messages->has('order')) {
                $this->errors->add('order', $messages->first('order'));
            }
        }
    }

    protected function doedit(){

        $uodate = [
            'name' =>$this->input['name'],
            'order' => !empty($this->input['order'])?$this->input['order']:0,
            'updated_at' => date('Y-m-d H:i:s',time()),
        ];
        DB::table('cv_certificates')->where('id',$this->input['id'])->update($uodate);
    }

    /**
     * 获取规则的职位数组
     */
    public function getpname(){
        $data = [];
        $root = DB::table('cv_certificates')->where('pid', 0)->orderby('order','desc')->get();
        if(!empty($root)){
            foreach ($root as $k => $v){
                $data[$v->id]['id'] = $v->id;
                $data[$v->id]['name'] = $v->name;

                $sub = DB::table('cv_certificates')->where('pid', $v->id)->orderby('order','desc')->get();
                if(!empty($sub)){
                    foreach ($sub as $kk => $vv){
                        $subname = trim($vv->name);
                        $data[$v->id]['sub'][$vv->id] = [
                            'id' => $vv->id,
                            'name' => $subname,
                        ];
                    }

                }
            }
        }
        return $data;
    }

}
