<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Repositories\Admin\TemplateRepository;
use App\Utils\Upload;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class TemplateController extends Controller
{
    /**
     * 获取模板列表
     * @return json
     */
    public function getList(){
        $TemplateRepository = new TemplateRepository(Input::all());
        $TemplateRepository ->dofunction = 'getList';
        $TemplateRepository->contract();

        if (! $TemplateRepository->passes()) {
            return response()->json($TemplateRepository->wrap(), $TemplateRepository->status);
        }
        return response($TemplateRepository->data);
    }


    /**
     * 简历模板上传接口
     */
    public function upload(Request $request){

        $input = $request->only(['type']);
        $filename = Upload::uploadTemplate('templates/'.$input['type']);
        return response(['filename'=>$filename]);
    }

    /**
     * 添加简历模板
     * @return Response
     */
    public function add(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $TemplateRepository = new TemplateRepository($input);
        $TemplateRepository ->dofunction = 'add';
        $TemplateRepository->contract();

        if (! $TemplateRepository->passes()) {
            return response()->json($TemplateRepository->wrap(), $TemplateRepository->status);
        }
        return response('');
    }

    /**
     * 编辑简历模板
     * @return json
     */
    public function edit(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $TemplateRepository = new TemplateRepository($input);
        $TemplateRepository ->dofunction = 'edit';
        $TemplateRepository->contract();

        if (! $TemplateRepository->passes()) {
            return response()->json($TemplateRepository->wrap(), $TemplateRepository->status);
        }
        return response('');
    }

    /**
     * 删除简历模板
     * @return Response
     */
    public function del(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $TemplateRepository = new TemplateRepository($input);
        $TemplateRepository ->dofunction = 'del';
        $TemplateRepository->contract();

        if (! $TemplateRepository->passes()) {
            return response()->json($TemplateRepository->wrap(), $TemplateRepository->status);
        }
        return response('');
    }

}
