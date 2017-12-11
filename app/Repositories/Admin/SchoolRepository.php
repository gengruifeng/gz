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


class SchoolRepository extends Repository implements RepositoryInterface
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
        $db = DB::table('cv_school')->select('name','created_at','order','id','cityid');

        if(!empty($this->input['name'])){
            $db->where('name','like','%'.trim($this->input['name']).'%');
        }

        if(!empty($this->input['cityid'])){
            $db->where('cityid',$this->input['cityid']);
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
                $city = DB::table('province_city')->where('id', $v->cityid)->first();
                if(!empty($city->areaname)){
                    $ret[$k]->cityname = $city->areaname;
                }else{
                    $ret[$k]->cityname = '';
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
            'name' => "required|unique:cv_school,name",
            'cityid'=>"required|integer|exists:province_city,id",
            'order'=>"integer",
        ];
        $messages = [
            'name.required' => '名称不能为空',
            'name.unique' => '名称已经存在',
            'cityid.required' => '城市不能为空',
            'cityid.integer' => '城市数据类型不正确',
            'cityid.exists' => '城市数据不正确',
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
            if ($messages->has('cityid')) {
                $this->errors->add('cityid', $messages->first('cityid'));
            }
            if ($messages->has('order')) {
                $this->errors->add('order', $messages->first('order'));
            }
        }
    }

    protected function doadd(){
        $insert = [
            'name' =>$this->input['name'],
            'cityid'  => $this->input['cityid'],
            'order' => !empty($this->input['order'])?$this->input['order']:0,
            'created_at' => date('Y-m-d H:i:s',time()),

        ];
        $ret = DB::table('cv_school')->insertGetId($insert);
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
            'id'=>"required|integer|exists:cv_school,id",

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

        $ret = DB::table('cv_school')->where('id',$this->input['id'])->delete();

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
            'name' => "required|unique:cv_school,name,{$this->input['id']}",
            'cityid'=>"integer|exists:province_city,id",
            'order'=>"integer",
        ];
        $messages = [
            'name.required' => '名称不能为空',
            'name.unique' => '名称已经存在',
            'cityid.integer' => '分类数据类型不正确',
            'cityid.exists' => '分类数据不正确',
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
            if ($messages->has('cityid')) {
                $this->errors->add('cityid', $messages->first('cityid'));
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
            'cityid' => !empty($this->input['cityid'])?$this->input['cityid']:0,
            'updated_at' => date('Y-m-d H:i:s',time()),
        ];
        DB::table('cv_school')->where('id',$this->input['id'])->update($uodate);
    }

}
