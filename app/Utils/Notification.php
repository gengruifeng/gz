<?php
namespace App\Utils;
use App\Entity\Notification as Notify;
use App\Entity\Contents;
use App\Entity\User;
use DB;
class Notification
{
    /**
     * @param $from -发送人Id
     * @param $to -收件人id
     * @param $type -通知类型 2-文章信息通知 3-问答信息通知
     * @param $content -通知内容
     * @param $showType -模板类型 1-评论文章 2-收藏文章 3-赞了文章4-在文章中@了5-文章审核通过6-文章审核失败7-文章审核删除 21-回答了问题 22-编辑了问题 23删除了问题 24 回答了关注的问题25 邀请回答问题 26 赞同了回答 27 评论了回答 28 回复了评论 29 关注了我
     * @param $by -通知方式
     * @param $associate   -关联ID
     */
    public static function sendNotify($from , $to , $type , $showType = 0 ,$associate = 0 ,$content='',$by = 0 )
    {

        //验证用户是否存在
        $sender = User::select('id')->where('id',$to)->where('disabled','<',1)->first();

        if(empty($sender)){
            \Log::info('收件人id不存在或者账号被禁用！');
            return ['status'=>0,'msg'=>'用户不存在！'];
        }
        if(!in_array($type,array(2,3))){
            \Log::info('通知类型不存在！');
            return ['status'=>0,'msg'=>'请填写正确的通知类型！'];
        }
        if($type == 2){

            if(!in_array($showType,array(1,2,3,4,5,6,7))){
                \Log::info('通知类型为文章信息通知时，模板类型不在类型数组内！');
                return ['status'=>0,'msg'=>'模板类型不存在！'];
            }
            if($associate == 0){
                \Log::info('通知类型为文章信息通知时，关联id（associate_id）需大于0！');
                return ['status'=>0,'msg'=>'关联id不能为0！'];
            }
        }
        if($type == 3){
            if(!in_array($showType,array(21,22,23,24,25,26,27,28,29))){
                \Log::info('通知类型为问答信息通知时，模板类型不在类型数组内！');
                return ['status'=>0,'msg'=>'模板类型不存在！'];
            }
            if($showType != 29){
                if($associate == 0){
                    \Log::info('通知类型为问答信息通知时，关联id（associate_id）需大于0！');
                    return ['status'=>0,'msg'=>'关联id不能为0！'];
                }
            }
        }
        return self::saveData($from , $to , $type , $showType ,$associate ,$content,$by );
    }


    //入库
    protected static function saveData($from , $to , $type , $showType ,$associate ,$content,$by )
    {
        $resultId = self::saveNotifications($from , $to , $type , $showType ,$associate ,$content,$by );
        if($resultId){
            //写入日志
            \Log::info('通知发送成功，通知id为 '.$resultId);
            return ['status'=>1,'msg'=>'发送成功'];
        }else{
            \Log::info('通知发送失败，失败uid为 '.$to);
            return ['status'=>0,'msg'=>'内部错误'];
        }
    }

    //contents_table 入库
    public  static function saveContents($content){
        if(empty($content)){
            \Log::info('通知类型为系统通知时，内容不能为空！');
            return ['status'=>0,'msg'=>'通知内容为空！'];
        }
        $contents = new Contents;
        $contents->content = $content;
        $contents->save();
        return $contents->id;
    }
    
    //notifications_table 入库
    protected  static function saveNotifications($from , $to , $type , $showType ,$associate ,$content,$by){
        $notify = new Notify;
        $notify->from = $from;
        $notify->recipient = $to;
        $notify->type = $type;
        $notify->show_type = $showType;
        $notify->content = $content;
        $notify->by = $by;
        $notify->associate_id = $associate;
        $notify->read = 0; 
        $notify->save();
        return $notify->id;
    }
}