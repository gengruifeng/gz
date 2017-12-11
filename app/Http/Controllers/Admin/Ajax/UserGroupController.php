<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Repositories\Admin\UserGroupRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class UserGroupController extends Controller
{
    //
    public function edit(){
        $UserGroupRepository = new UserGroupRepository(Input::all());
        $UserGroupRepository ->dofunction = 'edit';
        $UserGroupRepository->contract();

        if (! $UserGroupRepository->passes()) {
            return response()->json($UserGroupRepository->wrap(), $UserGroupRepository->status);
        }
        return response('');
    }

    public function add(){
        $UserGroupRepository = new UserGroupRepository(Input::all());
        $UserGroupRepository ->dofunction = 'add';
        $UserGroupRepository->contract();

        if (! $UserGroupRepository->passes()) {
            return response()->json($UserGroupRepository->wrap(), $UserGroupRepository->status);
        }
        return response('');
    }

    public function getcon(){
        $UserGroupRepository = new UserGroupRepository(Input::all());
        $UserGroupRepository ->dofunction = 'getcon';
        $UserGroupRepository->contract();

        if (! $UserGroupRepository->passes()) {
            return response()->json($UserGroupRepository->wrap(), $UserGroupRepository->status);
        }
        return response()->json($UserGroupRepository->returnData, 200);
    }

    public function saveUserCon(){
        $UserGroupRepository = new UserGroupRepository(Input::all());
        $UserGroupRepository ->dofunction = 'saveUserCon';
        $UserGroupRepository->contract();

        if (! $UserGroupRepository->passes()) {
            return response()->json($UserGroupRepository->wrap(), $UserGroupRepository->status);
        }
        return response('');
    }
}
