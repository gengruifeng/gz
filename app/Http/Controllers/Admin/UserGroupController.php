<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserGroupController extends Controller
{
    //
    
    public function grouplist(){

        $data = DB::table('user_groups')->get();
        return view('admin.grouplist')->with('data',$data);
    }

    public function edit(Request $request){

        $data = DB::table('user_groups')->where('id',$request->id)->first();
        return view('admin.groupedit')->with('userData',$data);
    }

    public function add(){

        return view('admin.groupedit')->with('userData',[]);
    }

    public function editcon(Request $request){

        return view('admin.groupeditcon')->with('id',$request->id);
    }
}
