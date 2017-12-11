<?php

namespace App\Repositories;

use DB;
use Log;
class QuestionsNolineRepository extends Repository implements RepositoryInterface
{

    public function contract()
    {
        //查询出已发布 未加入统计的数据
        $currenttime = time();
        $data = DB::table('questions_noline')
            ->select(DB::raw("count(*) as num "),"uid")
            ->where(['status'=>1,'is_question'=>0])
            ->where('release_at','<',$currenttime)
            ->groupby('uid')
            ->get();

        //同步数据到用户统计表
        if(!empty($data)){
            foreach($data as $user){
                $this->syncUserAnalysis($user->uid,$user->num,$currenttime);
            }
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
                'error_id' => $this->status = 400,
                'description' => '参数错误',
                'error_name' => 'Bad Request',
                'errors' => $this->errors->getErrors()
            ];
        }

        return $wrapper;
    }
    //同步数据到用户统计表
    private function syncUserAnalysis($uid,$num,$time){
        DB::beginTransaction();
        $ret1 = DB::table('user_analysis')
            ->where('uid',$uid)
            ->increment('question', $num);
        $ret2 = DB::table('questions_noline')
            ->where(['status'=>1,'is_question'=>0,'uid'=>$uid])
            ->where('release_at','<',$time)
            ->update(array('is_question' => 1));
        if($ret1 && $ret2){
            DB::commit();
        }else{
            DB::rollback();
        }   

    }


}
