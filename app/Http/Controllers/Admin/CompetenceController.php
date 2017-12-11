<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Admin\CompetenceRepository;
use App\Utils\Tree;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CompetenceController extends Controller
{
    //
    public function competenceList(){
//        $competenceRepository = new CompetenceRepository();
//        $competenceRepository->dofunction = 'getCompetenceAll';
//        $competenceRepository->contract();
        return view('admin.competencelist');
    }
}
