<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Repositories\Admin\CompetenceRepository;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CompetenceController extends Controller
{
    //

    public function getList(){
        $CompetenceRepository = new CompetenceRepository(Input::all());
        $CompetenceRepository ->dofunction = 'getCompetenceAll';
        $CompetenceRepository->contract();

        if (! $CompetenceRepository->passes()) {
            return response()->json($CompetenceRepository->wrap(), $CompetenceRepository->status);
        }
        return response()->json($CompetenceRepository->returnData, 200);
    }

    /**
     * 添加权限
     * @return Response
     */
    public function add(){
        $CompetenceRepository = new CompetenceRepository(Input::all());
        $CompetenceRepository ->dofunction = 'add';
        $CompetenceRepository->contract();

        if (! $CompetenceRepository->passes()) {
            return response()->json($CompetenceRepository->wrap(), $CompetenceRepository->status);
        }
        return response('');
    }

    /**
     * 获取一条权限信息
     * @return Response
     */
    public function getone(){
        $CompetenceRepository = new CompetenceRepository(Input::all());
        $CompetenceRepository ->dofunction = 'getone';
        $CompetenceRepository->contract();

        if (! $CompetenceRepository->passes()) {
            return response()->json($CompetenceRepository->wrap(), $CompetenceRepository->status);
        }
        return response()->json($CompetenceRepository->returnData, 200);
    }

    /**
     * 添加权限
     * @return Response
     */
    public function edit(){
        $CompetenceRepository = new CompetenceRepository(Input::all());
        $CompetenceRepository ->dofunction = 'edit';
        $CompetenceRepository->contract();

        if (! $CompetenceRepository->passes()) {
            return response()->json($CompetenceRepository->wrap(), $CompetenceRepository->status);
        }
        return response('');
    }

    /**
     * 添加权限
     * @return Response
     */
    public function del(){
        $CompetenceRepository = new CompetenceRepository(Input::all());
        $CompetenceRepository ->dofunction = 'del';
        $CompetenceRepository->contract();

        if (! $CompetenceRepository->passes()) {
            return response()->json($CompetenceRepository->wrap(), $CompetenceRepository->status);
        }
        return response('');
    }
}
