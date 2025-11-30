<?php

namespace App\Http\Controllers;

use App\Models\DailyAttendance;
use App\Models\Engineer;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DailyAttendanceController extends Controller
{
    private function applyRoleFilter($query)
    {
        $user = auth()->user();

        switch ($user->role->name ?? '') {

            case 'admin':
                return $query;

            case 'governorate_manager':
                return $query->where(function ($q) use ($user) {
                    $q->whereHas('engineer', function ($eng) use ($user) {
                        $eng->where('work_governorate_id', $user->governorate_id);
                    });
                    $q->orWhereHas('supervisor', function ($sup) use ($user) {
                        $sup->where('governorate_id', $user->governorate_id)
                            ->whereHas('role', function ($r) {
                                $r->where('name', 'survey_supervisor');
                            });
                    });
                    $q->orWhere('recorded_by', $user->id);
                });

            default:
                abort(403, 'غير مصرح لك');
        }
    }

    public function index(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $userType = $request->input('user_type', 'all');

        $query = DailyAttendance::with(['engineer', 'supervisor', 'recordedBy'])
                                ->forDate($date);

        $query = $this->applyRoleFilter($query);

        if ($userType === 'engineer') {
            $query->engineers();
        } elseif ($userType === 'supervisor') {
            $query->supervisors();
        }

        $attendances = $query->latest()->paginate(20);

        $todayStats = [
            'total' => $query->count(),
            'present' => (clone $query)->where('status', 'present')->count(),
            'absent' => (clone $query)->where('status', 'absent')->count(),
            'engineers' => (clone $query)->engineers()->count(),
            'supervisors' => (clone $query)->supervisors()->count(),
        ];

        return view('attendance.index', compact('attendances', 'date', 'userType', 'todayStats'));
    }

    public function create()
    {
        $user = auth()->user();

        switch ($user->role->name) {
            case 'admin':
                $engineers = Engineer::where('is_active', true)->get();
                $supervisors = User::whereHas('role', fn($q) => $q->where('name', 'survey_supervisor'))->get();
                break;

            case 'governorate_manager':
                $engineers = Engineer::where('is_active', true)
                    ->where('work_governorate_id', $user->governorate_id)->get();

                $supervisors = User::whereHas('role', fn($q) => $q->where('name', 'survey_supervisor'))
                    ->where('governorate_id', $user->governorate_id)->get();
                break;

            default:
                abort(403);
        }

        $maxDate = now()->format('Y-m-d');
        $minDate = null;

        return view('attendance.create', compact('engineers', 'supervisors', 'maxDate', 'minDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_type' => 'required|in:engineer,supervisor',
            'user_id' => 'required',
            'attendance_date' => 'required|date|before_or_equal:today',
            'status' => 'required|in:present,absent,leave',
            'notes' => 'nullable|string|max:500'
        ]);

        $user = auth()->user();
        $targetUser = null;

        if ($request->user_type === 'engineer') {
            $targetUser = Engineer::findOrFail($request->user_id);
            if ($user->role->name === 'governorate_manager' &&
                $targetUser->work_governorate_id != $user->governorate_id) {
                abort(403);
            }
        } else {
            $targetUser = User::findOrFail($request->user_id);
            if ($user->role->name === 'governorate_manager' &&
                $targetUser->governorate_id != $user->governorate_id) {
                abort(403);
            }
        }

        $date = $request->attendance_date;
        $dayType = DailyAttendance::getDayType($date);

        if ($dayType === 'friday') {
            $request->merge(['status' => 'weekend']);
        }

        $data = [
            'user_type' => $request->user_type,
            'attendance_date' => $date,
            'day_type' => $dayType,
            'status' => $request->status,
            'notes' => $request->notes,
            'recorded_by' => auth()->id(),
            'engineer_id' => $request->user_type === 'engineer' ? $request->user_id : null,
            'user_id' => $request->user_type === 'supervisor' ? $request->user_id : null,
        ];

        DailyAttendance::create($data);

        return redirect()->route('attendance.index')
            ->with('success', 'تم تسجيل الدوام بنجاح');
    }

    public function edit($id)
    {
        $attendance = DailyAttendance::with(['engineer', 'supervisor'])->findOrFail($id);

        $this->applyRoleFilter(DailyAttendance::where('id', $id))->firstOrFail();

        return view('attendance.edit', compact('attendance'));
    }

    public function update(Request $request, $id)
    {
        $attendance = DailyAttendance::findOrFail($id);

        $this->applyRoleFilter(DailyAttendance::where('id', $id))->firstOrFail();

        $request->validate([
            'status' => 'required|in:present,absent,leave,weekend',
            'notes' => 'nullable|string|max:500'
        ]);

        $attendance->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('attendance.index')
            ->with('success', 'تم تحديث الدوام بنجاح');
    }

    public function destroy($id)
    {
        $attendance = DailyAttendance::findOrFail($id);

        $this->applyRoleFilter(DailyAttendance::where('id', $id))->firstOrFail();

        $attendance->delete();

        return redirect()->route('attendance.index')
            ->with('success', 'تم حذف الدوام بنجاح');
    }

    public function statistics(Request $request)
    {
        $user = auth()->user();

        $month = $request->input('month', now()->format('Y-m'));
        $userType = $request->input('user_type', 'all');

        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $query = DailyAttendance::whereBetween('attendance_date', [$startDate, $endDate]);

        $query = $this->applyRoleFilter($query);

        if ($userType !== 'all') {
            $query->where('user_type', $userType);
        }

        $stats = [
            'total_days' => $query->count(),
            'present' => (clone $query)->where('status', 'present')->count(),
            'absent' => (clone $query)->where('status', 'absent')->count(),
            'leave' => (clone $query)->where('status', 'leave')->count(),
            'weekend' => (clone $query)->where('status', 'weekend')->count(),
        ];

        $topAttendees = $this->getTopAttendees($startDate, $endDate, $userType);

        return view('attendance.statistics', compact('stats', 'month', 'userType', 'topAttendees'));
    }

    private function getTopAttendees($startDate, $endDate, $userType)
    {
        $query = DailyAttendance::whereBetween('attendance_date', [$startDate, $endDate])
                                ->where('status', 'present');

        $query = $this->applyRoleFilter($query);

        if ($userType !== 'all') {
            $query->where('user_type', $userType);
        }

        if ($userType === 'engineer' || $userType === 'all') {
            $engineers = $query->clone()
                ->engineers()
                ->select('engineer_id', DB::raw('COUNT(*) as attendance_count'))
                ->groupBy('engineer_id')
                ->orderByDesc('attendance_count')
                ->limit(10)
                ->with('engineer')
                ->get();
        }

        if ($userType === 'supervisor' || $userType === 'all') {
            $supervisors = $query->clone()
                ->supervisors()
                ->select('user_id', DB::raw('COUNT(*) as attendance_count'))
                ->groupBy('user_id')
                ->orderByDesc('attendance_count')
                ->limit(10)
                ->with('supervisor')
                ->get();
        }

        return [
            'engineers' => $engineers ?? collect(),
            'supervisors' => $supervisors ?? collect()
        ];
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'user_type' => 'required|in:engineer,supervisor',
            'user_id' => 'required',
            'date' => 'required|date'
        ]);

        $user = auth()->user();

        if ($request->user_type === 'engineer') {
            $target = Engineer::findOrFail($request->user_id);
            if ($user->role->name === 'governorate_manager' &&
                $target->work_governorate_id != $user->governorate_id) {
                abort(403);
            }
        } else {
            $target = User::findOrFail($request->user_id);
            if ($user->role->name === 'governorate_manager' &&
                $target->governorate_id != $user->governorate_id) {
                abort(403);
            }
        }

        $exists = DailyAttendance::where('user_type', $request->user_type)
            ->where($request->user_type === 'engineer' ? 'engineer_id' : 'user_id', $request->user_id)
            ->whereDate('attendance_date', $request->date)
            ->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'تم تسجيل الدوام مسبقاً لهذا التاريخ' : 'يمكن التسجيل'
        ]);
    }
}
