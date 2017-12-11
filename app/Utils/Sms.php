<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/12
 * Time: 13:51
 */
namespace App\Utils;

use App\Entity\SmsCode;

class Sms
{
    //主帐号,对应开官网发者主账号下的 ACCOUNT SID
    protected static $accountSid ;

    //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
    protected static $accountToken;

    //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
    //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
    protected static $appId;

    //请求地址
    //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
    //生产环境（用户应用上线使用）：app.cloopen.com
    protected static $serverIP;

    //请求端口，生产环境和沙盒环境一致都是8883
    protected static $serverPort;

    //REST版本号，在官网文档REST介绍中获得。
    protected static $softVersion = '2013-12-26';

    //模板id
    protected static $templateId;

    //发送短信入库状态码
    protected static $dbStatus = 1;

    //发送短信失败信息
    protected static $errorMsg = '';


    public static function send($mobile, $datas, $type)
    {
        require_once 'CCPRestSDK.php';
        self::initParam($type);
        $rest = new \REST(self::$serverIP,self::$serverPort,self::$softVersion);
        $rest->setAccount(self::$accountSid,self::$accountToken);
        $rest->setAppId(self::$appId);
        $result = $rest->sendTemplateSMS($mobile,$datas,self::$templateId);
        $statusCode =  (int)$result->statusCode;

        //入库
        if(self::$dbStatus == 1 && $statusCode == '000000'){
            //成功
            self::toDatabases($mobile,$datas[0],$type,$result,$statusCode);
            return ['status' =>1];
        }elseif(self::$dbStatus == 0){
            //失败  错误处理
            return ['status' =>0,'msg' => '内部错误'];
        }elseif($statusCode != '000000'){
            self::proErrorMsg($statusCode);
            return ['status' =>0,'msg' => self::$errorMsg];
        }elseif(self::$dbStatus == 2){
            //失败  频发发送
            return ['status' =>0,'msg' => '短信验证码发送过频繁，请稍后再试'];
        }
        //写入日志
        \Log::info('Message sent');
    }

    protected static function initParam($type=''){

        self::$accountSid = config('sms.accountSid');

        self::$accountToken = config('sms.authToken');

        self::$appId = config('sms.appId');

        self::$serverIP = config('sms.onlineServerIp');

        self::$serverPort = config('sms.serverPort');

        if($type){
            self::$templateId = config('sms.type')[$type];
        }
    }


    public static function toDatabases($mobile,$code,$type,$result,$statusCode){
        $res = SmsCode::where('mobile', $mobile)->where('type', $type)->first();

        if(!empty($res)){

            if(time() - (int)$res->send_time < 60){
                self::$dbStatus = 2;
            }else{
                //更新
                $res->code = $code;
                $res->send_time = time();
                $res->back_data = json_encode($result);
                $res->ip = \Request::getClientIp();
                $res->return_status = $statusCode;
                $res->status = 1;
                $res->error_num = 0;
                $ret=$res->save();
                if($ret){
                    self::$dbStatus = 0;
                }
            }

        }else{
            $smsCode = new SmsCode;
            $smsCode->code = $code;
            $smsCode->mobile = $mobile;
            $smsCode->type = $type;
            $smsCode->send_time = time();
            $smsCode->back_data = json_encode($result);
            $smsCode->ip = \Request::getClientIp();
            $smsCode->return_status = $statusCode;
            $smsCode->status = 1;
            $smsCode->error_num = 0;
            $ret=$smsCode->save();
            if($ret){
                self::$dbStatus = 0;
            }
        }

    }

    public static function proErrorMsg($statusCode){
        if(in_array($statusCode,[160038,160039,160040,160041])){
            self::$errorMsg = config('sms.error_ids')[$statusCode];
        }else{
            self::$errorMsg = '发送失败，请稍后再试！';
        }
    }

    public static function checkCode($mobile, $type, $code){
        $ret = SmsCode::where('mobile', $mobile)->where('type', $type)->where('status', 1)->first();
        if(!empty($ret)){
            if(time() - $ret->send_time > 30*60){
                return ['status' => 0, 'msg' => '短信验证码已经超时！'];
            }elseif($ret->code != $code){
                if($ret->error_num == 5){
                    return ['status' => 0, 'msg' => '验证码失效！'];
                }
                //防刷策略
                $ret->error_num = (int)$ret->error_num +1;
                $res=$ret->save();
                if(!$res){
                    return ['status' => 0, 'msg' => '验证失败请稍后再试！'];
                }
                return ['status' => 0, 'msg' => '验证码错误请稍后再试！'];
            }
            //验证成功 更新状态
            $ret->status = 2;
            $res=$ret->save();
            if($res){
                return ['status' => 1 ];
            }else{
                return ['status' => 0, 'msg' => '验证失败请稍后再试！'];
            }
        }else{
            return ['status' => 0, 'msg' => '短信验证码不存在'];
        }
    }

    //操作成功 发送短信
    public static function sendSmsSuccess($mobile, $datas, $templateId){
        require_once 'CCPRestSDK.php';
        self::initParam();
        $rest = new \REST(self::$serverIP,self::$serverPort,self::$softVersion);
        $rest->setAccount(self::$accountSid,self::$accountToken);
        $rest->setAppId(self::$appId);
        $result = $rest->sendTemplateSMS($mobile,$datas,$templateId);
        return  $result->statusCode;
    }
}