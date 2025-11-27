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
    $role = $user->role->name ?? null;

    $engineer_count = null;
    $team_count     = null;

    if ($role === null && $user->hasPermission('teams.view')) {
        $team_count     = Team::count();
        return view('main.index', compact('engineer_count', 'team_count'));
    }
    if ($role === null && $user->hasPermission('engineers.view')) {
        $engineer_count = Engineer::count();
        return view('main.index', compact('engineer_count'));
    }

    switch ($role) {

        case 'governorate_manager':
            $engineer_count = Engineer::where('work_governorate_id', $user->governorate_id)->count();
            $team_count     = Team::where('governorate_id', $user->governorate_id)->count();
            break;

        case 'survey_supervisor':
            $engineer_count = Engineer::where('main_work_area_code', $user->main_work_area_code)->count();
            $team_count     = Team::where('main_work_area_code', $user->main_work_area_code)->count();
            break;

        case 'field_supervisor':
            $engineer_count = Engineer::where('work_governorate_id', $user->governorate_id)->count();
            $team_count     = Team::where('governorate_id', $user->governorate_id)->count();
            break;

        case 'system_admin':
        case 'admin':
            $engineer_count = Engineer::count();
            $team_count     = Team::count();
            break;

        default:
            break;
    }

    return view('main.index', compact('engineer_count', 'team_count'));
}

}
