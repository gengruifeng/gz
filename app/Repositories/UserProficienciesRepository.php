<?php

namespace App\Repositories;

use App\Entity\UserProficiencies;
use Illuminate\Support\Facades\DB;
use Validator;
use Log;
use App\Utils\HttpStatus;

class UserProficienciesRepository extends Repository implements RepositoryInterface
{

    public $dofunction;

    public $doreturn;

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
                'errors' => $this->errors->getErrors()
            ];
        }
        return $wrapper;
    }

    /**
     * 根据登陆uid获取用户的标签
     * @return array
     */
    public function getUserTags($uid){
        $returnData = [];
        $userTags = DB::table('user_proficiencies')->select('tag_id')->where('uid',$uid)->orderBy('created_at','desc')->get();
        if(!empty($userTags)){
            foreach ($userTags as $value){
                $tag = DB::table('tags')->select('name')->where('id',$value->tag_id)->first();
                if(!empty($tag)){
                    $returnData[] = [
                        'tag_id' => $value->tag_id,
                        'name' => $tag->name,
                    ];
                }
            }
        }
        return $returnData;
    }


    public function subcategory()
    {
        $this->_validator();
        if ($this->passes()) {
            $this->getCategoryTag();
        }
    }

    /**
     * 验证参数
     */
    protected function _validator(){
        if(empty($this->input['categoryid'])){
            $this->status = 400;
            $this->description = '参数不能为空！';
            $this->error_name = 'Bad Request';
            $this->accepted = false;
            $this->errors->add('categoryid', '请选擅长的领域！');
            return 0 ;
        }
        foreach ($this->input['categoryid'] as $value){
            $ret = DB::table('categories')->select('id')->where('id', $value)->first();
            if(empty($ret)){
                $this->status = 400;
                $this->description = '参数数据不正确！';
                $this->error_name = 'Bad Request';
                $this->accepted = false;
                $this->errors->add('categoryid', '擅长领域数据不正确！');
                break ;
            }
        }
    }

    /**
     * 根据获取
     */
    public function getCategoryTag(){
        $tags = [];
        foreach ($this->input['categoryid'] as $value){
            $ret = DB::table('categories_tags')->select('categories_tags.tag_id')->join('tags', 'categories_tags.tag_id', '=', 'tags.id')->where('category_id', $value)->orderBy('tags.created_at','desc')->get();
            if(!empty($ret)){
                foreach ($ret as $k=>$v){
                    $retTag = DB::table('tags')->select('name')->where('id', $v->tag_id)->first();
                    if(!empty($retTag)){
                        $tags[] = $v->tag_id;
                    }
                }
            }
        }
        if(!empty($tags)){
            $tags = DB::table('tags')->select('id','name')->whereIn('id', $tags)->orderBy('created_at','desc')->get();
        }
        $this->doreturn = $tags;
    }

    /**
     * 获取返回值
     * @return mixed
     */
    public function returnData(){

        $this->status = 200;
        return $this->doreturn;
    }

    public function subUserTags(){
        $this->_validatorTags();
        if ($this->passes()) {
            $this->updateUserTags();
        }
    }

    /**
     * 验证参数
     */
    protected function _validatorTags(){
        if(empty($this->input['tagsid'])){
            $this->status = 400;
            $this->description = '参数不能为空！';
            $this->error_name = 'Bad Request';
            $this->accepted = false;
            $this->errors->add('tagsid', '请选擅长领域的标签！');
            return 0 ;
        }
        if(count($this->input['tagsid'])>=10){
            $this->status = 400;
            $this->description = '参数不能为空！';
            $this->error_name = 'Bad Request';
            $this->accepted = false;
            $this->errors->add('tagsid', '最多可以选择9个标签！');
            return 0 ;
        }
        foreach ($this->input['tagsid'] as $value){
            $ret = DB::table('tags')->select('id')->where('id', $value)->first();
            if(empty($ret)){
                $this->status = 400;
                $this->description = '参数数据正确！';
                $this->error_name = 'Bad Request';
                $this->accepted = false;
                $this->errors->add('tagsid', '擅长领域标签数据正确！');
                break ;
            }
        }
    }

    /**
     * 更新用户的标签
     */
    public function updateUserTags(){
        $uid = $this->input['uid'];
        //开启事务
        DB::beginTransaction();
        $retTwo = true;
        $retOne = true;
        $is = DB::table('user_proficiencies')->where('uid', $uid)->get();
        if(!empty($is)){
            $retOne = DB::table('user_proficiencies')->where('uid', $uid)->delete();
        }

        foreach ($this->input['tagsid'] as $value){
            $ret = DB::table('user_proficiencies')->insert(
                [
                    'uid' => $uid,
                    'tag_id' => $value,
                ]
            );
            if(!$ret){
                $retTwo = false;
            }
        }
        if(!$retOne || !$retTwo){
            DB::rollBack();
            $this->status = 400;
            $this->description = '参数错误';
            $this->error_name = 'Bad Request';
            $this->accepted = false;
            $this->errors->add('tagsid', '写入数据库失败！');
        }

        DB::commit();
    }
}
