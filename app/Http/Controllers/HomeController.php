<?php

namespace App\Http\Controllers;

use App\Models\Engineer;
use Illuminate\Http\Request;

class HomeController extends Controller
{
       public function index()
    {
        $engineer_count = Engineer::count();
        return view('main.index', compact('engineer_count'));
    }

}
