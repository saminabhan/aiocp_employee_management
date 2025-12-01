<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        return view('profile.index', compact('user', 'role'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

       $request->validate([
    'name' => 'required|string|max:255',
    'username' => 'required|string|max:255|unique:users,username,' . $user->id,
    'phone' => 'required|digits:10|unique:users,phone,' . $user->id,
        ], [
            'name.required' => 'الاسم مطلوب.',
            'username.required' => 'اسم المستخدم مطلوب.',
            'username.unique' => 'اسم المستخدم مستخدم في النظام.',
            'phone.required' => 'رقم الجوال مطلوب.',
            'phone.digits' => 'رقم الجوال يجب أن يكون مكوناً من 10 أرقام.',
            'phone.unique' => 'هذا الرقم مستخدم في النظام.',
        ]);


        $user->name = $request->name;
        $user->username = $request->username;
        $user->phone = $request->phone;

        $user->save();

        return redirect()->route('profile.index')->with('success', 'تم تحديث البيانات بنجاح');
    }

    public function changePassword()
    {
        return view('profile.change-password');
    }

    public function updatePassword(Request $request)
    {
       $request->validate([
    'current_password' => 'required',
    'password' => 'required|confirmed|min:6',
        ], [
            'current_password.required' => 'يرجى إدخال كلمة المرور الحالية.',
            
            'password.required' => 'يرجى إدخال كلمة المرور الجديدة.',
            'password.confirmed' => 'كلمة المرور الجديدة غير متطابقة مع التأكيد.',
            'password.min' => 'كلمة المرور يجب ألا تقل عن 6 أحرف.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.index')->with('success', 'تم تغيير كلمة المرور بنجاح');
    }

    public function show($id)
    {
    $user = User::with(['role'])->findOrFail($id);

    return view('support.users-profile', compact('user'));
    }
}
