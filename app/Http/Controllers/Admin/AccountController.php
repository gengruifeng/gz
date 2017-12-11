<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class AccountController extends Controller
{
    //

    public function userList(){
        return view('admin.accountlist');
    }

    //

    public function edit(Request $request){
        $uid = $request->id;

        $userData = DB::table('users')->select('id','display_name','mobile','email','group_id','disabled','gender')->where('id',$uid)->first();

        $con = DB::table('user_groups')->select('id','name')->get();

        if(!$userData){
            return  Redirect::to('admin/account/list');
        }
        return view('admin.accountedit')->with('userData',$userData)->with('con',$con);
    }
}
