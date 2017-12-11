<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/8
 * Time: 14:36
 */
namespace App\Utils;

class Email
{
    /**
     * @var receiver's address
     */
    protected static $mailaddressee;

    /**
     * @var  Mailbox title
     */
    protected static $title;

    /**
     * @var Annex Address
     */
    protected static $File;

    /**
     * @var Attachment Name
     */
    protected static $Filename;

    /**
     * @var Accessory Type
     */
    protected static $mime;

    /**
     * @param $mailaddressee  receiver's address
     * @param $template mailtemplate
     * @param $data     mail data
     * @param $title   Mailbox title
     * @param null $File   Annex Address
     * @param string $Filename   Attachment Name
     * @param string $mime    Annex Address
     */
    public static function send($mailaddressee , $template , $data , $title , $File = null , $Filename='' , $mime='MIME')
    {
          if( config('mail.username') && config('mail.password')){
                self::$title = $title;

                self::$File = $File;

                self::$Filename = $Filename;

                self::$mime = $mime;

                self::$mailaddressee = $mailaddressee;

                $result = \Mail::send('email.' . $template , $data , function($message) use($data) 
                {
                    $message->from(config('mail.from.address'), config('mail.from.name'));

                    if(self::$File != null){
                        $message->attach(self::$File,['as' => self::$Filename, 'mime' => self::$mime]);
                    }
                    $message->to(self::$mailaddressee)->subject(self::$title);
                });
                if($result){
                    //写入日志
                    \Log::info('Message has been sent - '.$mailaddressee);
                    return ['status'=>1,'msg'=>'发送成功!'];
                }else{
                    \Log::error('Mail delivery failed - '.$mailaddressee);
                    return ['status'=>0,'msg'=>'发送失败!'];
                }
                
          }else{
                \Log::error('Mail delivery failed - '.$mailaddressee);
                return ['status'=>0,'msg'=>'发送失败！'];
          }
    }
}