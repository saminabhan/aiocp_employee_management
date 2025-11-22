<?php

namespace App\Http\Controllers;

use App\Models\Engineer;
use Illuminate\Http\Request;

class HomeController extends Controller
{
       public function index()
    {
            $user = auth()->user();

            if ($user->role->name === 'governorate_manager') {

                $engineer_count = Engineer::where('home_governorate_id', $user->governorate_id)->count();

            } else {
                
                $engineer_count = Engineer::count();
            }

            return view('main.index', compact('engineer_count'));
        }


}
