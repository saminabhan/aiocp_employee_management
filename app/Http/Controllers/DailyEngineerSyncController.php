<?php

namespace App\Http\Controllers;

use App\Models\DailyEngineerSync;
use App\Models\Engineer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyEngineerSyncController extends Controller
{
    private function applyRoleFilter($query)
    {
        $user = auth()->user();
        $role = $user->role->name ?? '';

        switch ($role) {

            case 'admin':
                return $query;

            case 'governorate_manager':
                return $query->where(function ($q) use ($user) {
                    $q->whereHas('engineer', function ($eng) use ($user) {
                        $eng->where('work_governorate_id', $user->governorate_id);
                    })
                    ->orWhere('recorded_by', $user->id);
                });

            case 'survey_supervisor':
                return $query->whereHas('engineer', function ($eng) use ($user) {
                    $eng->where('main_work_area_code', $user->main_work_area_code);
                });

            default:
                abort(403, 'غير مصرح لك');
        }
    }

    public function index(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));

        $query = DailyEngineerSync::with(['engineer', 'recordedBy'])
                    ->forDate($date);

        $query = $this->applyRoleFilter($query);

        $syncs = $query->latest()->paginate(20);

        $stats = [
            'total' => $query->count(),
            'synced' => (clone $query)->where('is_synced', true)->count(),
            'not_synced' => (clone $query)->where('is_synced', false)->count(),
        ];

        return view('engineer_sync.index', compact('syncs', 'date', 'stats'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $selectedDate = $request->input('date', now()->format('Y-m-d'));

        $engineers = Engineer::where('is_active', true);

        if ($user->role->name === 'governorate_manager') {
            $engineers->where('work_governorate_id', $user->governorate_id);
        }

        $engineers = $engineers->get();

        $registered = DailyEngineerSync::whereDate('sync_date', $selectedDate)
            ->pluck('engineer_id')
            ->toArray();

        $availableEngineers = $engineers->reject(fn ($e) => in_array($e->id, $registered));

        $maxDate = now()->format('Y-m-d');

        return view('engineer_sync.create', compact('availableEngineers', 'selectedDate','maxDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'engineer_ids' => 'required|array|min:1',
            'engineer_ids.*' => 'required|integer|exists:engineers,id',
            'sync_date' => 'required|date|before_or_equal:today',
            'is_synced' => 'required|boolean',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->engineer_ids as $engineerId) {

                $exists = DailyEngineerSync::where('engineer_id', $engineerId)
                    ->whereDate('sync_date', $request->sync_date)
                    ->exists();

                if ($exists) {
                    continue;
                }

                DailyEngineerSync::create([
                    'engineer_id' => $engineerId,
                    'sync_date' => $request->sync_date,
                    'is_synced' => $request->is_synced,
                    'notes' => $request->notes,
                    'recorded_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return redirect()->route('engineer-sync.index', ['date' => $request->sync_date])
                ->with('success', 'تم حفظ المزامنة بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $sync = DailyEngineerSync::with('engineer')->findOrFail($id);
        $this->applyRoleFilter(DailyEngineerSync::where('id', $id))->firstOrFail();

        return view('engineer_sync.edit', compact('sync'));
    }

    public function update(Request $request, $id)
    {
        $sync = DailyEngineerSync::findOrFail($id);
        $this->applyRoleFilter(DailyEngineerSync::where('id', $id))->firstOrFail();

        $request->validate([
            'is_synced' => 'required|boolean',
            'notes' => 'nullable|string|max:500',
        ]);

        $sync->update($request->only('is_synced', 'notes'));

        return redirect()->route('engineer-sync.index')
            ->with('success', 'تم تحديث حالة المزامنة');
    }

    public function destroy($id)
    {
        $sync = DailyEngineerSync::findOrFail($id);
        $this->applyRoleFilter(DailyEngineerSync::where('id', $id))->firstOrFail();

        $sync->delete();

        return redirect()->route('engineer-sync.index')
            ->with('success', 'تم حذف السجل');
    }
}
