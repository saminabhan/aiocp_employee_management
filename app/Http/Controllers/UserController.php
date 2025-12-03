<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Constant;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['permissions', 'role', 'governorate'])->latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
{
$roles = Role::where('name', '!=', 'field_engineer')->get();
    $permissions = Permission::all();

    $governorates = Constant::childrenOfId(14)->get();
    $cities = Constant::childrenOfId(55)->get();
$usedAreas = User::whereNotNull('main_work_area_code')
                ->pluck('main_work_area_code')
                ->toArray();

$mainWorkAreas = Constant::childrenOfId(55)
                ->whereNotIn('id', $usedAreas)
                ->get();

    $roles_permissions = [];
    foreach ($roles as $role) {
        $roles_permissions[$role->id] = $role->permissions->pluck('id')->toArray();
    }

    return view('users.create', compact('roles', 'permissions', 'roles_permissions', 'governorates', 'mainWorkAreas' ,'cities'));
}
public function getCities($governorateId)
{
    $cities = Constant::where('parent', $governorateId)->get();
    return response()->json($cities);
}
public function getWorkAreas($govId)
{
    $userId = request()->query('user_id');
    
    $usedAreas = User::whereNotNull('main_work_area_code')
                     ->when($userId, function($query) use ($userId) {
                         $query->where('id', '!=', $userId);
                     })
                     ->pluck('main_work_area_code')
                     ->filter()
                     ->unique()
                     ->toArray();

    $areas = Constant::where('governorate_id', $govId)
                     ->whereNotIn('id', $usedAreas)
                     ->get(['id', 'name']);

    return response()->json($areas);
}

public function store(Request $req)
{
    $req->validate([
        'name' => 'required',
        'username' => 'required|unique:users,username',
        'phone' => 'required|unique:users,phone',
        'governorate_id' => 'nullable|exists:constants,id',
        'city_id' => 'nullable|exists:constants,id',
        'main_work_area_code' => 'nullable|exists:constants,id',
    ],[
        'name.required' => 'الرجاء إدخال اسم المستخدم',
        'username.required' => 'الرجاء إدخال اسم الدخول',
        'username.unique' => 'اسم الدخول مستخدم مسبقًا',
        'phone.required' => 'الرجاء إدخال رقم الجوال',
        'phone.unique' => 'رقم الجوال مستخدم مسبقًا',
        'governorate_id.exists' => 'المحافظة المحددة غير موجودة',
        'city_id.exists' => 'المدينة المحددة غير موجودة',
        'main_work_area_code.exists' => 'منطقة العمل غير صحيحة',
    ]);

    $role = Role::find($req->role_id);

    if ($role && $role->name === 'survey_supervisor') {

        $req->validate([
            'governorate_id' => 'required|exists:constants,id',
            'city_id' => 'required|exists:constants,id',
            'main_work_area_code' => 'required|exists:constants,id',
        ],[
            'governorate_id.required' => 'الرجاء اختيار المحافظة',
            'city_id.required' => 'الرجاء اختيار المدينة',
            'main_work_area_code.required' => 'الرجاء اختيار كود منطقة العمل الرئيسي',
        ]);
    }

    $rawPassword = Str::random(6);

    $data = [
        'name' => $req->name,
        'username' => $req->username,
        'phone' => $req->phone,
        'password' => bcrypt($rawPassword),
        'role_id' => $req->role_id != "custom" ? $req->role_id : null,
        'governorate_id' => $req->governorate_id,
        'city_id' => $req->city_id,
        'main_work_area_code' => $req->main_work_area_code,
    ];

    $user = User::create($data);

    if ($req->role_id === "custom") {
        if ($req->permissions) {
            $user->permissions()->sync($req->permissions);
        }
    } else {
        if ($req->role_id) {
            $role = Role::find($req->role_id);
            $user->permissions()->sync($role->permissions->pluck('id')->toArray());
        }
    }

    $message =
        "مرحبا {$req->name}، تم إنشاء حسابك بنجاح.\n" .
        "اسم المستخدم: {$req->username}\n" .
        "كلمة المرور: {$rawPassword}\n" .
        "رابط تسجيل الدخول:\n" . url('/login') . "\n\n" .
        "يرجى تغيير كلمة المرور عند أول دخول.";

    $mobile = $req->phone;
    if (substr($mobile, 0, 1) === '0') {
        $mobile = '972' . substr($mobile, 1);
    }

    $smsService = new SmsService();
    $smsService->send(
        $mobile,
        $message,
        null,
        auth()->id()
    );

    return redirect()->route('users.index')->with('success', 'تم إضافة المستخدم بنجاح');
}


public function edit($id)
{
    $user = User::with('permissions')->findOrFail($id);

    $roles = Role::where('name', '!=', 'field_engineer')->get();
    $permissions = Permission::orderBy('category')->get();

    $governorates = Constant::childrenOfId(14)->get();

    $cities = Constant::childrenOfId(55)->get();

    $usedAreas = User::whereNotNull('main_work_area_code')
        ->where('id', '!=', $user->id)
        ->pluck('main_work_area_code')
        ->toArray();

    $work_areas = Constant::childrenOfId(55)
        ->where(function ($q) use ($usedAreas, $user) {
            $q->whereNotIn('id', $usedAreas)
              ->orWhere('id', $user->main_work_area_code);
        })
        ->get();

    $userPermissions = $user->permissions->pluck('id')->toArray();

    return view('users.edit', compact(
        'user',
        'roles',
        'permissions',
        'governorates',
        'cities',
        'work_areas',
        'userPermissions'
    ));
}

  public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'username' => 'required|unique:users,username,' . $user->id,
        'role_id' => 'nullable',
        'governorate_id' => 'nullable|exists:constants,id',
        'city_id' => 'nullable|exists:constants,id',
        'main_work_area_code' => 'nullable|exists:constants,id',
    ]);

    $roleId = ($request->role_id === "custom" || $request->role_id === null)
        ? null
        : intval($request->role_id);

    $user->update([
        'name' => $request->name,
        'username' => $request->username,
        'role_id' => $roleId,
        'governorate_id' => $request->governorate_id,
        'city_id' => $request->city_id,
        'main_work_area_code' => $request->main_work_area_code,
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