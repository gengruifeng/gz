<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NoticeController extends Controller
{
    //
    
    public function index(){
        $data = DB::table('contents')
            ->select('created_at','content')
            ->orderBy('created_at','desc')
            ->get();
        return view('admin.noticeindex')->with('data',$data);
    }
}
