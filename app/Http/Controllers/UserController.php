<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // عرض جميع المستخدمين
    public function index()
    {
        $users = User::with('permissions')->latest()->get();
        return view('users.index', compact('users'));
    }

public function create()
{
    $roles = Role::all();
    $permissions = Permission::all();

    // تجهيز بيانات للأجاكس
    $roles_permissions = [];
    foreach ($roles as $role) {
        $roles_permissions[$role->id] = $role->permissions->pluck('id')->toArray();
    }

    return view('users.create', compact('roles', 'permissions', 'roles_permissions'));
}




public function store(Request $req)
{
    $req->validate([
    'name' => 'required',
    'username' => 'required|unique:users,username',
    'password' => 'required',
    'phone' => 'required|unique:users,phone',
],[
    'name.required' => 'الرجاء إدخال اسم المستخدم',
    'username.required' => 'الرجاء إدخال اسم الدخول',
    'username.unique'   => 'اسم الدخول مستخدم مسبقًا',
    'password.required' => 'الرجاء إدخال كلمة المرور',
    'phone.required'    => 'الرجاء إدخال رقم الجوال',
    'phone.unique'      => 'رقم الجوال مستخدم مسبقًا',
]);


    // الحالة 1: الدور مخصص → لا ننشئ دور ولا نخزن "مخصص" في جدول roles
    if ($req->role_id === "custom") {

        // ننشئ المستخدم بدون role_id
        $user = User::create([
            'name'       => $req->name,
            'username'   => $req->username,
            'phone'      => $req->phone,
            'password'   => bcrypt($req->password),
            'role_id'    => null,
        ]);

        // نخزن صلاحياته مباشرة
        if ($req->permissions) {
            $user->permissions()->sync($req->permissions);
        }

    } else {

        // الحالة 2: اختيار دور عادي
        $user = User::create([
            'name'       => $req->name,
            'username'   => $req->username,
            'phone'      => $req->phone,
            'password'   => bcrypt($req->password),
            'role_id'    => $req->role_id,
        ]);

        // ننسخ صلاحيات الدور للمستخدم (اختياري)
        if ($req->role_id) {
            $role = Role::find($req->role_id);
            $user->permissions()->sync($role->permissions->pluck('id')->toArray());
        }
    }

    return redirect()->route('users.index')->with('success', 'تم إضافة المستخدم بنجاح');
}




 
    // صفحة تعديل مستخدم
    public function edit($id)
    {
        $user = User::with('permissions')->findOrFail($id);
        $roles = Role::all();
        $permissions = Permission::orderBy('category')->get();

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    // تحديث المستخدم
public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name'      => 'required',
        'username'  => 'required|unique:users,username,' . $user->id,
        // مهم جداً — ما بنعمل exists هون
        'role_id'   => 'nullable',
    ]);

    // 🔥 حل الخطأ:
    // إذا اختار "custom" نحولها لـ null قبل عملية التحديث
    $roleId = ($request->role_id === "custom" || $request->role_id === null)
        ? null
        : intval($request->role_id);

    $user->update([
        'name'      => $request->name,
        'username'  => $request->username,
        'role_id'   => $roleId,   // الآن القيمة صحيحة
    ]);

    // تحديث كلمة المرور لو موجودة
    if ($request->password) {
        $user->update([
            'password' => Hash::make($request->password)
        ]);
    }

    // تحديث الصلاحيات (تعمل بشكل مستقل عن الدور)
    $user->permissions()->sync($request->permissions ?? []);

    return redirect()->route('users.index')->with('success', 'تم تحديث المستخدم بنجاح');
}

    // حذف المستخدم
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف المستخدم');
    }

    // جلب صلاحيات الدور عبر AJAX
    public function getRolePermissions($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);
        return response()->json($role->permissions->pluck('id'));
    }

public function show($id)
{
    $user = User::with([
        'role.permissions',
        'permissions'
    ])->findOrFail($id);

    return view('users.show', compact('user'));
}


}
