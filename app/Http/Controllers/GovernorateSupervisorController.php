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

    // الاستعلام الأساسي
    $query = User::where('role_id', function($q) {
        $q->select('id')
          ->from('roles')
          ->where('name', 'survey_supervisor');
    })
    ->with(['city', 'mainWorkArea']);

    // إذا كان مدير محافظة → فلترة فقط نفس المحافظة
    if ($user->role->name === 'governorate_manager') {
        $query->where('governorate_id', $user->governorate_id);
    }

    // المدير العام يشوف الكل بدون فلتر
    // لذلك لا نضيف أي شرط إضافي للأدمين

    $supervisors = $query->get();

    return view('governorate.supervisors.index', compact('supervisors'));
}



public function show($id)
{
    $user = auth()->user(); // مدير المحافظة الحالي

    // المشرف المطلوب
    $supervisor = User::where('id', $id)
        ->where('role_id', function($q) {
            $q->select('id')->from('roles')->where('name', 'survey_supervisor');
        })
        ->where('governorate_id', $user->governorate_id) // يجب أن يكون من نفس المحافظة
        ->firstOrFail(); // يمنع الدخول لغير المسموح

    // الفرق التابعة لهذا المشرف
    $teams = Team::where('supervisor_id', $supervisor->id)
        ->with('members')
        ->get();

    return view('governorate.supervisors.show', compact('supervisor', 'teams'));
}
}
