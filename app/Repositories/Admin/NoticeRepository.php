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

class NoticeRepository extends Repository implements RepositoryInterface
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
                'errors' => $this->errors->getErrors(),
            ];
        }
        return $wrapper;
    }

   protected function sendmsg(){
       if(!empty($this->input['content'])){
            $this->_send();
       }else{
           $this->status = 400;
           $this->description = '参数错误';
           $this->accepted = false;
           $this->errors->add('content','消息内容不能为空');
           DB::rollBack();
       }
   }

    protected function _send(){
        Notification::saveContents($this->input['content']);
        \Log::info('[admin]system notification,Notification content is '.$this->input['content'].' - adminid:'.$this->input['adminid']);
    }
}
