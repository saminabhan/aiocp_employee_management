<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Constant;
use App\Models\Engineer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Team::query()->with(['governorate']);

        switch ($user->role->name ?? '') {

            case 'admin':
            case 'system_admin':
            case 'support':
                break;

            case 'governorate_manager':
                $query->where('governorate_id', $user->governorate_id);
                break;

            case 'survey_supervisor':
                $query->where('main_work_area_code', $user->main_work_area_code);
                break;

            case 'engineer':
                $engineerId = $user->engineer->id ?? null;

                if ($engineerId) {
                    $query->whereRaw("JSON_CONTAINS(engineer_ids, '\"$engineerId\"')");
                } else {
                    $query->whereRaw('1=0');
                }
                break;

            default:
                if (!$user->hasPermission('teams.view')) {
                    abort(403, 'غير مصرح لك بعرض الفرق.');
                }

                if ($user->governorate_id) {
                    $query->where('governorate_id', $user->governorate_id);
                }

                if ($user->main_work_area_code) {
                    $query->where('main_work_area_code', $user->main_work_area_code);
                }
                break;
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $teams = $query->latest()->paginate(10);

        return view('teams.index', compact('teams'));
    }


public function create()
{
    $user = auth()->user();

    $roleName = $user->role->name ?? null;

    $governorates = collect();
    $mainCodes = collect();
    $subCodes = collect();
    $engineers = collect();

    $canCreate = $user->hasPermission('teams.create');

    switch ($roleName) {

        case 'admin':
        case 'system_admin':
            $usedSubCodes = Team::pluck('sub_work_area_code')->filter()->toArray();
            $mainCodes = Constant::childrenOfId(55)->get();
            $governorates = Constant::childrenOfId(14)->get();
            break;

case 'governorate_manager':
    $usedSubCodes = Team::pluck('sub_work_area_code')->filter()->toArray();
    
    $mainCodes = Constant::where('parent', 55)
                         ->where('governorate_id', $user->governorate_id)
                         ->get();
    
    $engineers = $this->getAvailableEngineers($user->governorate_id);
    break;
        case 'survey_supervisor':
            $usedSubCodes = Team::pluck('sub_work_area_code')->filter()->toArray();
            $mainCodes = Constant::childrenOfId(55)->get();

            $busyEngineerIds = Team::where('governorate_id', $user->governorate_id)
                ->get()
                ->pluck('engineer_ids')
                ->flatten()
                ->unique()
                ->toArray();

            $engineers = Engineer::where('main_work_area_code', $user->main_work_area_code)
                ->whereNotIn('id', $busyEngineerIds)
                ->get();
            break;

        default:
            if (!$canCreate) {
                abort(403, 'ليس لديك صلاحية لإضافة فرق');
            }

            $usedSubCodes = Team::pluck('sub_work_area_code')->filter()->toArray();
            $mainCodes = Constant::childrenOfId(55)->get();

            if ($canCreate) {
                $governorates = Constant::childrenOfId(14)->get();
            }
            elseif ($user->governorate_id) {
                $engineers = $this->getAvailableEngineers($user->governorate_id);
            }

            break;
    }

    $isAdminOrManager = $roleName === 'admin' || ($user->role_id === null && $canCreate) || $roleName === 'system_admin' || $roleName === 'governorate_manager';

    return view('teams.create', compact(
        'governorates',
        'mainCodes',
        'subCodes',
        'engineers',
        'usedSubCodes',
        'user',
        'isAdminOrManager'
    ));
}

public function store(Request $request)
{
    $user = auth()->user();
    $role = $user->role->name ?? '';

    switch ($role) {

        case 'admin':
        case 'system_admin':
        case 'governorate_manager':
        case 'survey_supervisor':
            break;

        default:
            if (!$user->hasPermission('teams.create')) {
                abort(403, 'ليس لديك صلاحية لإضافة فرق');
            }
            break;
    }

    $rules = [
        'name' => 'required|string|max:255',
        'main_work_area_code' => 'required|exists:constants,id',
        'sub_work_area_code' => 'required|exists:constants,id',
        'engineer_ids' => 'required|array|min:2',
        'engineer_ids.*' => 'exists:engineers,id',
    ];

    if (in_array($role, ['admin', 'system_admin']) || $user->hasPermission('teams.create')) {
        $rules['governorate_id'] = 'required|exists:constants,id';
    }

    $validated = $request->validate($rules);

    if (in_array($role, ['governorate_manager', 'survey_supervisor'])) {
        $validated['governorate_id'] = $user->governorate_id;
    }

    if (!in_array($role, ['admin', 'system_admin']) && $user->governorate_id) {
        $validated['governorate_id'] = $user->governorate_id;
    }

    $validated['governorate_id'] = $validated['governorate_id'] ?? $user->governorate_id;

    if (!$validated['governorate_id']) {
        abort(422, 'لا يمكن إنشاء فريق بدون المحافظة.');
    }

    $validated['engineer_ids'] = array_map('intval', array_unique($validated['engineer_ids']));
    $validated['is_active'] = true;

    $team = Team::create($validated);

    Engineer::whereIn('id', $validated['engineer_ids'])
        ->update(['team_id' => $team->id]);

    return redirect()->route('teams.index')
        ->with('success', 'تم إنشاء الفريق بنجاح');
}


    public function show(Team $team)
    {
        $user = auth()->user();

        switch ($user->role->name ?? '') {

            case 'admin':
            case 'system_admin':
            case 'support':
                break;

            case 'governorate_manager':
                if ($team->governorate_id !== $user->governorate_id) {
                    abort(403, 'غير مصرح لك بعرض هذا الفريق');
                }
                break;

            case 'survey_supervisor':
                $engineerIds = Engineer::where('main_work_area_code', $user->main_work_area_code)
                    ->pluck('id')
                    ->toArray();

                $teamHasEngineer = !empty(array_intersect($team->engineer_ids, $engineerIds));

                if (!$teamHasEngineer) {
                    abort(403, 'غير مصرح لك بعرض هذا الفريق');
                }
                break;

            default:
                if (!$user->hasPermission('teams.view')) {
                    abort(403, 'غير مصرح لك بعرض هذا الفريق');
                }

                if ($user->governorate_id && $team->governorate_id !== $user->governorate_id) {
                    abort(403, 'غير مصرح لك بعرض هذا الفريق');
                }

                if ($user->main_work_area_code && $team->main_work_area_code !== $user->main_work_area_code) {
                    abort(403, 'غير مصرح لك بعرض هذا الفريق');
                }
                break;
        }

        $engineers = $team->engineers();
        return view('teams.show', compact('team', 'engineers'));
    }

public function edit(Team $team)
{
    $user = auth()->user();

    $governorates = collect();
    $engineers = collect();

    switch ($user->role->name ?? '') {

        case 'admin':
        case 'system_admin':
            $usedSubCodes = Team::where('id', '!=', $team->id)->pluck('sub_work_area_code')->filter()->toArray();
            $mainCodes = Constant::childrenOfId(55)->get();
            $subCodes = Constant::childrenOfId($team->main_work_area_code)->get();
            $governorates = Constant::childrenOfId(14)->get();
            $engineers = $this->getAvailableEngineersForTeam($team, $team->governorate_id);
            break;

        case 'governorate_manager':
            if ($team->governorate_id !== $user->governorate_id) {
                abort(403, 'غير مصرح لك بتعديل هذا الفريق');
            }

            $usedSubCodes = Team::where('id', '!=', $team->id)->pluck('sub_work_area_code')->filter()->toArray();
            
            $mainCodes = Constant::where('parent', 55)
                                 ->where('governorate_id', $user->governorate_id)
                                 ->get();
            
            $subCodes = Constant::childrenOfId($team->main_work_area_code)->get();
            $engineers = $this->getAvailableEngineersForTeam($team, $user->governorate_id);
            break;

        case 'survey_supervisor':
            $engineerIds = Engineer::where('main_work_area_code', $user->main_work_area_code)
                ->pluck('id')
                ->toArray();

            $teamHasEngineer = !empty(array_intersect($team->engineer_ids, $engineerIds));

            if (!$teamHasEngineer) {
                abort(403, 'غير مصرح لك بتعديل هذا الفريق');
            }

            $usedSubCodes = Team::where('id', '!=', $team->id)->pluck('sub_work_area_code')->filter()->toArray();
            $mainCodes = Constant::childrenOfId(55)->get();
            $subCodes = Constant::childrenOfId($team->main_work_area_code)->get();

            $busyEngineerIds = Team::where('governorate_id', $user->governorate_id)
                ->where('id', '!=', $team->id)
                ->get()
                ->pluck('engineer_ids')
                ->flatten()
                ->unique()
                ->toArray();

            $engineers = Engineer::where('main_work_area_code', $user->main_work_area_code)
                ->whereNotIn('id', $busyEngineerIds)
                ->get();
            break;

        default:
            if (!$user->hasPermission('teams.edit')) {
                abort(403, 'غير مصرح لك بتعديل هذا الفريق');
            }

            if ($user->governorate_id && $team->governorate_id !== $user->governorate_id) {
                abort(403, 'غير مصرح لك بتعديل هذا الفريق');
            }

            if ($user->main_work_area_code && $team->main_work_area_code !== $user->main_work_area_code) {
                abort(403, 'غير مصرح لك بتعديل هذا الفريق');
            }

            $usedSubCodes = Team::where('id', '!=', $team->id)
                ->pluck('sub_work_area_code')
                ->filter()
                ->toArray();

            $mainCodes = Constant::childrenOfId(55)->get();
            $subCodes = Constant::childrenOfId($team->main_work_area_code)->get();

            $governorates = Constant::childrenOfId(14)->get();
            $engineers = $this->getAvailableEngineersForTeam($team, $team->governorate_id);

            break;
    }

    $isSupervisor = in_array($user->role->name ?? '', ['survey_supervisor', 'field_supervisor']);
    
    $isGovernorateManager = ($user->role->name ?? '') === 'governorate_manager';

    return view('teams.edit', compact(
        'team',
        'governorates',
        'mainCodes',
        'subCodes',
        'engineers',
        'usedSubCodes',
        'user',
        'isSupervisor',
        'isGovernorateManager'
    ));
}

public function update(Request $request, Team $team)
{
    $user = auth()->user();

    switch ($user->role->name ?? '') {

        case 'admin':
        case 'system_admin':
            break;

        case 'governorate_manager':
            if ($team->governorate_id !== $user->governorate_id) {
                abort(403, 'غير مصرح لك بتعديل هذا الفريق');
            }
            break;

        case 'survey_supervisor':
            $engineerIds = Engineer::where('main_work_area_code', $user->main_work_area_code)->pluck('id')->toArray();
            $teamHasEngineer = !empty(array_intersect($team->engineer_ids ?? [], $engineerIds));

            if (!$teamHasEngineer) {
                abort(403, 'غير مصرح لك بتعديل هذا الفريق');
            }
            break;

        default:
            if (!$user->hasPermission('teams.edit')) {
                abort(403, 'غير مصرح لك بتعديل هذا الفريق');
            }

            if ($user->governorate_id && $team->governorate_id !== $user->governorate_id) {
                abort(403, 'غير مصرح لك بتعديل هذا الفريق');
            }

            if ($user->main_work_area_code && $team->main_work_area_code !== $user->main_work_area_code) {
                abort(403, 'غير مصرح لك بتعديل هذا الفريق');
            }
            break;
    }


    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'main_work_area_code' => 'required|exists:constants,id',
        'sub_work_area_code' => 'required|exists:constants,id',
        'engineer_ids' => 'required|array|min:2',
        'engineer_ids.*' => 'exists:engineers,id'
    ]);

    if (in_array($user->role?->name, ['admin', 'system_admin']) 
        || $user->hasPermission('teams.edit')) 
    {
        $request->validate([
            'governorate_id' => 'required|exists:constants,id'
        ]);
        $validated['governorate_id'] = $request->governorate_id;
    } else {
        $validated['governorate_id'] = $user->governorate_id;
    }

$oldEngineers = array_map('intval', (array) ($team->engineer_ids ?? []));

$newEngineers = array_map('intval', array_values(array_unique($validated['engineer_ids'])));

$validated['engineer_ids'] = $newEngineers;

$team->update($validated);


$toRemove = array_values(array_diff($oldEngineers, $newEngineers)); // أرقام
$toAdd    = array_values(array_diff($newEngineers, $oldEngineers)); // أرقام

if (!empty($toRemove)) {
    Engineer::whereIn('id', $toRemove)->update(['team_id' => null]);
}

if (!empty($toAdd)) {
    Engineer::whereIn('id', $toAdd)->update(['team_id' => $team->id]);
}

    return redirect()->route('teams.index')
        ->with('success', 'تم تحديث الفريق بنجاح');
}

protected function getAvailableEngineersForTeam(Team $team, $filterGovernorate = null)
{
    $teamEngineerIds = (array) ($team->engineer_ids ?? []);
    $mainCode = $team->main_work_area_code;

    $query = Engineer::where('main_work_area_code', $mainCode)
        ->where(function ($q) use ($teamEngineerIds) {
            $q->whereNull('team_id');
            if (!empty($teamEngineerIds)) {
                $q->orWhereIn('id', $teamEngineerIds);
            }
        });

    if ($filterGovernorate) {
        $query->where('work_governorate_id', $filterGovernorate);
    }

    return $query->get();
}

public function destroy(Team $team)
{
    $user = auth()->user();

    switch ($user->role->name ?? '') {

        case 'admin':
        case 'system_admin':
            break;

        case 'governorate_manager':
            if ($team->governorate_id !== $user->governorate_id) {
                abort(403, 'غير مصرح لك بحذف هذا الفريق');
            }
            break;

        case 'survey_supervisor':
            $engineerIds = Engineer::where('main_work_area_code', $user->main_work_area_code)
                ->pluck('id')
                ->toArray();

            $teamHasEngineer = !empty(array_intersect($team->engineer_ids, $engineerIds));

            if (!$teamHasEngineer) {
                abort(403, 'غير مصرح لك بحذف هذا الفريق');
            }
            break;

        default:
            if (!$user->hasPermission('teams.delete')) {
                abort(403, 'غير مصرح لك بحذف هذا الفريق');
            }

            if ($user->governorate_id && $team->governorate_id !== $user->governorate_id) {
                abort(403, 'غير مصرح لك بحذف هذا الفريق');
            }

            if ($user->main_work_area_code && $team->main_work_area_code !== $user->main_work_area_code) {
                abort(403, 'غير مصرح لك بحذف هذا الفريق');
            }
            break;
    }

    if (is_array($team->engineer_ids) && count($team->engineer_ids) > 0) {
        Engineer::whereIn('id', $team->engineer_ids)
            ->update(['team_id' => null]);
    }

    $team->delete();

    return back()->with('success', 'تم حذف الفريق بنجاح');
}

    public function getSubWorkCodes($mainCodeId)
    {
        return Constant::where('parent', $mainCodeId)->select('id', 'name')->get();
    }


    public function getEngineersByGovernorate($governorateId)
    {
        return $this->getAvailableEngineers($governorateId);
    }


    private function getAvailableEngineers($governorateId, $teamId = null)
    {
        return Engineer::where('work_governorate_id', $governorateId)
            ->where(function($q) use ($teamId) {
                $q->whereNull('team_id'); // غير مرتبط بأي فريق
                if ($teamId) {
                    $q->orWhere('team_id', $teamId); // أو مرتبط بالفريق الحالي
                }
            })
            ->get();
    }
    
    public function getSubCodes($mainId)
    {
        return Constant::where('parent', $mainId)->select('id', 'name')->get();
    }


    public function toggleStatus(Team $team)
    {
        $team->is_active = !$team->is_active;
        $team->save();

        return redirect()->back()->with('success', 'تم تغيير حالة الفريق بنجاح.');
    }

public function getEngineersByMainCode($mainCodeId)
{
    try {
        $currentTeamId = request()->get('team_id');

        $query = Engineer::where('main_work_area_code', $mainCodeId)
            ->where('is_active', 1);

        if ($currentTeamId) {
            $query->where(function($q) use ($currentTeamId) {
                $q->whereNull('team_id')
                  ->orWhere('team_id', $currentTeamId);
            });
        } else {
            $query->whereNull('team_id');
        }

        $engineers = $query->select('id', 'first_name', 'second_name', 'third_name' , 'last_name')
            ->get();

        return response()->json($engineers);

    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function getMainCodesByGovernorate($govId)
{
    $codes = Constant::where('parent', 55)
                     ->where('governorate_id', $govId)
                     ->get();

    return response()->json($codes);
}


}