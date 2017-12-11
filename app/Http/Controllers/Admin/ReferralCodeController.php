<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Admin\ReferralCodeRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReferralCodeController extends Controller
{
   
    public function codelist(Request $request){
        $ReferralCodeRepository = new ReferralCodeRepository($request->all());
        $ReferralCodeRepository ->dofunction = 'getList';
        $ReferralCodeRepository->contract();

        if (! $ReferralCodeRepository->passes()) {
            return response()->json($ReferralCodeRepository->wrap(), $ReferralCodeRepository->status);
        }
        return view('admin.referralcode')->with('data',$ReferralCodeRepository->doreturn);
    }
}
