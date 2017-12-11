<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Repositories\Admin\TagsRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Utils\Upload;

class TagsController extends Controller
{
    //
    /**
     * 获取标签列表
     * @return json
     */
    public function getList(){
        $TagsRepository = new TagsRepository(Input::all());
        $TagsRepository ->dofunction = 'getList';
        $TagsRepository->contract();

        if (! $TagsRepository->passes()) {
            return response()->json($TagsRepository->wrap(), $TagsRepository->status);
        }
        return response($TagsRepository->data);
    }

    /**
     * 添加标签
     * @return Response
     */
    public function add(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $TagsRepository = new TagsRepository($input);
        $TagsRepository ->dofunction = 'add';
        $TagsRepository->contract();

        if (! $TagsRepository->passes()) {
            return response()->json($TagsRepository->wrap(), $TagsRepository->status);
        }
        return response('');
    }

    /**
     * 编辑标签
     * @return Response
     */
    public function edit(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $TagsRepository = new TagsRepository($input);
        $TagsRepository ->dofunction = 'edit';
        $TagsRepository->contract();

        if (! $TagsRepository->passes()) {
            return response()->json($TagsRepository->wrap(), $TagsRepository->status);
        }
        return response('');
    }

    /**
     * 删除标签
     * @return Response
     */
    public function del(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $TagsRepository = new TagsRepository($input);
        $TagsRepository ->dofunction = 'del';
        $TagsRepository->contract();

        if (! $TagsRepository->passes()) {
            return response()->json($TagsRepository->wrap(), $TagsRepository->status);
        }
        return response('');
    }

    /**
     * 添加擅长标签
     */
    public function addCategories(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $TagsRepository = new TagsRepository($input);
        $TagsRepository ->dofunction = 'addCategories';
        $TagsRepository->contract();

        if (! $TagsRepository->passes()) {
            return response()->json($TagsRepository->wrap(), $TagsRepository->status);
        }
        return response('');
    }

    /**
     * 删除擅长标签
     */
    public function delCategories(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $TagsRepository = new TagsRepository($input);
        $TagsRepository ->dofunction = 'delCategories';
        $TagsRepository->contract();

        if (! $TagsRepository->passes()) {
            return response()->json($TagsRepository->wrap(), $TagsRepository->status);
        }
        return response('');
    }


    /**
     * 添加领域图片
     * @return Response
     */
    public function addCategoriesPic(){
        $filename = Upload::uploadCategory('categories');
        return response(['filename'=>$filename]);
    }

    /**
     * 添加领域
     * @return Response
     */
    public function addCategoriesInfo(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $TagsRepository = new TagsRepository($input);
        $TagsRepository ->dofunction = 'addCategoriesInfo';
        $TagsRepository->contract();

        if (! $TagsRepository->passes()) {
            return response()->json($TagsRepository->wrap(), $TagsRepository->status);
        }
        return response('');
    }

    /**
     * 编辑领域
     * @return Response
     */
    public function editCategoriesInfo(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $TagsRepository = new TagsRepository($input);
        $TagsRepository ->dofunction = 'editCategoriesInfo';
        $TagsRepository->contract();

        if (! $TagsRepository->passes()) {
            return response()->json($TagsRepository->wrap(), $TagsRepository->status);
        }
        return response('');
    }

    /**
     * 删除领域
     * @return Response
     */
    public function delCategoriesInfo(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $TagsRepository = new TagsRepository($input);
        $TagsRepository ->dofunction = 'delCategoriesInfo';
        $TagsRepository->contract();

        if (! $TagsRepository->passes()) {
            return response()->json($TagsRepository->wrap(), $TagsRepository->status);
        }
        return response('');
    }
}
