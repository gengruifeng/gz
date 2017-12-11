<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/12
 * Time: 13:38
 */

return [

    'accountSid' => '8a48b5514fba2f87014fcaf764f6253a',

    'authToken' => 'c88d5b36976c400d9c819a5caef9e4c5',

    'sandBoxServerIP' => 'sasandboxapp.cloopen.com', // restUrl

    'onlineServerIp' => 'app.cloopen.com', // restUrl

    'serverPort' => '8883', // restUrl

    'appId' => '8aaf070855b647ab0155bed0926d0da6',

    'type' =>[
        'registered' => 108188,
        'retrieve' => 107629,
        'binding' => 108188,
        'updatepass' => 107629,
    ],

    'error_ids' =>[
        '112300' => '接收短信的手机号码为空',
        '112301' => '短信正文为空',
        '112302' => '群发短信已暂停',
        '112303' => '应用未开通短信功能',
        '112304' => '短信内容的编码转换有误',
        '112305' => '应用未上线，短信接收号码外呼受限',
        '112306' => '接收模板短信的手机号码为空',
        '112307' => '模板短信模板ID为空',
        '112308' => '模板短信模板data参数为空',
        '112309' => '模板短信内容的编码转换有误',
        '112310' => '应用未上线，模板短信接收号码外呼受限',
        '112311' => '短信模板不存在',
        '000000' => '发送成功',
        '160000' => '系统错误',
        '160031' => '参数解析失败',
        '160032' => '短信模板无效',
        '160033' => '短信存在黑词',
        '160034' => '号码黑名单',
        '160035' => '短信下发内容为空',
        '160036' => '短信模板类型未知',
        '160037' => '短信内容长度限制',
        '160038' => '短信验证码发送过频繁，请稍后再试',
        '160039' => '超出同模板同号天发送次数上限',
        '160040' => '操作过于频繁，请明天再来',
        '160041' => '通知超出同模板同号码天发送上限',
        '160042' => '号码格式有误',
        '160043' => '应用与模板id不匹配',
        '160050' => '短信发送失败',
    ],


];