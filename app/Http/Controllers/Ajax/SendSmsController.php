<?php

namespace App\Http\Controllers\Ajax;


use App\Repositories\SendSmsRepository;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
class SendSmsController extends Controller
{
    /**
     * 发送短信
     * @return json
     */
    public function doSend(){
        $SendSmsRepository = new SendSmsRepository(Input::all());
        $SendSmsRepository->contract();
        if (! $SendSmsRepository->passes()) {
            return response()->json($SendSmsRepository->wrap(), $SendSmsRepository->status);
            
        }
        return response('');
    }
}
