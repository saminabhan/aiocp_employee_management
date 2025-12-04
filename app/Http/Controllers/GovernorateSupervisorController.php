<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use Illuminate\Http\Request;

class GovernorateSupervisorController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user->role_id) {
            abort(403);
        }

        $query = User::where('role_id', function($q) {
            $q->select('id')->from('roles')->where('name', 'survey_supervisor');
        })->with(['city', 'mainWorkArea']);

        if ($user->role->name === 'governorate_manager') {
            $query->where('governorate_id', $user->governorate_id);
        } elseif ($user->role->name !== 'admin') {
            abort(403);
        }

        $supervisors = $query->get();

        return view('governorate.supervisors.index', compact('supervisors'));
    }

public function show($id)
{
    $user = auth()->user();

    if (!$user->role_id) {
        abort(403);
    }

    $supervisorQuery = User::where('id', $id)
        ->where('role_id', function($q) {
            $q->select('id')->from('roles')->where('name', 'survey_supervisor');
        });

    if ($user->role->name === 'governorate_manager') {
        $supervisorQuery->where('governorate_id', $user->governorate_id);
    } elseif ($user->role->name !== 'admin') {
        abort(403);
    }

    $supervisor = $supervisorQuery->firstOrFail();

    $mainWorkCode = $supervisor->main_work_area_code;

    if (!$mainWorkCode) {
        $teams = collect();
    } else {
$teams = Team::where('main_work_area_code', $mainWorkCode)
    ->get();
    }

    return view('governorate.supervisors.show', compact('supervisor', 'teams'));
}

}
