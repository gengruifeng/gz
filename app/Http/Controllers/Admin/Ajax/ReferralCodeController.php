<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Repositories\Admin\ReferralCodeRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReferralCodeController extends Controller
{

    public function add(){
        $ReferralRepository = new ReferralCodeRepository();
        $ReferralRepository ->dofunction = 'addCode';
        $ReferralRepository->contract();
        if (! $ReferralRepository->passes()) {
            return response()->json($ReferralRepository->wrap(), $ReferralRepository->status);
        }
        return response('');
    }

    public function issued(Request $request){
        $permit = ['id'];
        $input = $request->only($permit);
        $ret = DB::table('referral_codes')->select('code')->where('issued',0)->whereIn('id', $input['id'])->orderby('id','desc')->get();

        DB::table('referral_codes')->where('issued',0)->whereIn('id',$input['id'])->update([
            'issued' => 1
        ]);
        $str = '';
        if(!empty($ret)){
            foreach ($ret as $k=>$v){
                $str.=$v->code."\r\n";
            }
        }
        $filename = date('Y-m-d H:i:s',time()).'邀请码.txt';
        header("Content-type: text/plain");
        header("Accept-Ranges: bytes");
        header("Content-Disposition: attachment; filename=".$filename);
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0" );
        header("Pragma: no-cache" );
        header("Expires: 0" );
        exit($str);
    }
}
