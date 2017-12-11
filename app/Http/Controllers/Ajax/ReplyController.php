<?php

namespace App\Http\Controllers\Ajax;

use App\Repositories\Reply;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\ReplyRepository;
use Illuminate\Support\Facades\Input;


class ReplyController extends Controller
{
    
    public function doAnswers(){
        $ReplyRepository =new ReplyRepository(Input::all());
        $ReplyRepository->contract();

        if (! $ReplyRepository->passes()) {
            return response()->json($ReplyRepository->wrap(), $ReplyRepository->status);
        }
        return response('');
    }
}
