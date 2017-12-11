<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Admin\CertificateRepository;
use App\Repositories\Admin\CityRepository;
use App\Repositories\Admin\PositionRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TemplateDataController extends Controller
{
    //

    public function index(){

        $PositionRepository = new PositionRepository();
        $selectposition = $PositionRepository->getpname();

        $CertificateRepository = new CertificateRepository();
        $selectcertificate = $CertificateRepository->getpname();

        $CityRepository = new CityRepository();
        $selectcity = $CityRepository->getpareaname();

        return view('admin.templatedataindex')->with('selectposition', $selectposition)->with('selectcertificate', $selectcertificate)->with('selectcity', $selectcity);
    }
}
