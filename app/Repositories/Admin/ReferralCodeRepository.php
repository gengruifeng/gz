<?php

namespace App\Repositories\Admin;

use Illuminate\Support\Facades\DB;
use App\Utils\HttpStatus;
use App\Repositories\Repository;
use App\Repositories\RepositoryInterface;
use App\Utils\ShortID;
use App\Utils\Computing;
use Log;

class ReferralCodeRepository extends Repository implements RepositoryInterface
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

    /**
     * 生成邀请码
     * @return array
     */
    protected function addCode(){
        $code = [];
        for ($i=0;$i<50;$i++){
            $code[$i] = [
                'code' => ShortID::generate(8),
            ];
        }
        $result = DB::table('referral_codes')->insert($code);
        if(!$result){
            $this->status = 400;
            $this->description = '参数错误';
            $this->accepted = false;
            $this->errors->add('id','生成邀请码失败');
        }
    }

    /**
     * 获取邀请码列表
     * @return array
     */
    public function getList(){
        $result=[];
        $pageSize = empty($this->input['pageSize'])?20:$this->input['pageSize'];
        $page = empty($this->input['page'])?1:$this->input['page'];

        $countdb = DB::table('referral_codes');

        if(!empty($this->input['used']) && $this->input['used'] != -1){
            if($this->input['used'] == 1){
                $countdb->where('used',$this->input['used']);
            }

            if($this->input['used'] == 2){
                $countdb->where('used',0);
            }
        }else{
            $this->input['used'] = -1;
        }

        if(!empty($this->input['issued']) && $this->input['issued'] != -1){
            if($this->input['issued'] == 1){
                $countdb->where('issued',$this->input['issued']);
            }

            if($this->input['issued'] == 2){
                $countdb->where('issued',0);
            }
        }else{
            $this->input['issued'] = -1;
        }

        $count =$countdb->orderby("created_at","desc")->orderby('id','desc')->count();

        $db = DB::table('referral_codes');

        if(!empty($this->input['used']) && $this->input['used'] != -1){
            if($this->input['used'] == 1){
                $db->where('used',$this->input['used']);
            }

            if($this->input['used'] == 2){
                $db->where('used',0);
            }
        }else{
            $this->input['used'] = -1;
        }

        if(!empty($this->input['issued']) && $this->input['issued'] != -1){
            if($this->input['issued'] == 1){
                $db->where('issued',$this->input['issued']);
            }

            if($this->input['issued'] == 2){
                $db->where('issued',0);
            }
        }else{
            $this->input['issued'] = -1;
        }

        $result['codelist'] =$db->orderby("created_at","desc")->orderby('id','desc')->take($pageSize)->skip(($page-1)*$pageSize)->get();
        $pageinfo= Computing::getPageInfo(['page'=>$page,'pageSize'=>$pageSize],$count);
        $pageinfo['total'] = $count;
        $result['pageinfo'] = $pageinfo;
        $result['issued'] = $this->input['issued'];
        $result['used'] = $this->input['used'];
        $result['pageSize'] = $pageSize;
        $this->doreturn = $result;
    }
}
