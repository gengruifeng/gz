<?php

namespace App\Http\Controllers\Ajax;

use App\Repositories\MailPasswordRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
class MailPasswordController extends Controller
{
    /**
     * 邮箱修改密码
     * @return json
     */
    public function doUp(){
        $MailPasswordRepository = new MailPasswordRepository(Input::all());
        $MailPasswordRepository->contract();
        if (! $MailPasswordRepository->passes()) {
            return response()->json($MailPasswordRepository->wrap(), $MailPasswordRepository->status);
        }
        return response('');
    }
}
