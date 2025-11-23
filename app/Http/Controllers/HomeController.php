<?php

namespace App\Http\Controllers;

use App\Models\Engineer;
use App\Models\Team;
use Illuminate\Http\Request;

class HomeController extends Controller
{
       public function index()
    {
            $user = auth()->user();

            if ($user->role->name === 'governorate_manager') {

                $engineer_count = Engineer::where('work_governorate_id', $user->governorate_id)->count();
                $team_count = Team::where('governorate_id', $user->governorate_id)->count();

            } else {
                
                $engineer_count = Engineer::count();
                $team_count = Team::count();
            }

            return view('main.index', compact('engineer_count' ,'team_count'));
        }


}
