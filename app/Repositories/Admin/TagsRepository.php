<?php

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


class TagsRepository extends Repository implements RepositoryInterface
{

    public $dofunction;

    public $doreturn;

    public $currenpPge = 1;

    public $num = 10;

    public $order = 'created_at';

    public $orderBy = 'DESC';

    public $isDisabled = 0;

    public $data = [];

    public $standard = [
        1 => '编辑',
        2 => '添加',
        3 => '删除',
        4 => '添加领域',
        5 => '删除领域',
    ];

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
             $this->_initData($ret);
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

    protected function _initPram(){
        $this->currenpPge = !empty($this->input['currenpPge'])?$this->input['currenpPge']:1;
        $this->num = !empty($this->input['num'])?$this->input['num']:10;
        $this->order = !empty($this->input['order'])?$this->input['order']:'created_at';
        $this->orderBy = !empty($this->input['orderBy'])?$this->input['orderBy']:'DESC';
    }

    protected function _where($istotal){
        $db = DB::table('tags')->select('users.display_name','tags.id','tags.name','tags.tagged_answers','tags.tagged_articles','tags.created_at','tags.uid');

        $db->join('users', 'tags.uid', '=', 'users.id');

        if(!empty($this->input['display_name'])){
            $db->where('users.display_name','like','%'.$this->input['display_name'].'%');
        }

        if(!empty($this->input['name'])){
            $db->where('tags.name','like','%'.$this->input['name'].'%');
        }

        if($istotal){
            $ret = $db->count();
        }else{
            $ret = $db->orderBy($this->order, $this->orderBy)->skip(($this->currenpPge - 1) * $this->num)->take($this->num)->get();
        }

        return $ret;
    }

    protected function _initData(&$ret){
        foreach ($ret as $k=>$v){
            $html = '';
            $res = DB::table('tags_history')->select('tags_history.*','users.display_name')->join('users', 'tags_history.adminid', '=', 'users.id')->where('tags_history.tag_id',$v->id)->get();
            if(!empty($res)){
                foreach ($res as $kk=>$vv){
                    $reson = '';
                    if($vv->type == 4){
                        $name = DB::table('categories')->select('entity')->where('id',$vv->category_id)->first();
                        $reson = '<br />添加领域到：'.(!empty($name->entity)?$name->entity:'');
                    }elseif ($vv->type == 5){
                        $name = DB::table('categories')->select('entity')->where('id',$vv->category_id)->first();
                        $reson = '<br />删除领域：'.(!empty($name->entity)?$name->entity:'');
                    }
                    $html .= '<p>'.$vv->display_name.'|'.($this->standard[$vv->type]).'|'.$vv->created_at.$reson.'</p><hr />';
                }
            }else{
                $html .= '<p> 无操作记录</p>';
            }
            $ret[$k]->caozuo = $html;
        }
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
            'id' => 'required|integer|exists:tags,id',
            'name' => "required|unique:tags,name,{$this->input['id']}",
        ];
        $messages = [
            'name.required'=>'标签不能为空',
            'name.unique'=>'标签已经存在',
            'id.required'=>'id不能为空',
            'id.integer'=>'id数据格式不正确',
            'id.exists'=>'id数据不正确',
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

    protected function add(){
        $this->validadoradd();
        if($this->passes()){
            $this->doadd();
            $this->addsphinx();
        }
    }


    protected function validadoradd(){

        $rules = [
            'name' => "required|unique:tags,name",
        ];
        $messages = [
            'name.required'=>'标签不能为空',
            'name.unique'=>'标签已经存在',
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

    protected function doedit(){
        DB::beginTransaction();

        $ret1 = DB::table('tags')->where('id',$this->input['id'])->update([
            'name' => $this->input['name'],
            'updated_at' => date('Y-m-d H:i:s',time()),
        ]);
        $ret2 = DB::table('tags_history')->insert([
            'tag_id' => $this->input['id'],
            'type' => 1,
            'adminid' =>$this->input['adminid'],
            'created_at' =>date('Y-m-d H:i:s',time()),
        ]);

        if(!$ret1 || !$ret2 ){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','保存失败');
            DB::rollBack();
            \Log::error('[admin]edit tag, Failed to update the database - adminid:'.$this->input['adminid']);
        }else{
            DB::commit();
            \Log::info('[admin]edit tag, tgaid is '.$this->input['id'].' - adminid:'.$this->input['adminid']);
        }
    }

    protected function doadd(){
        DB::beginTransaction();

        $ret1 = DB::table('tags')->insertGetId([
            'name' => $this->input['name'],
            'uid' =>$this->input['adminid'],
            'created_at' => date('Y-m-d H:i:s',time()),
        ]);
        $this->input['id'] = $ret1;
        $ret2 = DB::table('tags_history')->insert([
            'tag_id' => $ret1,
            'type' => 2,
            'adminid' =>$this->input['adminid'],
            'created_at' =>date('Y-m-d H:i:s',time()),
        ]);

        if(!$ret1 || !$ret2 ){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','保存失败');
            DB::rollBack();
            \Log::error('[admin]add tag, Failed to update the database - adminid:'.$this->input['adminid']);
        }else{
            \Log::info('[admin]add tag, tgaid is '.$ret1.' - adminid:'.$this->input['adminid']);
            DB::commit();
        }
    }

    protected function del(){
        $this->validadordel();
        if($this->passes()){
            $this->dodel();
            $this->delsphinx();
        }
    }


    protected function validadordel(){

        $rules = [
            'id' => 'required|integer|exists:tags,id',
        ];
        $messages = [
            'id.required'=>'id不能为空',
            'id.integer'=>'id数据格式不正确',
            'id.exists'=>'id数据不正确',
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
        DB::beginTransaction();

        $is = DB::table('categories_tags')->where('tag_id', $this->input['id'])->count();
        $ret1 = true;
        if($is){
            $ret1 = DB::table('categories_tags')->where('tag_id', $this->input['id'])->delete();
        }
        $is = DB::table('article_tags')->where('tag_id', $this->input['id'])->count();
        $ret2 = true;
        if($is){
            $ret2 = DB::table('article_tags')->where('tag_id', $this->input['id'])->delete();
        }

        $is = DB::table('question_tags')->where('tag_id', $this->input['id'])->count();
        $ret3 = true;
        if($is){
            $ret3 = DB::table('question_tags')->where('tag_id', $this->input['id'])->delete();
        }

        $is = DB::table('user_proficiencies')->where('tag_id', $this->input['id'])->count();
        $ret4 = true;
        if($is){
            $ret4 = DB::table('user_proficiencies')->where('tag_id', $this->input['id'])->delete();
        }

        $ret5 = DB::table('tags')->where('id', $this->input['id'])->delete();

        $ret6 = DB::table('tags_history')->insert([
            'tag_id' => $this->input['id'],
            'type' => 3,
            'adminid' =>$this->input['adminid'],
            'created_at' =>date('Y-m-d H:i:s',time()),
        ]);

        if(!$ret1 || !$ret2 || !$ret3 || !$ret4 || !$ret5 || !$ret6){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','删除失败');
            DB::rollBack();
            \Log::error('[admin]del tag, Failed to update the database - adminid:'.$this->input['adminid']);
        }else{
            \Log::info('[admin]del tag, tgaid is '.$this->input['id'].' - adminid:'.$this->input['adminid']);
            DB::commit();
        }
    }


    protected function addCategories(){
        $this->validadoraddCategories();
        if($this->passes()){
            $this->doaddCategories();
        }
    }


    protected function validadoraddCategories(){

        $rules = [
            'tag_id' => "required|integer|exists:tags,id",
            'category_id' => "required|integer|exists:categories,id",
        ];
        $messages = [
            'tag_id.required'=>'标签id不能为空',
            'tag_id.integer'=>'标签数据格式不正确',
            'tag_id.exists'=>'标签id数据不正确',
            'category_id.required'=>'擅长领域id不能为空确',
            'category_id.integer'=>'擅长领域id据格式不正确',
            'category_id.exists'=>'擅长领域id数据不正确',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('tag_id')) {
                $this->errors->add('tag_id', $messages->first('tag_id'));
            }
            if ($messages->has('category_id')) {
                $this->errors->add('category_id', $messages->first('category_id'));
            }

        }
    }

    protected function doaddCategories(){
        DB::beginTransaction();

        $ret1 = DB::table('categories_tags')->insert([
            'tag_id' => $this->input['tag_id'],
            'category_id' => $this->input['category_id'],
            'created_at' => date('Y-m-d H:i:s',time()),
        ]);
        $ret2 = DB::table('tags_history')->insert([
            'tag_id' => $this->input['tag_id'],
            'category_id' => $this->input['category_id'],
            'type' => 4,
            'adminid' =>$this->input['adminid'],
            'created_at' =>date('Y-m-d H:i:s',time()),
        ]);

        if(!$ret1 || !$ret2 ){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','保存失败');
            DB::rollBack();
        }else{
            DB::commit();
        }
    }

    protected function delCategories(){
        $this->validadoraddCategories();
        if($this->passes()){
            $this->dodelCategories();
        }
    }


    protected function dodelCategories(){
        DB::beginTransaction();
        $ret1 = DB::table('categories_tags')->where('tag_id',$this->input['tag_id'])->where('category_id',$this->input['category_id'])->delete();
        $ret2 = DB::table('tags_history')->insert([
            'tag_id' => $this->input['tag_id'],
            'category_id' => $this->input['category_id'],
            'type' => 5,
            'adminid' =>$this->input['adminid'],
            'created_at' =>date('Y-m-d H:i:s',time()),
        ]);
        if(!$ret1 || !$ret2 ){
            $this->status = 500;
            $this->description = '发生一个内部错误';
            $this->accepted = false;
            $this->errors->add('id','保存失败');
            DB::rollBack();
        }else{
            DB::commit();
        }
    }


    public function getCategoriesTag(){
        $data = [];
        $ret =  DB::table('categories_tags')->get();
        if(!empty($ret)){
            foreach ($ret as $k=>$v){
                $data[$v->category_id][$v->tag_id] = $v->id;
            }
        }

        return $data;
    }

    
    public function delsphinx(){
        SphinxQL::create($this->connection)->query('delete from tags where id = '.$this->input['id'])->execute();
    }

    public function addsphinx(){
        $id = $this->input['id'];
        $name =  $this->input['name'];
        $ret = SphinxQL::create($this->connection)->query("select * from tags where id=".$id)->execute();
        if(empty($ret)){
            SphinxQL::create($this->connection)->query("insert into tags (id,name) values ({$id},'".addslashes($name)."')")->execute();
        }else{
            $sq = SphinxQL::create($this->connection)->replace()->into('tags');
            $sq->value('id', $id)->value('name', addslashes($name));
            $sq->execute();
        }
    }

    public function editsphinx(){
        $id = $this->input['id'];
        $name =  $this->input['name'];
        $ret = SphinxQL::create($this->connection)->query("select * from tags where id=".$id)->execute();

        if(!empty($ret)){
            $sq = SphinxQL::create($this->connection)->replace()->into('tags');
            $sq->value('id', $id)->value('name', addslashes($name));
            $sq->execute();
        }else{
            SphinxQL::create($this->connection)->query("insert into tags (id,name) values ({$id},'".addslashes($name)."')")->execute();
        }
    }


    /**
     * 添加擅长领域
     */
    protected function addCategoriesInfo(){
        $this->validadoraddCategoriesInfo();
        if($this->passes()){
            $this->doaddCategoriesInfo();
        }
    }


    protected function validadoraddCategoriesInfo(){

        $rules = [
            'entity' => "required|unique:categories,entity",
            'order' => "integer",
            'categoryurl' => "required",
            'categoryurlhide' => "required",
        ];
        $messages = [
            'entity.required'=>'领域不能为空',
            'entity.unique'=>'领域已经存在',
            'categoryurl.required'=>'图标(选中前)不能为空',
            'categoryurlhide.required'=>'图标(选中后)不能为空',
            'order.integer'=>'排序为整数数字',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('entity')) {
                $this->errors->add('entity', $messages->first('entity'));
            }
            if ($messages->has('order')) {
                $this->errors->add('order', $messages->first('order'));
            }
            if ($messages->has('categoryurl')) {
                $this->errors->add('categoryurl', $messages->first('categoryurl'));
            }
            if ($messages->has('categoryurlhide')) {
                $this->errors->add('categoryurlhide', $messages->first('categoryurlhide'));
            }
        }
    }

    protected function doaddCategoriesInfo(){

        $ret1 = DB::table('categories')->insertGetId([
            'entity' => $this->input['entity'],
            'order' =>$this->input['order'],
            'pic' => $this->input['categoryurl'],
            'pic_hide' => $this->input['categoryurlhide'],
            'created_at' => date('Y-m-d H:i:s',time()),
        ]);

        if(!$ret1){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','保存失败');
            DB::rollBack();
        }
    }



    /**
     * 添加擅长领域
     */
    protected function editCategoriesInfo(){
        $this->validadoreditCategoriesInfo();
        if($this->passes()){
            $this->doeditCategoriesInfo();
        }
    }


    protected function validadoreditCategoriesInfo(){

        $rules = [
            'id' => 'required|integer|exists:categories,id',
            'entity' => "required|unique:categories,entity,{$this->input['id']}",
            'order' => "integer",
            'categoryurl' => "required",
            'categoryurlhide' => "required",
        ];
        $messages = [
            'entity.required'=>'领域不能为空',
            'entity.unique'=>'领域已经存在',
            'order.integer'=>'排序为整数数字',
            'id.required'=>'id不能为空',
            'id.integer'=>'id数据格式不正确',
            'id.exists'=>'id数据不正确',
            'categoryurl.required'=>'图标(选中前)不能为空',
            'categoryurlhide.required'=>'图标(选中后)不能为空',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('entity')) {
                $this->errors->add('entity', $messages->first('entity'));
            }
            if ($messages->has('order')) {
                $this->errors->add('order', $messages->first('order'));
            }
            if ($messages->has('id')) {
                $this->errors->add('id', $messages->first('id'));
            }
            if ($messages->has('categoryurl')) {
                $this->errors->add('categoryurl', $messages->first('categoryurl'));
            }
            if ($messages->has('categoryurlhide')) {
                $this->errors->add('categoryurlhide', $messages->first('categoryurlhide'));
            }
        }
    }

    protected function doeditCategoriesInfo(){

        $ret1 = DB::table('categories')->where('id',$this->input['id'])->update([
            'entity' => $this->input['entity'],
            'order' =>$this->input['order'],
            'pic' =>$this->input['categoryurl'],
            'pic_hide' =>$this->input['categoryurlhide'],
            'updated_at' => date('Y-m-d H:i:s',time()),
        ]);

        if(!$ret1){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','保存失败');
            DB::rollBack();
        }
    }


    protected function delCategoriesInfo(){
        $this->validadordelCategoriesInfo();
        if($this->passes()){
            $this->dodelCategoriesInfo();
        }
    }


    protected function validadordelCategoriesInfo(){

        $rules = [
            'id' => 'required|integer|exists:categories,id',
        ];
        $messages = [
            'id.required'=>'id不能为空',
            'id.integer'=>'id数据格式不正确',
            'id.exists'=>'id数据不正确',
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

        if($this->passes()){
            if(in_array($this->input['id'],[1,2,3,4,5])){
                $this->status = 403;
                $this->description = '没有权限，请联系开发者。';
                $this->accepted = false;
                $this->errors->add('id', '没有权限，请联系开发者。');
            }
        }
    }

    protected function dodelCategoriesInfo(){
        $relation = DB::table('categories_tags')
            ->select('tag_id')
            ->where('category_id', $this->input['id'])
            ->get();
        if($relation === null){
            DB::table('categories')->where('id', $this->input['id'])->delete();
        }else{
            $flag = false;
            foreach($relation as $tagInfo){
                $count = DB::table('user_proficiencies')
                    ->select('tag_id')
                    ->where('tag_id', $tagInfo->tag_id)
                    ->count();
                if($count > 0){
                    $flag = true;
                }
            }
            if($flag){
                $this->status = 400;
                $this->description = '参数错误';
                $this->accepted = false;
                $this->errors->add('id','此领域下的标签已经在使用，不能删除');
            }else{
                DB::beginTransaction();

                $ret1 = DB::table('categories_tags')->where('category_id', $this->input['id'])->delete();

                $ret2 = DB::table('categories')->where('id', $this->input['id'])->delete();
                if($ret1 && $ret2){
                    DB::commit();
                }else{
                    $this->status = 400;
                    $this->description = '参数错误';
                    $this->accepted = false;
                    $this->errors->add('id','删除失败');
                    DB::rollBack();
                }
            }
        }
    }

    /*
     * 合并标签
     */
    private function mergeTags(){
//        $tags = DB::table('tags')->select(DB::raw('count(*) as num, id,name'))->groupBy('name')->having('num', '>=', 2)->get();
//        if(!empty($tags)){
//            foreach ($tags as $k =>$v){
//                DB::beginTransaction();
//                $mergeTag = DB::table('tags')->where('name',$v->name)->orderBy('tagged_answers','desc')->first();
//                $tag = DB::table('tags')->where('name',$v->name)->whereNotIn('id',[$mergeTag->id])->orderBy('tagged_answers','desc')->get();
//
//                if(!empty($tag)){
//                    $ret1 = $ret2 = $ret3 = $ret4 = $ret5 = true;
//                    foreach ($tag as $kk=>$vv){
//                        //update question
//                        $questionTags = DB::table('question_tags')->where('tag_id', $vv->id)->get();
//                        if(!empty($questionTags)){
//                            $ret1 = DB::table('question_tags')->where('tag_id', $vv->id)->update([
//                                'tag_id'=>$mergeTag->id,
//                            ]);
//                        }
//
//                        //update actiles
//                        $actleTags = DB::table('article_tags')->where('tag_id', $vv->id)->get();
//                        if(!empty($actleTags)){
//                            $ret2 = DB::table('article_tags')->where('tag_id', $vv->id)->update([
//                                'tag_id'=>$mergeTag->id,
//                            ]);
//                        }
//
//                        //categories_tags
//                        $categoriesTags = DB::table('categories_tags')->where('tag_id', $vv->id)->get();
//                        if(!empty($categoriesTags)){
//                            $ret3 = DB::table('categories_tags')->where('tag_id', $vv->id)->update([
//                                'tag_id'=>$mergeTag->id,
//                            ]);
//                        }
//
//                        //user_proficiencies
//                        $user_proficiencies = DB::table('user_proficiencies')->where('tag_id', $vv->id)->get();
//                        if(!empty($user_proficiencies)){
//                            $ret4 = DB::table('user_proficiencies')->where('tag_id', $vv->id)->update([
//                                'tag_id'=>$mergeTag->id,
//                            ]);
//                        }
//                        //del
//                        $ret5 = DB::table('tags')->where('id', $vv->id)->delete();
//
//                        SphinxQL::create($this->connection)->query('delete from tags where id = '.$vv->id)->execute();
//                    }
//                    $questionnum = DB::table('question_tags')->where('tag_id', $mergeTag->id)->count();
//                    $qactlesnum = DB::table('article_tags')->where('tag_id', $mergeTag->id)->count();
//                    DB::table('tags')->where('id', $mergeTag->id)->update([
//                        'tagged_answers'=>$questionnum,
//                        'tagged_articles'=>$qactlesnum,
//                    ]);
//
//                    $id = $mergeTag->id;
//                    $name = $mergeTag->name;
//                    $ret = SphinxQL::create($this->connection)->query("select * from tags where id=".$id)->execute();
//
//                    if(!empty($ret)){
//                        $sq = SphinxQL::create($this->connection)->replace()->into('tags');
//                        $sq->value('id', $id)->value('name', addslashes($name));
//                        $sq->execute();
//                    }else{
//                        SphinxQL::create($this->connection)->query("insert into tags (id,name) values ({$id},'".addslashes($name)."')")->execute();
//                    }
//                    if(!$ret1 || !$ret2 || !$ret3 || !$ret4 || !$ret5){
//                        DB::rollBack();
//                        \Log::error('[merge tags]'.$v->name.'合并失败');
//                    }else{
//                        DB::commit();
//                        \Log::info('[merge tags]'.$v->name.'合并成功');
//                    }
//                }
//            }
//        }
//
//       $this->tagsTolower();
       $this->merge();
    }

    /**
     * 转换小写
     */
    private function tagsTolower(){
        $tags = DB::table('tags')->select('id','name')->get();
        $connection = new Connection();
        $connection->setParams(['host' => env('SPHINX_HOST'), 'port' => env('SPHINX_PORT')]);
        foreach ($tags as $tagsval){

            DB::table('tags')
                ->where('id','=',$tagsval->id)
                ->update(['name' => strtolower($tagsval->name)]);
            $ret = SphinxQL::create($connection)->query("select * from tags where id=".$tagsval->id)->execute();
            if(empty($ret)){
                SphinxQL::create($connection)->query("insert into tags (id,name) values ({$tagsval->id},'".$tagsval->name."')")->execute();
            }else{
                $sq = SphinxQL::create($connection)->replace()->into('tags');
                $sq->value('id', $tagsval->id)->value('name', strtolower($tagsval->name));
                $sq->execute();
            }
        }
    }

    /**
     * 合并关系表
     */
    private function merge(){
        $question_tags = DB::table('question_tags')->select(DB::raw('count(*) as num,id,tag_id,question_id'))->groupBy('tag_id','question_id')->having('num', '>', 1)->get();
        foreach ($question_tags as $k=>$v){
            DB::table('question_tags')->where('tag_id', $v->tag_id)->where('question_id', $v->question_id)->whereNotIn('id',[$v->id])->delete();
        }
    }
}
