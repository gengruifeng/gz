<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/8
 * Time: 14:36
 */
namespace App\Utils;
use Mailgun\Mailgun;
use Log;
class MailGunDo
{

    /**
     * @param $toData  receiver's address
     * @param $template mailtemplate
     * @param $data     mail data
     * @param $time     mail send time
     */
    public static function send($toData, $tile, $template, $data, $time){
        $mgClient = new Mailgun('key-ff3d6c16e19bf738ed190e62c756ef3c');
        $domain = "www.gongzuo.com";
        $result = $mgClient->sendMessage($domain, array(
            'from'    => 'noreply@www.gongzuo.com',
            'to'      => $toData,
            'subject' => $tile,
            'html'    => $template,
            'recipient-variables' => json_encode($data),
            'o:deliverytime' => $time
        ));
        if($result->http_response_code == 200){
            \Log::info('Timed messages has been sent - '.$toData.'- Send Time'.date('Y-m-d H:i:s',$time));
        }else{
            \Log::error($result);
        }
        return $result;
    }
}