<?php

namespace Smpl\Login\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TestController extends Controller
{
    public function index()
    {
        return view('vendor.test.index');
    }
}
