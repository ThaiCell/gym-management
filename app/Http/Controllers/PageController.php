<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PageController extends Controller
{
    /** Route: GET / */
    public function getRoot()
    {
            return view('user.home');
    }

    /** Route: GET /about */
    public function getAbout()
    {
            return view('user.about');
    }

}
