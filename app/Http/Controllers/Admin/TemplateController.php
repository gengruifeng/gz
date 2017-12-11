<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class TemplateController extends Controller
{

    /**
     * 模板列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function templatelist()
    {
        $professions = DB::table('cv_professions')->select('id','title')->get();

        return view('admin.templatelist')->with('professions', $professions);
    }

    /**
     * 添加页面
     * @return $this
     */
    public function add()
    {
        $professions = DB::table('cv_professions')->select('id','title')->get();
        return view('admin.templateedit')->with('professions', $professions);
    }

    /**
     * 简历模板编辑页
     * @param Request $request
     * @return mixed
     */
    public function edit(Request $request){
        $id = $request->id;
        $data = DB::table('cv_templates')->where('id',$id)->first();
        $professions = DB::table('cv_professions')->select('id','title')->get();
        if(!$data){
            return  Redirect::to('admin/template/list');
        }
        return view('admin.templateedit')->with('data',$data)->with('professions',$professions)->with('data',$data);
    }
}
