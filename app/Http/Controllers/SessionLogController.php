<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class SessionLogController extends Controller
{
    public function index()
    {
        $sessions = DB::table('sessions')
            ->orderBy('last_activity', 'desc')
            ->paginate(15);

        $sessions->transform(function ($session) {

            try {
                $data = unserialize(Crypt::decrypt($session->payload));
            } catch (\Exception $e) {
                $data = [];
            }

            $session->user = isset($data['login_web']) ? $data['login_web'] : null;

            $session->last_activity_formatted = date('Y-m-d H:i:s', $session->last_activity);

            return $session;
        });

        return view('sessions.index', compact('sessions'));
    }
}
