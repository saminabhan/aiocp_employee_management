<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['permissions', 'role', 'governorate'])->latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        
    $governorates     = Constant::childrenOfId(14)->get();

        $roles_permissions = [];
        foreach ($roles as $role) {
            $roles_permissions[$role->id] = $role->permissions->pluck('id')->toArray();
        }

        return view('users.create', compact('roles', 'permissions', 'roles_permissions', 'governorates'));
    }

    public function store(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required',
            'phone' => 'required|unique:users,phone',
            'governorate_id' => 'nullable|exists:constants,id',
        ],[
            'name.required' => 'الرجاء إدخال اسم المستخدم',
            'username.required' => 'الرجاء إدخال اسم الدخول',
            'username.unique' => 'اسم الدخول مستخدم مسبقًا',
            'password.required' => 'الرجاء إدخال كلمة المرور',
            'phone.required' => 'الرجاء إدخال رقم الجوال',
            'phone.unique' => 'رقم الجوال مستخدم مسبقًا',
            'governorate_id.exists' => 'المحافظة المحددة غير موجودة',
        ]);

        if ($req->role_id === "custom") {
            $user = User::create([
                'name' => $req->name,
                'username' => $req->username,
                'phone' => $req->phone,
                'password' => bcrypt($req->password),
                'role_id' => null,
                'governorate_id' => $req->governorate_id,
            ]);

            if ($req->permissions) {
                $user->permissions()->sync($req->permissions);
            }
        } else {
            $user = User::create([
                'name' => $req->name,
                'username' => $req->username,
                'phone' => $req->phone,
                'password' => bcrypt($req->password),
                'role_id' => $req->role_id,
                'governorate_id' => $req->governorate_id,
            ]);

            if ($req->role_id) {
                $role = Role::find($req->role_id);
                $user->permissions()->sync($role->permissions->pluck('id')->toArray());
            }
        }

        return redirect()->route('users.index')->with('success', 'تم إضافة المستخدم بنجاح');
    }

    public function edit($id)
    {
        $user = User::with('permissions')->findOrFail($id);
        $roles = Role::all();
        $permissions = Permission::orderBy('category')->get();
        $governorates = Constant::childrenOfId(14)->get();

        return view('users.edit', compact('user', 'roles', 'permissions', 'governorates'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $user->id,
            'role_id' => 'nullable',
            'governorate_id' => 'nullable|exists:constants,id',
        ]);

        $roleId = ($request->role_id === "custom" || $request->role_id === null)
            ? null
            : intval($request->role_id);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'role_id' => $roleId,
            'governorate_id' => $request->governorate_id,
        ]);

        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        $user->permissions()->sync($request->permissions ?? []);

        return redirect()->route('users.index')->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف المستخدم');
    }

    public function getRolePermissions($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);
        return response()->json($role->permissions->pluck('id'));
    }

    public function show($id)
    {
        $user = User::with([
            'role.permissions',
            'permissions',
            'governorate'
        ])->findOrFail($id);

        return view('users.show', compact('user'));
    }
}