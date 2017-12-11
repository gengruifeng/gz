<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/15
 * Time: 14:26
 */

namespace App\Utils;


class Computing
{
    /**
     * 获取过去多少时间
     *
     * @param created_at 发布时间
     */
    public static function timejudgment($created_at){
        $time = strtotime($created_at);
        //获取今天凌晨的时间戳
        $day = strtotime($created_at);
        //获取昨天凌晨的时间戳
        $pday = strtotime(date('Y-m-d',strtotime('-1 day')));
        //获取现在的时间戳
        $nowtime = time();
        $tc = $nowtime-$time;
        if($time<$pday){
            $str = date('Y-m-d H:i:s',$time);
        }elseif($time<$day && $time>$pday){
            $str = "昨天";
        }elseif($tc>60*60){
            $str = floor($tc/(60*60))."小时前";
        }elseif($tc>60){
            $str = floor($tc/60)."分钟前";
        }else{
            $str = "刚刚";
        }
        return $str;
    }
    /**
     * getPageInfo 计算分页
     * @return array
     * @param arrInput allItemCount
     */
    public static  function getPageInfo($arrInput,$allItemCount) {
        $pageSize = 5;
        $allPage = ceil($allItemCount/$arrInput['pageSize']);
        $ret = array(
            'prevPage' => $arrInput['page']==1?1:$arrInput['page']-1,
            'currentPage' => $arrInput['page'],
            'nextPage' => $arrInput['page']>=$allPage?$allPage:$arrInput['page']+1,
            'allPage' => ceil($allItemCount/$arrInput['pageSize']),
            'firstPage' => 1,
        );
        $ret['lastPage'] = $ret['allPage'];
        $ret['pageSize'] = $pageSize;
        $maxPageNo = $ret['allPage'];
        $minPageNo = 1;
        if ($pageSize < $ret['allPage']) {
            $minPageNo = $ret['currentPage'] - 3;
            $minPageNo = $minPageNo < $ret['firstPage'] ? $ret['firstPage'] : $minPageNo;
            $maxPageNo = $minPageNo + $pageSize - 1;
            if ($maxPageNo > $ret['allPage']) {
                $minPageNo = $minPageNo - $maxPageNo + $ret['allPage'];
                $maxPageNo = $ret['allPage'];
            }
        }
        for ($i=$minPageNo; $i <= $maxPageNo; $i++) {
            $ret['pageBar'][] = $i;
        }
        return $ret;
    }
}