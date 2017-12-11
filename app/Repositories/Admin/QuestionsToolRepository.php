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
use Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Utils\HttpStatus;
use App\Repositories\Repository;
use App\Repositories\RepositoryInterface;
use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;


class QuestionsToolRepository extends Repository implements RepositoryInterface
{
    const PATTERN_TAG = '#^(?P<tag>[0-9a-zA-Z\x{4e00}-\x{9fa5}\#\+]+)(;(?&tag))*$#u';       //返回数组

    public $dofunction;

    public $doreturn;

    public $currenpPge = 1;

    public $num = 10;

    public $order = 'id';

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

    protected function _initPram(){
        $this->currenpPge = !empty($this->input['currenpPge'])?$this->input['currenpPge']:1;
        $this->num = !empty($this->input['num'])?$this->input['num']:10;
        $this->order = !empty($this->input['order'])?$this->input['order']:'id';
        $this->orderBy = !empty($this->input['orderBy'])?$this->input['orderBy']:'DESC';
        $this->input['status'] = !empty($this->input['status']) ? $this->input['status'] : 0;
    }

    protected function _where($istotal){
        $db = DB::table('questions_noline')->select('users.display_name','questions_noline.id','questions_noline.status','questions_noline.subject','questions_noline.updated_at','questions_noline.created_at');

        $db->join('users', 'questions_noline.adminid', '=', 'users.id');

        $db->where('questions_noline.status',0);
        $this->order = 'questions_noline.id';
        if($istotal){
            $ret = $db->count();
        }else{
            $ret = $db->orderBy($this->order, $this->orderBy)->skip(($this->currenpPge - 1) * $this->num)->take($this->num)->get();
        }
        return $ret;
    }

    protected function del(){
        
        $this->validadordel();
        if($this->passes()){
            $this->dodel();
        }
    }

    protected function validadordel(){
        $rules = [
            'id'=>"required|integer|exists:questions_noline,id",

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
        $ret1 = DB::table('questions_noline')->where('id',$this->input['id'])->update(['status' => 2]);
        if(!$ret1){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','删除失败');
            \Log::error('[admin]del unpublished questions, Failed to update the database - adminid:'.$this->input['adminid']);
        }else{
            \Log::info('[admin]del unpublished questions, questionid is '.$this->input['id'].' - adminid:'.$this->input['adminid']);
        }
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


    protected function add(){
        $this->validateadd(false);
        if($this->passes()){
            $this->doadd();
        }
    }


    /**
     * Validate the input
     *
     * @param void
     *
     * @return void
     */
    private function validateadd($isedit = false)
    {
        $rules = [
            'subject' => 'required',
            'detail' => 'required',
            'tags' => 'required|array',
        ];
        $messages = [
            'subject.required' => '请填写标题',
            'detail.required' => '请填写内容',
            'tags.required' => '请添加至少一个标签',
            'tags.array' => '标签不正确'
        ];
        $validator = Validator::make($this->input, $rules, $messages);

        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '请输入信息';
            $this->accepted = false;

            $messages = $validator->errors();

            if ($messages->has('subject')) {
                $this->errors->add('subject', $messages->first('subject'));
            }

            if ($messages->has('detail')) {
                $this->errors->add('detail', $messages->first('detail'));
            }

            if ($messages->has('tags')) {
                $this->errors->add('tags', $messages->first('tags'));
            }
        }

        if($this->passes()){
            $ret = DB::table('questions')->where('subject',$this->input['subject'])->where('status', 0)->first();
            if(!empty($ret)){
                $this->status = 400;
                $this->description = '请输入信息';
                $this->accepted = false;
                $this->errors->add('subject','线上问题已经存在，请修改问题标题');

            }
        }

        if($this->passes()){
            if($isedit){
                $ret = DB::table('questions_noline')->where('id','<>',$this->input['id'])->where('subject',$this->input['subject'])->where('status', 0)->first();
            }else{
                $ret = DB::table('questions_noline')->where('subject',$this->input['subject'])->where('status', 0)->first();
            }
            if(!empty($ret)){
                $this->status = 400;
                $this->description = '请输入信息';
                $this->accepted = false;
                $this->errors->add('subject','问题已经存在，请修改问题标题');

            }
        }

    }

    protected function doadd(){
        $ret = DB::table('questions_noline')->insertGetId([
            'subject' => $this->input['subject'],
            'detail' => $this->input['detail'],
            'tagsjson' => json_encode($this->input['tags']),
            'adminid' => $this->input['adminid'],
            'created_at' => date('Y-m-d H:i:s',time()),
            'updated_at' => date('Y-m-d H:i:s',time()),

        ]);
        if(!$ret){
            $this->status = 500;
            $this->description = 'Internal Server Error';
            $this->accepted = false;
            $this->errors->add('id','操作失败');
            \Log::error('[admin]Add unpublished questions, Failed to update the database - adminid:'.$this->input['adminid']);
            return 0;
        }else{
            \Log::info('[admin]Add unpublished questions, questionid is '.$ret.' - adminid:'.$this->input['adminid']);
        }
    }

    public function getone(){
        if(empty($this->input['id'])){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','参数错误');
            return 0;
        }
        $ret = DB::table('questions_noline')->where('id', $this->input['id'])->first();
        if(!empty($ret)){
            $data['subject'] = $ret->subject;
            $data['tagsjson'] = json_decode($ret->tagsjson);
            $data['id'] = $this->input['id'];
            $data['detail'] = $ret->detail;
            $this->data = $data;
        }else{
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','参数错误');
            return 0;
        }
    }

    protected function edit(){
        $this->validateadd(true);
        if($this->passes()){
            $this->doedit();
        }
    }

    protected function doedit(){
        $ret = DB::table('questions_noline')->where('id',$this->input['id'])->update([
            'subject' => $this->input['subject'],
            'detail' => $this->input['detail'],
            'tagsjson' => json_encode($this->input['tags']),
            'adminid' => $this->input['adminid'],
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
        if(!$ret){
            $this->status = 500;
            $this->description = 'Internal Server Error';
            $this->accepted = false;
            $this->errors->add('id','操作失败');
            \Log::error('[admin]edit unpublished questions, Failed to update the database - adminid:'.$this->input['adminid']);
            return 0;
        }else{
            \Log::info('[admin]edit unpublished questions, questionid is '.$this->input['id'].' - adminid:'.$this->input['adminid']);
        }
    }


    /**
     *获取用户列表
     */
    public function getUserList(){
        //初始化列表
        $this->_initPram();

        $ret = $this->_userWhere(0);
        if(!empty($ret)){
            $totalret = $this->_userWhere(1);
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


    protected function _userWhere($istotal){
        $db = DB::table('users')->select('users.id','users.display_name','users.email','users.mobile','users.avatar');

        $db->where('users.disabled', 0);

        if(!empty($this->input['display_name'])){
            $db->where('users.display_name','like','%'.$this->input['display_name'].'%');
        }

        if(!empty($this->input['questionuser']) && $this->input['questionuser'] == 1){
            $db->join('questions_user', 'users.id', '=', 'questions_user.uid');
            $this->order = 'questions_user.created_at';
        }else{
            $userUIds = DB::table('questions_user')->lists('uid');
            $db->whereNotIn('users.id',$userUIds);
        }

        if($istotal){
            $ret = $db->count();
        }else{
            $ret = $db->orderBy($this->order, $this->orderBy)->skip(($this->currenpPge - 1) * $this->num)->take($this->num)->get();
        }
        return $ret;
    }

    protected function userDel(){

        $this->validadoruserDel();
        if($this->passes()){
            $this->douserDel();
        }
    }

    protected function validadoruserDel(){
        $rules = [
            'id'=>"required|integer|exists:questions_user,uid",
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

    protected function douserDel(){
        $ret1 = DB::table('questions_user')->where('uid',$this->input['id'])->delete();
        if(!$ret1){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','删除失败');
        }
    }

    protected function userAdd(){

        $this->validadoruserAdd();
        if($this->passes()){
            $this->douseruserAdd();
        }
    }

    protected function validadoruserAdd(){
        $rules = [
            'id'=>"required|integer|exists:users,id",

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

    protected function douseruserAdd(){
        $ret1 = DB::table('questions_user')->insert([
            'uid' => $this->input['id'],
            'adminid' => $this->input['adminid'],
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
        if(!$ret1){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','删除失败');
        }
    }

    protected function release(){
        Log::info('[synchronization problem] The synchronization problem script begins');
        $ret = DB::table('questions_noline')->select('id','subject','tagsjson','detail')->where('status',0)->orderBy('id', 'asc')->limit(200)->get();
        if(!empty($ret)){
            $user = DB::table('questions_user')->lists('uid');
            if(!empty($user)){
                $newRet = $ret;
                $newUserQuestion = [];
                $newUserQuestion= $this->initUserQuestion($user,$newRet,$newUserQuestion);
                if(!empty($newUserQuestion)){
                    foreach ($newUserQuestion as $k=>$v){
                        if(!empty($v) && is_array($v)){
                            foreach ($v as $kk=>$vv){
                                DB::beginTransaction();
                                $resource= DB::table('questions')->where('subject', $vv['subject'])->first();

                                if($resource){
                                    DB::rollBack();
                                    Log::error('[synchronization problem] Problem Title already exists ，questions_noline->id='.$kk);
                                    continue;
                                }

                                $sretime = strtotime(date('Y-m-d',time()))+24*3600+9*3600;
                                $eretime = strtotime(date('Y-m-d',time()))+24*3600+24*3600;
                                $retime = rand($sretime,$eretime);
                                $retime = date('Y-m-d H:i:s',$retime);
                                $uid = $k;
                                $subject = $vv['subject'];
                                $detail = $vv['detail'];
                                $resource1 = DB::table('questions')->insertGetId([
                                    'subject' => $subject,
                                    'detail' => $detail,
                                    'uid' => $uid,
                                    'stared' => 1,
                                    'created_at' => $retime,
                                    'updated_at' => $retime,
                                ]);
                                if(!$resource1){
                                    DB::rollBack();
                                    Log::error('[synchronization problem]Problem Failed to store ，questions_noline->id='.$kk);
                                    continue;
                                }

                                $tagsjson = json_decode($vv['tagsjson']);

                                if(empty($tagsjson)){
                                    DB::rollBack();
                                    Log::error('[synchronization problem]No tags ，questions_noline->id='.$kk);
                                    continue;
                                }
                                foreach ($tagsjson as $kkk=>$vvv){
                                    $resource2 = DB::table('question_tags')->insertGetId([
                                        'question_id' => $resource1,
                                        'tag_id' => $vvv,
                                        'created_at' => $retime,
                                        'updated_at' => $retime,
                                    ]);
                                    if(!$resource2){
                                        DB::rollBack();
                                        Log::error('[synchronization problem]Tag storage failed ，questions_noline->id='.$kk);
                                        break;
                                    }
                                }
                                if(!$resource2){
                                    DB::rollBack();
                                    Log::error('[synchronization problem]Tag storage failed ，questions_noline->id='.$kk);
                                    continue;
                                }

                                $resource3 = DB::table('tags')->whereIn('id',$tagsjson)->increment('tagged_answers', 1);
                                if(!$resource3){
                                    DB::rollBack();
                                    Log::error('[synchronization problem]Tag increment failed ，questions_noline->id='.$kk);
                                    continue;
                                }

                                $id = $resource1;
                                $ret = SphinxQL::create($this->connection)->query("select * from questions where id=".$id)->execute();
                                if(!empty($ret)){
                                    $sq = SphinxQL::create($this->connection)->replace()->into('questions');
                                    $sq->value('id', $id)->value('subject', addslashes($subject));
                                    $sq->execute();
                                }else{
                                    SphinxQL::create($this->connection)->query("insert into questions values ({$id},'".addslashes($subject)."')")->execute();

                                }
                                $resource4 = DB::table('questions_noline')->where('id', $kk)->update([
                                    'status' => 1,
                                    'uid' =>$uid,
                                    'release_at' => (int)strtotime($retime),
                                ]);

                                if(!$resource4){
                                    DB::rollBack();
                                    Log::error('[synchronization problem]Tag storage failed ，questions_noline->id='.$kk);
                                    continue;
                                }

                                $resource5 = DB::table('question_stars')->insertGetId([
                                    'question_id' => $resource1,
                                    'uid' => $uid,
                                    'created_at' => $retime,
                                    'updated_at' => $retime,
                                ]);

                                if(!$resource5){
                                    DB::rollBack();
                                    Log::error('[synchronization problem]question_stars storage failed ，questions_noline->id='.$kk);
                                    continue;
                                }

                                DB::commit();
                            }
                        }
                    }
                }else{
                    Log::error('[synchronization problem] Data allocation error');
                }
            }else{
                Log::warning('[synchronization problem] No users found');
            }
        }else{
            Log::warning('[synchronization problem] Did not find the problem can be synchronized data');
        }

        Log::info('[synchronization problem] The synchronization problem script ends');
    }

    protected function initUserQuestion($user,&$newRet,&$newUserQuestion){
        foreach ($user as $k => $v){
            if(!empty($newRet)){
                foreach ($newRet as $kk=>$vv){
                    $newUserQuestion[$v][$vv->id]['subject'] = $vv->subject;
                    $newUserQuestion[$v][$vv->id]['tagsjson'] = $vv->tagsjson;
                    $newUserQuestion[$v][$vv->id]['detail'] = $vv->detail;
                    unset($newRet[$kk]);
                    break;
                }
            }else{
                break;
            }
        }
        if(!empty($newRet)){
            $this->initUserQuestion($user,$newRet,$newUserQuestion);
        }
        return $newUserQuestion;

    }

    /**
     * 更正关注数
     */
    public function updateStared(){
        $ret = DB::table('questions')->where('status',0)->where('created_at', '>=',date('Y-m-d H:i:s',1478739600))->where('created_at', '<=',date('Y-m-d H:i:s',1478793600))->get();
        if(!empty($ret)){
            foreach ($ret as $k=>$v){
                $sret = DB::table('question_stars')->where('question_id', $v->id)->count();
                if(!empty($sret)){
                    DB::table('questions')->where('id', $v->id)->update([
                        'stared' =>$sret,
                    ]);
                }else{
                    DB::table('question_stars')->insertGetId([
                        'question_id' => $v->id,
                        'uid' => $v->uid,
                        'created_at' => $v->created_at,
                        'updated_at' => $v->created_at,
                    ]);
                    DB::table('questions')->where('id', $v->id)->update([
                        'stared' =>1,
                    ]);
                }
            }
        }
    }
}
