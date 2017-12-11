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


class MajorRepository extends Repository implements RepositoryInterface
{

    public $dofunction;

    public $doreturn;

    public $currenpPge = 1;

    public $num = 10;

    public $order = 'created_at';

    public $orderBy = 'DESC';

    public $data = [];

    private $connection;

    public function contract()
    {
        $this->connection = new Connection();
        $this->connection->setParams(['host' => env('SPHINX_HOST'), 'port' => env('SPHINX_PORT')]);
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
        $this->order = !empty($this->input['order'])?$this->input['order']:'created_at';
        $this->orderBy = !empty($this->input['orderBy'])?$this->input['orderBy']:'DESC';
    }

    /**
     * 获取数据源
     * @param $istotal 1为获取数据源的总条数0为是数据源
     * @return mixed
     */
    protected function _where($istotal){
        $db = DB::table('cv_majors')->select('name','created_at','id');

        if(!empty($this->input['name'])){
            $db->where('name','like','%'.$this->input['name'].'%');
        }

        if($istotal){
            $ret = $db->count();
        }else{
            $ret = $db->orderBy($this->order, $this->orderBy)->skip(($this->currenpPge - 1) * $this->num)->take($this->num)->get();
        }
        return $ret;
    }

    /**
     * 添加
     */
    protected function add(){
        $this->validadoradd();
        if($this->passes()){
            $this->doadd();
            $this->addsphinx();
        }
    }

    /**
     * 验证添加数据
     */
    protected function validadoradd()
    {
        $rules = [
            'name' => "required|unique:cv_majors,name",
        ];
        $messages = [
            'name.required' => '名称不能为空',
            'name.unique' => '名称已经存在',
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
        }
    }

    protected function doadd(){
        $ret = DB::table('cv_majors')->insertGetId([
            'name' => $this->input['name'],
            'created_at' => date('Y-m-d H:i:s',time()),
        ]);
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
            $this->delsphinx();
        }
    }

    protected function validadordel(){
        $rules = [
            'id'=>"required|integer|exists:cv_majors,id",

        ];
        $messages = [
            'id.required'=>'id不能为空',
            'id.integer'=>'id数据类型不正确',
            'id.exists'=>'未知id数据',
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

        $ret = DB::table('cv_majors')->where('id',$this->input['id'])->delete();

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
            $this->editsphinx();
        }
    }
    public function addsphinx(){
        $id = $this->input['id'];
        $name =  $this->input['name'];
        $ret = SphinxQL::create($this->connection)->query("select * from cv_majors where id=".$id)->execute();
        if(!empty($ret)){
            $sq = SphinxQL::create($this->connection)->replace()->into('cv_majors');
            $sq->value('id', $id)->value('name', $name);
            $sq->execute();
        }else{
            SphinxQL::create($this->connection)->query("insert into cv_majors values ({$id},'".$name."')")->execute();
        }
    }

    public function editsphinx(){
        $id = $this->input['id'];
        $name =  $this->input['name'];
        $ret = SphinxQL::create($this->connection)->query("select * from cv_majors where id=".$id)->execute();
        if(!empty($ret)){
            $sq = SphinxQL::create($this->connection)->replace()->into('cv_majors');
            $sq->value('id', $id)->value('name', $name);
            $sq->execute();
        }else{
            SphinxQL::create($this->connection)->query("insert into cv_majors values ({$id},'".$name."')")->execute();

        }
    }
    public function delsphinx(){
        SphinxQL::create($this->connection)->query('delete from questions where id = '.$this->input['id'])->execute();
    }

    protected function validadoredit(){
        $rules = [
            'id'=>"required|integer|exists:cv_majors,id",
            'name' => "required|unique:cv_majors,name,{$this->input['id']}",
        ];
        $messages = [
            'name.required' => 'id不能为空',
            'name.unique' => '名称已经存在',
            'id.required'=>'id不能为空',
            'id.integer'=>'id数据类型不正确',
            'id.exists'=>'未知id数据',
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
            if ($messages->has('id')) {
                $this->errors->add('id', $messages->first('id'));
            }
        }
    }

    protected function doedit(){
        DB::table('cv_majors')->where('id',$this->input['id'])->update([
            'name' => $this->input['name'],
            'updated_at' => date('Y-m-d H:i:s',time()),
        ]);
    }

}
