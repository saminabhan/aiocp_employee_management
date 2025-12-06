<?php

namespace App\Http\Controllers;

use App\Models\UserActivity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = UserActivity::with('user')->latest()->paginate(30);
        return view('admin.activities.index', compact('activities'));
    }
}
