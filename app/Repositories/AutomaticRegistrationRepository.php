<?php

namespace App\Repositories;

use DB;
use Log;
use Illuminate\Support\Facades\Hash;

class AutomaticRegistrationRepository extends Repository implements RepositoryInterface
{
    /**
     * 文章列表
     *
     * {@inheritdoc}
     */
    public function contract()
    {
    	$this->add();
    }

    /**
     * Wrap the contract result to JSON object.
     *
     * {@inheritdoc}
     */
    public function wrap()
    {
    }

    //批量注册
    public function add(){
        echo "\r\n".date('H:i:s')."开始;";
        $filename = resource_path()."/sql/twenty.txt";
        $handle = fopen($filename, "r");
        $count = 0;
        $addsql = [];
        $time = date('Y-m-d H:i:s', time());
        while (($line = fgets($handle)) !== false) {
            $count++;
            $arr = explode("\t",$line);
            $addsql[] = [
                'email' => $arr[0],
                'passcode' => Hash::make(trim($arr[1])),
                'avatar' => 'head.png',
                'email_verified' => 1,
                'created_at' => $time,
                'updated_at' => $time,
            ];
            if($count%500 == 0){
                //数据入库
                $re = DB::table('users')->insert($addsql);
                if(!$re){
                    Log::error("数据入库失败：".json_encode($addsql));
                }
                $addsql = [];
                sleep(2);
            }
        }
        if($count%500 > 0){
            //数据入库
            $re = DB::table('users')->insert($addsql);
            if(!$re){
                Log::error("数据入库失败：".json_encode($addsql));
            }
        }
        fclose($handle);
        echo "\r\n".date('H:i:s')."结束,共新增".$count."条记录;";
    }

}
