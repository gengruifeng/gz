<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    //

    public function login(){

        return view('admin.login');
    }

    public function index(){

        return view('admin.index');
    }

    public function forbidden(){

        return view('admin.forbidden');
    }
}
