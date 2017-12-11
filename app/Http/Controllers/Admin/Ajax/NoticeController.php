<?php

namespace App\Http\Controllers\Admin\Ajax;

use App\Repositories\Admin\NoticeRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class NoticeController extends Controller
{


    /**
     * 发送系统通知
     * @return json
     */
    public function send(Request $request){
        $adminid = $request->security()->get('uid');
        $input = Input::all();
        $input['adminid'] = $adminid;
        $NoticeRepository = new NoticeRepository($input);
        $NoticeRepository ->dofunction = 'sendmsg';
        $NoticeRepository->contract();

        if (! $NoticeRepository->passes()) {
            return response()->json($NoticeRepository->wrap(), $NoticeRepository->status);
        }
        return response('');
    }
}
