<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

use App\Repositories\CVTemplateIndexRepository;

class CvController extends Controller
{
    /**
     * 简历模板H5发送模板url到邮箱
     */
    public function sendEmail(Request $request)
    {
        $input = $request->all();
        $respository = new CVTemplateIndexRepository($input);
        $respository->sendEmail();
        if(!$respository->passes()){
            return response()->json($respository->wrap(), $respository->status);
        }
        return response('');
    }
    
}
