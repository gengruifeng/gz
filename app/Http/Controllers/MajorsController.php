<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\SearchSchoolsRepository;
use App\Repositories\SearchMajorsRepository;

class MajorsController extends Controller
{
    /**
     * Search Majors
     *
     * @param void
     *
     * @return void
     */
    public function query(Request $request)
    {
        $permit = ['q'];
        $input = $request->only($permit);
        $repo = new SearchMajorsRepository($input);
        $repo->contract();
        return response()->json($repo->biz);
    }
    /**
     * Search schools
     *
     * @param void
     *
     * @return void
     */
    public function schoolquery(Request $request)
    {
        $permit = ['q','cityid'];
        $input = $request->only($permit);
        $repo = new SearchSchoolsRepository($input);
        $repo->contract();
        return response()->json($repo->biz);
    }

}
