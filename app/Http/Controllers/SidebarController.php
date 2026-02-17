<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SidebarController extends Controller
{
    public function index(){
        return view('test.index');
    }

    public function test(){
        return view('test.index');
    }
}
