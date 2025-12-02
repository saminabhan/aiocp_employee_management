<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;

class SessionLogController extends Controller
{
    public function index()
    {
        if (!auth()->check() || auth()->user()->role->name !== 'admin') {
            abort(403, 'غير مسموح بالوصول');
        }

        $sessions = DB::table('sessions')
            ->orderBy('last_activity', 'desc')
            ->paginate(15);

        $sessions->transform(function ($session) {

            $session->user = User::find($session->user_id);

            $session->last_activity_formatted = date('Y-m-d H:i:s', $session->last_activity);

            return $session;
        });

        return view('sessions.index', compact('sessions'));
    }
}
