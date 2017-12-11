<?php

namespace App\Repositories\Admin;

use App\Utils\HttpStatus;
use Illuminate\Support\Facades\DB;
use App\Repositories\Repository;
use App\Repositories\RepositoryInterface;
use Log;

class AdminAuthRepository extends Repository implements RepositoryInterface
{

    public $errorType;

    /**
     * 后台认证权限
     *
     * {@inheritdoc}
     */
    public function contract()
    {

        if(!empty($this->input['uid'])){
            //先判断是不是后台管理员
            $info = DB::table('users')->select('group_id')->where('id',$this->input['uid'])->first();
            if(!empty($info->group_id)){
                $groupInfo = DB::table('user_groups')->select('id')->where('id',$info->group_id)->first();
                if(empty($groupInfo->id)){
                    $this->errorType = 1;
                    $this->accepted = false;
                    $this->status = 403;
                    $this->description = '您不是后台管理员';
                    $this->errors->add('uid', '您不是后台管理员');
                }
            }else{
                $this->errorType = 1;
                $this->accepted = false;
                $this->status = 403;
                $this->description = '您不是后台管理员';
                $this->errors->add('uid', '您不是后台管理员');

            }
            //在判断有没有改权限
            if($this->passes()){
                $name = \Route::currentRouteName();
                if($name != 'adminindex'){
                    $ret = DB::table('competence_group')->select('competence_group.id')->join('competence', 'competence_group.competence_id', '=', 'competence.id')->where('competence_group.group_id', $info->group_id)->where('competence.url_name', $name)->first();
                    if(!$ret){
                        $this->errorType = 2;
                        $this->accepted = false;
                        $this->status = 403;
                        $this->description = '您没有该功能的权限，请联系管理员。';
                        $this->errors->add('uid', '您没有该功能的权限，请联系管理员。');
                    }
                }

            }
        }else{
            $this->errorType = 1;
            $this->accepted = false;
            $this->status = 403;
            $this->description = '您不是后台管理员';
            $this->errors->add('uid', '您不是后台管理员');
        }
    }

    /**
     * Wrap the contract result to JSON object.
     *
     * {@inheritdoc}
     */
    public function wrap()
    {
        $wrapper = [];

        if (! $this->passes()) {
            $wrapper = [
                'error_id' => $this->status,
                'description' => $this->description,
                'error_name' => HttpStatus::$statusTexts[$this->status],
                'errors' => $this->errors->getErrors(),
            ];
        }
        return $wrapper;
    }
}
