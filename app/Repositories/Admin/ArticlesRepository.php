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
use Log;

class ArticlesRepository extends Repository implements RepositoryInterface
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
        0 => '审核中',
        1 => '审核通过',
        2 => '审核不通过',
        3 => '已删除',
        4 => '编辑',
    ];

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
             \Log::info('[admin]Getting the articles list data successful - adminid:'.$this->input['adminid']);
         }else{
             $this->data = [
                 'status' =>0,
                 'currenpPge' =>$this->currenpPge,
                 'totalPage' =>0,
                 'total' =>0,
                 'data' =>$ret,
             ];
             \Log::info('[admin]No articles data - adminid:'.$this->input['adminid']);
         }
     }

    protected function _initPram(){
        $this->currenpPge = !empty($this->input['currenpPge'])?$this->input['currenpPge']:1;
        $this->num = !empty($this->input['num'])?$this->input['num']:10;
        $this->order = !empty($this->input['order'])?$this->input['order']:'created_at';
        $this->orderBy = !empty($this->input['orderBy'])?$this->input['orderBy']:'DESC';
    }

    protected function _where($istotal){
        $db = DB::table('articles')->select('articles.id','users.display_name','articles.updated_at','articles.subject','articles.viewed','articles.created_at','articles.standard');

        $db->join('users', 'articles.uid', '=', 'users.id');

        if(!empty($this->input['display_name'])){
            $db->where('users.display_name','like','%'.$this->input['display_name'].'%');
        }

        if(!empty($this->input['subject'])){
            $db->where('articles.subject','like','%'.$this->input['subject'].'%');
        }

        if(!empty($this->input['stime'])){
            $db->where('articles.created_at','>=',$this->input['stime']);
        }

        if(!empty($this->input['etime'])){
            $db->where('articles.created_at','<=',$this->input['etime']);
        }
        $this->input['standard'] = (int)$this->input['standard'];
        if(!empty($this->input['standard']) && $this->input['standard'] != -1 || $this->input['standard'] == 0){
            $db->where('articles.standard',$this->input['standard']);
        }

        //逆向思维
        if((!empty($this->input['scomments'] )) && (!empty($this->input['ecomments']))){
            $db->whereIn('articles.id', function ($query) {
                $query->select('article_comments.article_id')
                    ->from('article_comments')->groupBy('article_comments.article_id')->having(DB::raw('count(*)'),'>=', (int)$this->input['scomments'])->having(DB::raw('count(*)'),'<=', (int)$this->input['ecomments']);
            });
        }elseif($this->input['scomments'] === 0){
            $db->whereNotIn('articles.id', function ($query) {
                $query->select('article_comments.article_id')
                    ->from('article_comments')->groupBy('article_comments.article_id')->having(DB::raw('count(*)'),'<', (int)$this->input['scomments']);
            });
        }elseif(!empty($this->input['scomments'])){

            $db->whereIn('articles.id', function ($query) {
                $query->select('article_comments.article_id')
                    ->from('article_comments')->groupBy('article_comments.article_id')->having(DB::raw('count(*)'),'>=', (int)$this->input['scomments']);
            });
        }elseif(!empty($this->input['ecomments'])){
            $db->whereNotIn('articles.id', function ($query) {
                $query->select('article_comments.article_id')
                    ->from('article_comments')->groupBy('article_comments.article_id')->having(DB::raw('count(*)'),'>', (int)$this->input['ecomments']);
            });
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
            $ret[$k]->standard = $this->standard[$v->standard];

            $html = '';
            $res = DB::table('article_history')->select('article_history.*','users.display_name')->join('users', 'article_history.adminid', '=', 'users.id')->where('article_history.article_id',$v->id)->get();
            if(!empty($res)){
                foreach ($res as $kk=>$vv){
                    $reson = '';
                    if($vv->type == 2 || $vv->type == 3){
                        $reson = '<br />原因：'.$vv->reason;
                    }
                    $html .= '<p>'.$vv->display_name.'|'.($this->standard[$vv->type]).'|'.$vv->created_at.$reson.'</p><hr />';
                }
            }else{
                $html .= '<p> 无操作记录</p>';
            }
            $ret[$k]->caozuo = $html;

            $num = DB::table('article_comments')->where('article_id',$v->id)->count();

            $ret[$k]->num = $num;
        }
    }


    /**
     * 审核
     */
    protected function check(){
        $this->validadorcheck();
        if($this->passes()){
            $this->docheck();
        }
    }


    protected function validadorcheck(){
        $rules = [
            'id' => 'required|integer',//id
            'type' => 'required|in:1,2,3',
            'reason' => 'required_if:type,2,3',
        ];
        $message = [
            'id.required' => 'id不能为空！',
            'id.integer' => 'id数据格式不正确！',
            'type.required' => '类型不能为空！',
            'type.in' => '类型数据不正确！',
            'reason.required_if' => '原因不能为空！',

        ];
        $validator = Validator::make($this->input, $rules, $message);
        if ($validator->fails()) {

            $this->status = 400;
            $this->description = '参数错误';
            $this->error_name = 'Bad Request';
            $this->accepted = false;
            $messages = $validator->errors();

            if ($messages->has('id')) {
                $this->errors->add('id', $messages->first('id'));
            }

            if ($messages->has('type')) {
                $this->errors->add('type', $messages->first('type'));
            }

            if ($messages->has('reason')) {
                $this->errors->add('reason', $messages->first('reason'));
            }

        }
        if($this->passes()){
            $ret = DB::table('articles')->select('uid')->where('id',$this->input['id'])->first();
            if(!$ret){
                $this->errors->add('id', 'id验证不匹配！');
                $this->status = 400;
                $this->description = '参数错误';
                $this->error_name = 'Bad Request';
                $this->accepted = false;
            }else{
                $this->input['uid'] = $ret->uid;
            }

        }

    }

    /**
     *
     */
    protected function docheck(){
        DB::beginTransaction();

        DB::table('articles')->where('id',$this->input['id'])->update([
            'standard' => $this->input['type']
        ]);

        $ret2 = DB::table('article_history')->insert([
            'article_id' => $this->input['id'],
            'type' => $this->input['type'],
            'reason' => !empty($this->input['reason'])?$this->input['reason']:'',
            'adminid' =>$this->input['adminid'],
            'created_at' =>date('Y-m-d H:i:s',time()),
        ]);
        $ret = DB::table('articles')->select('uid')->where('id',$this->input['id'])->first();
        $ret1 = true;
        if($this->input['type'] == 1){
            $showType = 5;
            $content = '';
        }elseif($this->input['type'] == 2){
            $showType = 6;
            $content = $this->input['reason'];
        }elseif($this->input['type'] == 3){
            $showType = 7;
            $content = $this->input['reason'];
            $articlesVotesCount = DB::table('article_votes')->where('article_id',$this->input['id'])->count();
            if(!empty($articlesVotesCount)){
                $retis = DB::table('user_analysis')->select('reputation')->where('uid',$ret->uid)->first();
                if(!empty($retis) && $retis->reputation >= $articlesVotesCount){
                    $ret1 = DB::table('user_analysis')->where('uid', $ret->uid)->decrement('reputation', $articlesVotesCount);
                }else{
                    DB::table('user_analysis')->where('uid', $ret->uid)->update(['reputation' => 0]);
                }
            }
            $is = DB::table('article_tags')->where('article_id', $this->input['id'])->get();
            if($is){
                foreach ($is as $k=>$v){
                    $deTag = DB::table('tags')->where('id',$v->tag_id)->decrement('tagged_articles',1);
                    if(!$deTag){
                        $this->status = 400;
                        $this->description = '参数错误';
                        $this->accepted = false;
                        $this->errors->add('id','删除失败');
                        DB::rollBack();
                        return 0;
                    }
                }
            }
        }

        // 发消息
        $us = Notification::sendNotify($this->input['adminid'],$ret->uid,2,$showType,$this->input['id'],$content,0);
        if(!$ret1 || !$ret2 ||  $us['status'] != 1){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','操作失败');
            DB::rollBack();
            \Log::error('[admin]Review the article,Failed to update the database,type :'.$this->input['type'].', articleid is '.$this->input['id'].' - adminid:'.$this->input['adminid']);
        }else{
            DB::commit();
            \Log::info('[admin]Review the article,type :'.$this->input['type'].', articleid is '.$this->input['id'].' - adminid:'.$this->input['adminid']);
        }
    }

    public function getArticlesTags($id){
        $data = [];
        $ret = DB::table('article_tags')->where('article_id',$id)->get();
        if(!empty($ret)){
            foreach ($ret as $k=>$v){
                $data[$v->tag_id] = $v->article_id;
            }
        }
        return $data;
    }

    protected function edit(){
        $this->validadoredit();
        if($this->passes()){
            $this->doedit();
        }
    }


    protected function validadoredit(){

        $rules = [
            'subject' => "required|unique:questions,subject,{$this->input['id']}|between:0,50",
            'detail' => 'required',
            'tag' => 'required|array',
        ];
        $messages = [
            'subject.required'=>'标题不能为空',
            'subject.between'=>'标题应为0到50个字符',
            'subject.unique'=>'标题已经存在',
            'detail.required'=>'内容不能为空',
            'tag.required'=>'标签不能为空',
            'tag.array'=>'标签数据格式不正确',
        ];
        $validator = Validator::make($this->input, $rules, $messages);
        if ($validator->fails()) {
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $messages = $validator->errors();
            if ($messages->has('subject')) {
                $this->errors->add('subject', $messages->first('subject'));
            }

            if ($messages->has('detail')) {
                $this->errors->add('detail', $messages->first('detail'));
            }
            if ($messages->has('tag')) {
                $this->errors->add('tag', $messages->first('tag'));
            }
        }
        if($this->passes()){
            if(count($this->input['tag']) > 5){
                $this->status = 400;
                $this->description = '参数错误';
                $this->accepted = false;
                $this->errors->add('tag', '标签个数不能超过5个');
                return 0;
            }
            foreach ($this->input['tag'] as $k=>$v){
                $ret = DB::table('tags')->where('id', $v)->first();
                if(!$ret){
                    $this->status = 400;
                    $this->description = '参数错误';
                    $this->accepted = false;
                    $this->errors->add('tag', '标签数据不正确');
                    return 0;
                }
            }

        }
    }

    protected function doedit(){
        DB::beginTransaction();

        DB::table('articles')->where('id',$this->input['id'])->update([
            'subject' => $this->input['subject'],
            'detail' => $this->input['detail']
        ]);
        $ret2 = DB::table('article_history')->insert([
            'article_id' => $this->input['id'],
            'type' => 4,
            'adminid' =>$this->input['adminid'],
            'created_at' =>date('Y-m-d H:i:s',time()),
        ]);
        $ret3 = true;
        $is = DB::table('article_tags')->where('article_id', $this->input['id'])->get();
        if($is){
            foreach ($is as $k=>$v){
                $deTag = DB::table('tags')->where('id',$v->tag_id)->decrement('tagged_articles',1);
                if(!$deTag){
                    $this->status = 400;
                    $this->description = '参数错误';
                    $this->accepted = false;
                    $this->errors->add('id','删除失败');
                    DB::rollBack();
                    return 0;
                }
            }
            $ret3 = DB::table('article_tags')->where('article_id', $this->input['id'])->delete();
        }

        foreach ($this->input['tag'] as $k=>$v){
            $ret = DB::table('article_tags')->insert([
                'article_id' => $this->input['id'],
                'tag_id' => $v,
                'created_at' =>date('Y-m-d H:i:s',time()),
            ]);
            $inTag = DB::table('tags')->where('id',$v)->increment('tagged_articles',1);
            if(!$ret || !$inTag){
                $this->status = 400;
                $this->description = '参数错误';
                $this->accepted = false;
                $this->errors->add('id','保存失败');
                DB::rollBack();
                \Log::error('[admin]Review the article,Tags Failed to update the database, articleid is '.$this->input['id'].' - adminid:'.$this->input['adminid']);
                return 0;
            }
        }

        if(!$ret2 || !$ret3){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','保存失败');
            DB::rollBack();
            \Log::error('[admin]Edit the article,Failed to update the database, articleid is '.$this->input['id'].' - adminid:'.$this->input['adminid']);
        }else{
            \Log::info('[admin]Edit the article, articleid is '.$this->input['id'].' - adminid:'.$this->input['adminid']);
            DB::commit();
        }
    }


    protected function upload(){
        if(empty($this->input['thumbnails']) || empty($this->input['id'])){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','参数不正确');
            \Log::error('[admin]Upload article list chart,Parameter error, articleid is '.$this->input['id'].' - adminid:'.$this->input['adminid']);
        }
        if($this->passes()){
            $ret1 = DB::table('articles')->where('id',$this->input['id'])->update([
                'thumbnails' => $this->input['thumbnails']
            ]);
            if(!$ret1){
                $this->status = 400;
                $this->description = '参数错误';
                $this->accepted = false;
                $this->errors->add('id','s');
                \Log::error('[admin]Upload article list chart,Failed to update the database, articleid is '.$this->input['id'].' - adminid:'.$this->input['adminid']);
            }else{
                \Log::info('[admin]Upload article list chart, articleid is '.$this->input['id'].' - adminid:'.$this->input['adminid']);
            }
        }
    }

  
}
