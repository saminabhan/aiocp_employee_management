@extends('layouts.app')

@section('title', 'الدوام اليومي')

@push('styles')
<style>
    .page-header {
        background: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #0C4079;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-add {
        background: #0C4079;
        color: white;
        padding: 10px 25px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s;
    }

    .btn-add:hover {
        background: #083058;
        color: white;
    }

    /* إحصائيات */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 25px;
    }

    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }

    .stats-card .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #0C4079;
        margin-bottom: 5px;
    }

    .stats-card .stat-label {
        color: #6c757d;
        font-weight: 600;
        font-size: 14px;
    }

    .stats-icon {
        position: absolute;
        left: 15px;
        bottom: 15px;
        font-size: 45px;
        opacity: 0.1;
        color: #0C4079;
    }

    /* الفلاتر */
    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 20px 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }

    .form-label {
        font-weight: 600;
        color: #0C4079;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control, .form-select {
        border-radius: 8px;
        padding: 10px 12px;
        border: 1px solid #e8e8e8;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0C4079;
        box-shadow: 0 0 0 0.2rem rgba(12, 64, 121, 0.25);
    }

    .btn-main {
        background: #0C4079;
        border-color: #0C4079;
        color: white;
        padding: 10px 20px;
        font-weight: 600;
        border-radius: 8px;
    }

    .btn-main:hover {
        background: #083058;
        color: white;
    }

    /* الجدول */
    .table-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    .custom-table thead {
        background: #f8f9fa;
    }

    .custom-table th {
        padding: 15px;
        text-align: right;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #e8e8e8;
        font-size: 14px;
    }

    .custom-table td {
        padding: 15px;
        text-align: right;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
    }

    .custom-table tbody tr:hover {
        background: #f8f9fa;
    }

    .user-info {
        display: inline-flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
    }

    .user-details h6 {
        margin: 0;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .user-details p {
        margin: 0;
        font-size: 12px;
        color: #999;
    }

    /* الأزرار */
    .action-btns {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .btn-action {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-edit {
        background: #fff3e0;
        color: #f57c00;
    }

    .btn-delete {
        background: #ffebee;
        color: #c62828;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .pagination-wrapper {
        padding: 20px 25px;
        display: flex;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }

        .page-header {
            text-align: center;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
            margin-top: 15px;
        }
    }

    @media (max-width: 768px) {
        .stats-row {
            grid-template-columns: 1fr;
        }

        .page-title {
            font-size: 20px;
            justify-content: center;
        }

        .filter-card .row {
            gap: 15px;
        }

        .custom-table thead {
            display: none;
        }

        .custom-table tbody tr {
            display: block;
            margin-bottom: 15px;
            padding: 15px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .custom-table tbody td {
            display: flex;
            justify-content: space-between;
            padding: 10px 5px;
            border-bottom: 1px solid #f3f3f3;
        }

        .custom-table tbody td:last-child {
            border-bottom: none;
        }

        .custom-table tbody td::before {
            content: attr(data-label);
            font-weight: bold;
            color: #0C4079;
        }

        .action-btns {
            justify-content: flex-start;
        }
    }

    @media (max-width: 480px) {
        .stats-card .stat-value {
            font-size: 24px;
        }

        .stats-card .stat-label {
            font-size: 12px;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
        }

        .user-details h6 {
            font-size: 12px;
        }
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
<div class="container" dir="rtl">

    @if (Session::has('success'))
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            toast: true,
            position: 'bottom-start',
            icon: 'success',
            title: '{{ Session::get("success") }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            backdrop: false,
            customClass: {
                popup: 'medium-small-toast'
            }
        });
    });
    </script>
    @endif

    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h1 class="page-title">
                <i class="fas fa-calendar-check"></i>
                الدوام اليومي
            </h1>
            <a href="{{ route('attendance.create') }}" class="btn-add">
                <i class="fas fa-plus"></i>
                تسجيل دوام جديد
            </a>
        </div>
    </div>

    <div class="stats-row">
        <div class="stats-card">
            <div class="stat-value">{{ $todayStats['total'] }}</div>
            <div class="stat-label">إجمالي السجلات</div>
            <i class="fas fa-users stats-icon"></i>
        </div>

        <div class="stats-card">
            <div class="stat-value">{{ $todayStats['present'] }}</div>
            <div class="stat-label">الحاضرون</div>
            <i class="fas fa-user-check stats-icon"></i>
        </div>

        <div class="stats-card">
            <div class="stat-value">{{ $todayStats['absent'] }}</div>
            <div class="stat-label">الغائبون</div>
            <i class="fas fa-user-times stats-icon"></i>
        </div>

        <div class="stats-card">
            <div class="stat-value">{{ $todayStats['engineers'] }} / {{ $todayStats['supervisors'] }}</div>
            <div class="stat-label">مهندسين / مشرفين</div>
            <i class="fas fa-user-tie stats-icon"></i>
        </div>
    </div>

    <div class="filter-card">
        <form method="GET" action="{{ route('attendance.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">التاريخ</label>
                <input type="date" name="date" class="form-control" value="{{ $date }}" max="{{ now()->format('Y-m-d') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">نوع المستخدم</label>
                <select name="user_type" class="form-select">
                    <option value="all" {{ $userType == 'all' ? 'selected' : '' }}>الكل</option>
                    <option value="engineer" {{ $userType == 'engineer' ? 'selected' : '' }}>المهندسين</option>
                    <option value="supervisor" {{ $userType == 'supervisor' ? 'selected' : '' }}>المشرفين</option>
                </select>
            </div>

            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-main flex-fill">
                    <i class="fas fa-search"></i> بحث
                </button>
                <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="table-card">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الموظف</th>
                    <th>النوع</th>
                    <th>التاريخ</th>
                    <th>اليوم</th>
                    <th>الحالة</th>
                    <th>المسجل</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                <tr>
                    <td data-label="#">{{ $loop->iteration + ($attendances->currentPage() - 1) * $attendances->perPage() }}</td>
                    
                    <td data-label="الموظف">
                        <div class="user-info">
                            @if($attendance->user_type === 'engineer' && $attendance->engineer)
                                @if($attendance->engineer->personal_image)
                                    <img src="{{ asset('storage/' . $attendance->engineer->personal_image) }}" class="user-avatar" alt="{{ $attendance->engineer->full_name }}">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($attendance->engineer->full_name) }}&background=0C4079&color=fff&size=90" class="user-avatar" alt="{{ $attendance->engineer->full_name }}">
                                @endif
                                <div class="user-details">
                                    <h6>{{ $attendance->engineer->full_name }}</h6>
                                    <p>{{ $attendance->engineer->national_id }}</p>
                                </div>
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($attendance->supervisor->name) }}&background=0C4079&color=fff&size=90" class="user-avatar" alt="{{ $attendance->supervisor->name }}">
                                <div class="user-details">
                                    <h6>{{ $attendance->supervisor->name }}</h6>
                                    <p>{{ $attendance->supervisor->username }}</p>
                                </div>
                            @endif
                        </div>
                    </td>

                    <td data-label="النوع">
                        @if($attendance->user_type === 'engineer')
                            <span class="badge-custom badge-primary">مهندس</span>
                        @else
                            <span class="badge-custom badge-info">مشرف</span>
                        @endif
                    </td>

                    <td data-label="التاريخ">{{ $attendance->attendance_date->format('Y-m-d') }}</td>

                    <td data-label="اليوم">
                        @php
                            $dayNames = [
                                'Saturday' => 'السبت','Sunday' => 'الأحد','Monday' => 'الاثنين',
                                'Tuesday' => 'الثلاثاء','Wednesday' => 'الأربعاء','Thursday' => 'الخميس','Friday' => 'الجمعة'
                            ];
                            $dayName = $dayNames[$attendance->attendance_date->format('l')];
                        @endphp
                        <span class="badge bg-light text-dark">{{ $dayName }}</span>
                    </td>

                    <td data-label="الحالة">{!! $attendance->status_badge !!}</td>

                    <td data-label="المسجل">
                        @if($attendance->recordedBy)
                            <small>{{ $attendance->recordedBy->name }}</small>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    <td data-label="الإجراءات">
                        <div class="action-btns">
                            <a href="{{ route('attendance.edit', $attendance->id) }}" class="btn-action btn-edit" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('attendance.destroy', $attendance->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-calendar-times" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                        لا توجد سجلات دوام لهذا التاريخ
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($attendances->hasPages())
        <div class="pagination-wrapper">
            {{ $attendances->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'سيتم حذف سجل الدوام نهائياً',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush