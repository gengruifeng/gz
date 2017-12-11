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


class TemplateRepository extends Repository implements RepositoryInterface
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
        $db = DB::table('cv_templates')->select('subject','downloaded','created_at','id');

        if($this->input['profession_id'] != -1){
            $db->where('profession_id',$this->input['profession_id']);
        }

        if($this->input['colorscheme'] !=-1){
            $db->where('colorscheme',$this->input['colorscheme']);
        }

        if($this->input['language'] != -1){
            $db->where('language',$this->input['language']);
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
            if($this->passes()){
                $this->addsphinx();   
            }
        }
    }

    public function addsphinx(){
        $id = $this->input['id'];
        $name =  $this->input['subject'];
        $ret = SphinxQL::create($this->connection)->query("select * from cv_templates where id=".$id)->execute();
        if(empty($ret)){
            SphinxQL::create($this->connection)->query("insert into  cv_templates (id, subject) values ({$id},'".$name."')")->execute();
        }else{
            $sq = SphinxQL::create($this->connection)->replace()->into('cv_templates');
            $sq->value('id', $id)->value('subject', $name);
            $sq->execute();
        }
    }

    /**
     * 验证添加数据
     */
    protected function validadoradd()
    {
        $rules = [
            'preview' => "required",
            'file' => "required",
            'subject' => "required|unique:cv_templates,subject",
            'profession_id' => "required|exists:cv_professions,id",
            'language' => "required|in:zh-cn,en-us",
            'colorscheme' => "required|in:0,1",
            'feature' => "required",

        ];
        $messages = [
            'preview.required' => '预览图不能为空',
            'file.required' => '模板文件不能为空',
            'subject.required' => '名称不能为空',
            'subject.unique' => '名称已经存在',
            'profession_id.required' => '求职意向不能为空',
            'profession_id.exists' => '求职意向数据不正确',
            'language.required' => '语言不能为空',
            'language.in' => '语言数据不正确',
            'colorscheme.required' => '颜色主题数据不能为空',
            'colorscheme.in' => '颜色主题数据格式不正确',
            'feature.required' => '特点不能为空',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();

            if ($messages->has('preview')) {
                $this->errors->add('preview', $messages->first('preview'));
            }
            if ($messages->has('file')) {
                $this->errors->add('file', $messages->first('file'));
            }
            if ($messages->has('subject')) {
                $this->errors->add('subject', $messages->first('subject'));
            }
            if ($messages->has('profession_id')) {
                $this->errors->add('profession_id', $messages->first('profession_id'));
            }
            if ($messages->has('language')) {
                $this->errors->add('language', $messages->first('language'));
            }
            if ($messages->has('colorscheme')) {
                $this->errors->add('colorscheme', $messages->first('colorscheme'));
            }
            if ($messages->has('feature')) {
                $this->errors->add('feature', $messages->first('feature'));
            }
        }
    }

    protected function doadd(){
        $ret = DB::table('cv_templates')->insertGetId([
            'profession_id' => $this->input['profession_id'],
            'subject' => $this->input['subject'],
            'feature' => $this->input['feature'],
            'preview' => $this->input['preview'],
            'file' => $this->input['file'],
            'language' => $this->input['language'],
            'colorscheme' => $this->input['colorscheme'],
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
            'id'=>"required|integer|exists:cv_templates,id",

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

        $ret = DB::table('cv_templates')->where('id',$this->input['id'])->delete();

        if(!$ret){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','删除失败');
        }
    }
    
    public function delsphinx(){
        SphinxQL::create($this->connection)->query('delete from cv_templates where id = '.$this->input['id'])->execute();
    }


    
    public function getQuestionsTags($id){
        $data = [];
        $ret = DB::table('question_tags')->where('question_id',$id)->get();
        if(!empty($ret)){
            foreach ($ret as $k=>$v){
                $data[$v->tag_id] = $v->question_id;
            }
        }
        return $data;
    }

    protected function edit(){
        $this->validadoredit();
        if($this->passes()){
            $this->doedit();
            $this->editsphinx();
        }
    }


    protected function validadoredit(){
        $rules = [
            'id' => 'required',
            'preview' => "required",
            'file' => "required",
            'subject' => "required|unique:cv_templates,subject,{$this->input['id']}",
            'profession_id' => "required|exists:cv_professions,id",
            'language' => "required|in:zh-cn,en-us",
            'colorscheme' => "required|in:0,1",
            'feature' => "required",

        ];
        $messages = [
            'id.required' => 'id不能为空',
            'preview.required' => '预览图不能为空',
            'file.required' => '模板文件不能为空',
            'subject.required' => '名称不能为空',
            'subject.unique' => '名称已经存在',
            'profession_id.required' => '求职意向不能为空',
            'profession_id.exists' => '求职意向数据不正确',
            'language.required' => '语言不能为空',
            'language.in' => '语言数据不正确',
            'colorscheme.required' => '颜色主题数据不能为空',
            'colorscheme.in' => '颜色主题数据格式不正确',
            'feature.required' => '特点不能为空',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();

            if ($messages->has('preview')) {
                $this->errors->add('preview', $messages->first('preview'));
            }
            if ($messages->has('file')) {
                $this->errors->add('file', $messages->first('file'));
            }
            if ($messages->has('subject')) {
                $this->errors->add('subject', $messages->first('subject'));
            }
            if ($messages->has('profession_id')) {
                $this->errors->add('profession_id', $messages->first('profession_id'));
            }
            if ($messages->has('language')) {
                $this->errors->add('language', $messages->first('language'));
            }
            if ($messages->has('colorscheme')) {
                $this->errors->add('colorscheme', $messages->first('colorscheme'));
            }
            if ($messages->has('feature')) {
                $this->errors->add('feature', $messages->first('feature'));
            }
        }
    }

    protected function doedit(){
        DB::table('cv_templates')->where('id',$this->input['id'])->update([
            'profession_id' => $this->input['profession_id'],
            'subject' => $this->input['subject'],
            'feature' => $this->input['feature'],
            'preview' => $this->input['preview'],
            'file' => $this->input['file'],
            'language' => $this->input['language'],
            'colorscheme' => $this->input['colorscheme'],
            'updated_at' => date('Y-m-d H:i:s',time()),
        ]);
    }

    public function editsphinx(){
        $id = $this->input['id'];
        $subject =  $this->input['subject'];
        $ret = SphinxQL::create($this->connection)->query("select * from cv_templates where id=".$id)->execute();
        if(!empty($ret)){
            $sq = SphinxQL::create($this->connection)->replace()->into('cv_templates');
            $sq->value('id', $id)->value('subject', $subject);
            $sq->execute();
        }else{
           SphinxQL::create($this->connection)->query("insert into cv_templates values ({$id},'".$subject."')")->execute();

        }
    }


}
