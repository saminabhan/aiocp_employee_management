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

    $query = User::where('role_id', function($q) {
        $q->select('id')
          ->from('roles')
          ->where('name', 'survey_supervisor');
    })
    ->with(['city', 'mainWorkArea']);

    if ($user->role->name === 'governorate_manager') {
        $query->where('governorate_id', $user->governorate_id);
    }

    $supervisors = $query->get();

    return view('governorate.supervisors.index', compact('supervisors'));
}



public function show($id)
{
    $user = auth()->user();

    $supervisor = User::where('id', $id)
        ->where('role_id', function($q) {
            $q->select('id')->from('roles')->where('name', 'survey_supervisor');
        })
        ->where('governorate_id', $user->governorate_id)
        ->firstOrFail();

    $teams = Team::where('supervisor_id', $supervisor->id)
        ->with('members')
        ->get();

    return view('governorate.supervisors.show', compact('supervisor', 'teams'));
}
}
