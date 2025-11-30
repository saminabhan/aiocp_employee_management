@extends('layouts.app')

@push('styles')
    <style>
    .attendance-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e5e5e5;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }

    .attendance-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        padding-bottom: 12px;
    }

    .attendance-title {
        font-size: 17px;
        font-weight: 600;
        color: #0C4079;
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .attendance-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 25px;
    }

    .stat-clean {
        background: #fafafa;
        border: 1px solid #e6e6e6;
        border-radius: 10px;
        padding: 12px;
        text-align: center;
    }

    .stat-clean .value {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 5px;
        color: #0C4079;
    }

    .stat-clean .label {
        font-size: 12px;
        color: #666;
    }

    .rate-box {
        text-align: center;
        padding: 20px;
        background: #fdfdfd;
        border: 1px solid #eaeaea;
        border-radius: 10px;
        margin-bottom: 25px;
    }

    .rate-circle svg {
        width: 110px;
        height: 110px;
    }

    .rate-value {
        font-size: 26px;
        font-weight: 700;
        color: #0C4079;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .latest-title {
        font-weight: 600;
        margin-bottom: 10px;
        color: #444;
    }

    .latest-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 5px;
        border-bottom: 1px solid #eee;
    }

    .latest-item:last-child {
        border-bottom: none;
    }

    .latest-avatar {
        width: 38px;
        height: 38px;
        border-radius: 6px;
        object-fit: cover;
        border: 1px solid #ddd;
    }

    .latest-info {
        flex: 1;
    }

    .latest-info .name {
        font-weight: 600;
        font-size: 14px;
    }

    .latest-info .type {
        font-size: 11px;
        color: #777;
    }

    .view-btn {
        display: block;
        text-align: center;
        padding: 12px;
        background: #f8f8f8;
        border: 1px solid #e2e2e2;
        color: #0C4079;
        border-radius: 10px;
        text-decoration: none;
        margin-top: 15px;
        font-weight: 600;
        transition: all 0.5s;
    }

    .view-btn:hover {
        background: #0C4079;
        color: #fff;
    }

    .badge-custom {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
     .badge-primary {
        background: #e3f2fd;
        color: #1976d2;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }
    .badge-info {
        background: #d1ecf1;
        color: #0c5460;
    }
    .badge-warning {
        background: #fff3cd; 
        color: #856404;
    }
    .badge-danger {
        background: #f8d7da;
        color: #721c24;
    }
    .badge-secondary {
    background: #e2e3e5;
    color: #41464b;
    }

</style>
@endpush

@section('content')

    <div class="stats-grid">

        @if(user_can('engineers.view'))
        <div class="stat-card">
            <div class="stat-info">
                <h3>إجمالي المهندسين</h3>
                <p>{{ $engineer_count }} مهندس/ة</p>
            </div>
            <div class="stat-icon bg-blue">
                <i class="fas fa-user-friends"></i>
            </div>
        </div>
        @endif

        @if(user_can('teams.view'))
         <div class="stat-card">
            <div class="stat-info">
                <h3>عدد الفرق</h3>
                <p>{{ $team_count }} فرق</p>
            </div>
            <div class="stat-icon bg-black">
                <i class="fas fa-users-cog"></i>
            </div>
        </div>
        @endif

        <!-- <div class="stat-card">
            <div class="stat-info">
                <h3>سجلات الدوام اليومي</h3>
                <p>37 مهندس/ة</p>
            </div>
            <div class="stat-icon bg-green">
                <i class="fas fa-business-time"></i>
            </div>
        </div> -->

    </div>
        

@php
use App\Models\DailyAttendance;
use Carbon\Carbon;

$user = auth()->user();

$today = Carbon::today();

$query = DailyAttendance::with(['engineer', 'supervisor'])->forDate($today);

if ($user->role->name === 'governorate_manager') {

    $query->where(function ($q) use ($user) {

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
}

$todayAttendance = [
    'total' => $query->count(),
    'present' => (clone $query)->where('status', 'present')->count(),
    'absent' => (clone $query)->where('status', 'absent')->count(),
    'engineers' => (clone $query)->where('user_type', 'engineer')->where('status', 'present')->count(),
    'supervisors' => (clone $query)->where('user_type', 'supervisor')->where('status', 'present')->count(),
];

$attendanceRate = $todayAttendance['total'] > 0
    ? round(($todayAttendance['present'] / $todayAttendance['total']) * 100, 1)
    : 0;

$latestAttendances = (clone $query)
    ->latest()
    ->limit(5)
    ->get();

@endphp



    @if (user_can('attendance.view'))

<div class="attendance-card">

    <div class="attendance-header">
        <div class="attendance-title">
            <i class="fas fa-calendar-check"></i> الدوام اليومي
        </div>

        <a href="{{ route('attendance.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> تسجيل
        </a>
    </div>

    <div class="attendance-stats">
        <div class="stat-clean">
            <div class="value">{{ $todayAttendance['total'] }}</div>
            <div class="label">إجمالي اليوم</div>
        </div>

        <div class="stat-clean">
            <div class="value">{{ $todayAttendance['present'] }}</div>
            <div class="label">الحاضرون</div>
        </div>

        <div class="stat-clean">
            <div class="value">{{ $todayAttendance['engineers'] }}</div>
            <div class="label">مهندسين</div>
        </div>

        <div class="stat-clean">
            <div class="value">{{ $todayAttendance['supervisors'] }}</div>
            <div class="label">مشرفين</div>
        </div>
    </div>

    <div class="rate-box">
        <div class="rate-circle" style="position:relative; margin:auto; width:120px; height:120px;">
            <svg viewBox="0 0 120 120">
                <circle cx="60" cy="60" r="54" stroke="#e5e5e5" stroke-width="7" fill="none"></circle>
                <circle cx="60" cy="60" r="54" stroke="#0C4079" stroke-width="7" fill="none"
                        stroke-dasharray="{{ 339.29 }}"
                        stroke-dashoffset="{{ 339.29 * (1 - $attendanceRate / 100) }}"
                        transform="rotate(-90 60 60)" stroke-linecap="round">
                </circle>
            </svg>
            <div class="rate-value">{{ $attendanceRate }}%</div>
        </div>

        <div class="fw-bold mt-2">نسبة الحضور</div>
        <small class="text-muted">{{ \Carbon\Carbon::today()->format('Y-m-d') }}</small>
    </div>

    @if($latestAttendances->count())
        <div class="latest-title">آخر التسجيلات</div>

        @foreach($latestAttendances as $attendance)
            <div class="latest-item">
                @if($attendance->user_type === 'engineer')
                    <img class="latest-avatar"
                         src="{{ $attendance->engineer->personal_image ? asset('storage/'.$attendance->engineer->personal_image) :
                         'https://ui-avatars.com/api/?name='.urlencode($attendance->engineer->full_name).'&size=80&background=0C4079&color=fff&size=90' }}">
                    <div class="latest-info">
                        <div class="name">{{ $attendance->engineer->full_name }}</div>
                        <div class="type">مهندس</div>
                    </div>

                @else
                    <img class="latest-avatar"
                         src="https://ui-avatars.com/api/?name={{ urlencode($attendance->supervisor->name) }}&size=80&background=0C4079&color=fff&size=90">
                    <div class="latest-info">
                        <div class="name">{{ $attendance->supervisor->name }}</div>
                        <div class="type">مشرف</div>
                    </div>
                @endif

                {!! $attendance->status_badge !!}
            </div>
        @endforeach
    @else
        <div class="text-center py-3 text-muted">لا توجد تسجيلات اليوم</div>
    @endif

    <a href="{{ route('attendance.index') }}" class="view-btn">
        <i class="fas fa-arrow-left"></i> عرض جميع السجلات
    </a>

</div>
    @endif

@endsection