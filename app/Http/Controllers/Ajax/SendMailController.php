<?php

namespace App\Http\Controllers\Ajax;

use App\Repositories\SendMailRepository;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
class SendMailController extends Controller
{
    /**
     * 发送邮件
     * @return json
     */
    public function doSend(){
        $SendMailRepository = new SendMailRepository(Input::all());
        $SendMailRepository->contract();
        if (! $SendMailRepository->passes()) {
            return response()->json($SendMailRepository->wrap(), $SendMailRepository->status);
        }
        return response('');
    }
}
