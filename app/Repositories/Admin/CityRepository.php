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


class CityRepository extends Repository implements RepositoryInterface
{

    public $dofunction;

    public $doreturn;

    public $currenpPge = 1;

    public $num = 10;

    public $order = 'sort';

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
        $this->order = !empty($this->input['order'])?$this->input['order']:'sort';
        $this->orderBy = !empty($this->input['orderBy'])?$this->input['orderBy']:'DESC';
    }

    /**
     * 获取数据源
     * @param $istotal 1为获取数据源的总条数0为是数据源
     * @return mixed
     */
    protected function _where($istotal){
        $db = DB::table('province_city')->select('areaname','created_at','level','sort','id','parentid');

        if(!empty($this->input['areaname'])){
            $db->where('areaname','like','%'.trim($this->input['areaname']).'%');
        }

        if(!empty($this->input['parentid']) && empty($this->input['parentidtwo'])){
            if($this->input['parentid'] == -1){
                $db->where('parentid','0');

            }else{
                $parentid = DB::table('province_city')->select('id')->where('parentid',$this->input['parentid'])->get();
                $wherein=[];
                foreach ($parentid as $k=>$v){
                    $wherein[] = $v->id;
                }
                $db->wherein('parentid',$wherein);
            }

        }

        if(!empty($this->input['parentidtwo']) && $this->input['parentidtwo'] == -1){
            $db->where('parentid',$this->input['parentid']);
        }elseif(!empty($this->input['parentidtwo'])){
            $db->where('parentid',$this->input['parentidtwo']);

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
            foreach ($ret as $k=>$v) {
                if($v->level == 1){
                    $ret[$k]->type = '一级分类';
                    $ret[$k]->parentidtwo = 0;
                }elseif ($v->level == 2){
                    $ret[$k]->parentidtwo = 0;
                    $ret[$k]->type = '二级分类';
                }elseif ($v->level == 3){
                    $root = DB::table('province_city')->select('parentid','id')->where('id', $v->parentid)->first();
                    $ret[$k]->parentidtwo = $root->id;
                    $ret[$k]->parentid = $root->parentid;
                    $ret[$k]->type = '三级分类';
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
            'areaname' => "required|unique:province_city,areaname",
            'parentid'=>"integer",
            'parentidtwo'=>"integer",
            'sort'=>"integer",
        ];
        $messages = [
            'areaname.required' => '名称不能为空',
            'areaname.unique' => '名称已经存在',
            'parentid.integer' => '一级分类数据类型不正确',
            'parentidtwo.integer' => '二级分类数据类型不正确',
            'sort.integer' => '排序数据为整数',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('areaname')) {
                $this->errors->add('areaname', $messages->first('areaname'));
            }
            if ($messages->has('parentid')) {
                $this->errors->add('parentid', $messages->first('parentid'));
            }
            if ($messages->has('parentidtwo')) {
                $this->errors->add('parentidtwo', $messages->first('parentidtwo'));
            }
            if ($messages->has('sort')) {
                $this->errors->add('sort', $messages->first('sort'));
            }
        }
    }

    protected function doadd(){

        if(!empty($this->input['parentidtwo'])){
            $parentid = $this->input['parentidtwo'];
        }elseif(!empty($this->input['parentid'])){
            $parentid = $this->input['parentid'];
        }else{
            $parentid = 0;
        }

        if(isset($this->input['parentidtwo']) && $this->input['parentidtwo'] == 0){
            $level = 2;
        }elseif(!empty($this->input['parentidtwo'])){
            $level = 3;
        }elseif($this->input['parentid'] == 0){
            $level = 1;
        }else{
            $level = 0;
        }

        $insert = [
            'areaname' =>$this->input['areaname'],
            'shortname' =>$this->input['areaname'],
            'parentid'  => $parentid,
            'level'  => $level,
            'sort' => !empty($this->input['sort'])?$this->input['sort']:0,
            'created_at' => date('Y-m-d H:i:s',time()),

        ];
        $ret = DB::table('province_city')->insertGetId($insert);
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
            'id'=>"required|integer|exists:province_city,id|unique:province_city,parentid",

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

        $ret = DB::table('province_city')->where('id',$this->input['id'])->delete();

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
            'areaname' => "required|unique:province_city,areaname,{$this->input['id']}",
            'parentid'=>"integer",
            'parentidtwo'=>"integer",
            'sort'=>"integer",
        ];
        $messages = [
            'areaname.required' => '名称不能为空',
            'areaname.unique' => '名称已经存在',
            'parentid.integer' => '一级分类数据类型不正确',
            'parentidtwo.integer' => '二级分类数据类型不正确',
            'sort.integer' => '排序数据为整数',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('areaname')) {
                $this->errors->add('areaname', $messages->first('areaname'));
            }
            if ($messages->has('parentid')) {
                $this->errors->add('parentid', $messages->first('parentid'));
            }
            if ($messages->has('parentidtwo')) {
                $this->errors->add('parentidtwo', $messages->first('parentidtwo'));
            }
            if ($messages->has('sort')) {
                $this->errors->add('sort', $messages->first('sort'));
            }
        }
    }

    protected function doedit(){

        $uodate = [
            'areaname' =>$this->input['areaname'],
            'shortname' =>$this->input['areaname'],
            'sort' => !empty($this->input['sort'])?$this->input['sort']:0,
            'updated_at' => date('Y-m-d H:i:s',time()),
        ];
        DB::table('province_city')->where('id',$this->input['id'])->update($uodate);
    }

    /**
     * 获取规则的职位数组
     */
    public function getpareaname(){
        $data = [];
        $root = DB::table('province_city')->where('parentid', 0)->orderby('sort','desc')->orderBy('id', 'desc')->get();
        if(!empty($root)){
            foreach ($root as $k => $v){
                $data[$v->id]['id'] = $v->id;
                $data[$v->id]['areaname'] = $v->areaname;

                $sub = DB::table('province_city')->where('parentid', $v->id)->orderby('sort','desc')->orderBy('id', 'desc')->get();
                if(!empty($sub)){
                    foreach ($sub as $kk => $vv){
                        $subareaname = trim($vv->areaname);
                        $data[$v->id]['sub'][$vv->id] = [
                            'id' => $vv->id,
                            'areaname' => $subareaname,
                        ];
                    }

                }
            }
        }
        return $data;
    }

}
