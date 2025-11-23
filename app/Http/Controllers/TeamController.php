<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Constant;
use App\Models\Engineer;
use Illuminate\Http\Request;
use App\Http\Requests\TeamRequest;

class TeamController extends Controller
{
    /**
     * عرض قائمة الفرق
     */
    public function index()
    {
        $user = auth()->user();
        
        $query = Team::with('governorate');
        
        // إذا كان مدير محافظة، يعرض فرق محافظته فقط
        if ($user->role && $user->role->name === 'governorate_manager') {
            $query->where('governorate_id', $user->governorate_id);
        }
        
        $teams = $query->latest()->paginate(10);

        return view('teams.index', compact('teams'));
    }

    /**
     * عرض صفحة إضافة فريق جديد
     */
    public function create()
    {
        $user = auth()->user();

        $governorates = collect();
        $engineers = collect();

        // Admin - يختار المحافظة ثم يحمل المهندسين عبر Ajax
        if ($user->role && $user->role->name === 'admin') {
            $governorates = Constant::childrenOfId(14)->get();
        }
        // Governorate Manager - يحمل مهندسي محافظته المتاحين مباشرة
        else if ($user->role && $user->role->name === 'governorate_manager') {
            $governorateId = $user->governorate_id;
            
            // جلب المهندسين غير المرتبطين بأي فريق
            $engineers = $this->getAvailableEngineers($governorateId);
        }
        else {
            abort(403, 'ليس لديك صلاحية لإضافة فرق');
        }

        return view('teams.create', compact('governorates', 'engineers', 'user'));
    }

    /**
     * حفظ فريق جديد
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // القواعد الأساسية
        $rules = [
            'name' => 'required|string|max:255',
            'engineer_ids' => 'nullable|array',
            'engineer_ids.*' => 'exists:engineers,id',
            'is_active' => 'nullable|boolean',
        ];

        // إذا كان Admin، المحافظة إجبارية
        if ($user->role && $user->role->name === 'admin') {
            $rules['governorate_id'] = 'required|exists:constants,id';
        }

        $validated = $request->validate($rules);

        // تحديد المحافظة
        if ($user->role && $user->role->name === 'governorate_manager') {
            $validated['governorate_id'] = $user->governorate_id;
        }

        // تحويل المهندسين لـ JSON
        $validated['engineer_ids'] = $request->engineer_ids ?? [];
        $validated['is_active'] = $request->has('is_active');

        Team::create($validated);

        return redirect()->route('teams.index')
                        ->with('success', 'تم إنشاء الفريق بنجاح');
    }

    /**
     * عرض تفاصيل فريق
     */
    public function show(Team $team)
    {
        $team->load('governorate');
        $engineers = $team->engineers();

        return view('teams.show', compact('team', 'engineers'));
    }

    /**
     * عرض صفحة تعديل فريق
     */
    public function edit(Team $team)
    {
        $user = auth()->user();
        
        $governorates = collect();
        $engineers = collect();

        // Admin
        if ($user->role && $user->role->name === 'admin') {
            $governorates = Constant::childrenOfId(14)->get();
            
            // جلب المهندسين المتاحين + مهندسي هذا الفريق
            $engineers = $this->getAvailableEngineers($team->governorate_id, $team->id);
        }
        // Governorate Manager
        else if ($user->role && $user->role->name === 'governorate_manager') {
            // جلب المهندسين المتاحين + مهندسي هذا الفريق
            $engineers = $this->getAvailableEngineers($user->governorate_id, $team->id);
        }

        return view('teams.edit', compact('team', 'governorates', 'engineers', 'user'));
    }

    /**
     * تحديث بيانات فريق
     */
public function update(Request $request, Team $team)
{
    $user = auth()->user();

    $rules = [
        'name' => 'required|string|max:255',
        'engineer_ids' => 'nullable|array',
        'engineer_ids.*' => 'nullable|exists:engineers,id',  // أضف nullable هنا
        'is_active' => 'nullable|boolean',
    ];

    if ($user->role && $user->role->name === 'admin') {
        $rules['governorate_id'] = 'required|exists:constants,id';
    }

    $validated = $request->validate($rules);

    if ($user->role && $user->role->name === 'governorate_manager') {
        $validated['governorate_id'] = $user->governorate_id;
    }

    // معالجة المهندسين بشكل صحيح
    $engineerIds = $request->input('engineer_ids', []);
    
    // إزالة القيم الفارغة والنصوص وإزالة التكرار
    $engineerIds = array_values(array_unique(array_filter($engineerIds, function($id) {
        return !empty($id) && is_numeric($id) && $id > 0;
    })));
    
    // تحويل لأرقام صحيحة
    $engineerIds = array_map('intval', $engineerIds);
    
    $validated['engineer_ids'] = $engineerIds;
    $validated['is_active'] = $request->has('is_active');

    $team->update($validated);

    return redirect()->route('teams.index')->with('success', 'تم تحديث الفريق بنجاح');
}    /**
     * حذف فريق
     */
    public function destroy(Team $team)
    {
        $team->delete();

        return redirect()->route('teams.index')
                        ->with('success', 'تم حذف الفريق بنجاح');
    }

    /**
     * تبديل حالة الفريق (تفعيل/تعطيل)
     */
    public function toggleStatus(Team $team)
    {
        $team->update(['is_active' => !$team->is_active]);

        $status = $team->is_active ? 'تفعيل' : 'تعطيل';
        
        return redirect()->back()
                        ->with('success', "تم {$status} الفريق بنجاح");
    }

    /**
     * جلب المهندسين المتاحين حسب المحافظة (Ajax للـ Admin)
     */
    public function getEngineersByGovernorate($governorateId)
    {
        $engineers = $this->getAvailableEngineers($governorateId);
        
        return response()->json($engineers);
    }

    /**
     * دالة مساعدة: جلب المهندسين المتاحين (غير مرتبطين بفريق)
     * 
     * @param int $governorateId
     * @param int|null $excludeTeamId - لاستثناء فريق معين (للتعديل)
     * @return Collection
     */
    private function getAvailableEngineers($governorateId, $excludeTeamId = null)
    {
        // جلب جميع IDs المهندسين المرتبطين بفرق أخرى
        $busyEngineerIds = Team::where('governorate_id', $governorateId)
            ->when($excludeTeamId, function($query) use ($excludeTeamId) {
                $query->where('id', '!=', $excludeTeamId);
            })
            ->get()
            ->pluck('engineer_ids')
            ->flatten()
            ->unique()
            ->toArray();

        // جلب المهندسين غير المشغولين
        return Engineer::where('is_active', true)
            ->where('home_governorate_id', $governorateId)
            ->whereNotIn('id', $busyEngineerIds)
            ->get();
    }
}