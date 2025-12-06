<?php

namespace App\Http\Controllers;

use App\Models\PageVisit;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        $visits = PageVisit::with('user')
                    ->orderByDesc('count')
                    ->paginate(20);

        return view('admin.analytics.index', compact('visits'));
    }
}
