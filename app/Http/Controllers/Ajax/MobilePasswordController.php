<?php

namespace App\Http\Controllers\Ajax;

use App\Repositories\MobilePasswordRepository;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
class MobilePasswordController extends Controller
{
    //

    /**
     * 短信修改密码
     * @return json
     */
    public function doUp(){
        $MobilePasswordRepository = new MobilePasswordRepository(Input::all());
        $MobilePasswordRepository->contract();
        if (! $MobilePasswordRepository->passes()) {
            return response()->json($MobilePasswordRepository->wrap(), $MobilePasswordRepository->status);
        }
        return response('');
    }
}
