<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class WelcomeController extends Controller
{
    /**
     * The Homepage
     *
     * @param Request
     *
     * @return View
     */
    public function index(Request $request)
    {
        return view('welcome');
    }
}
