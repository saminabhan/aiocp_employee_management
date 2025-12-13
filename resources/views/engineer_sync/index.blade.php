@extends('layouts.app')

@section('title', 'مزامنة المهندسين')

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

    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
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

/* ===================  Responsive Table  =================== */
@media (max-width: 768px) {

    .custom-table thead {
        display: none;
    }

    .custom-table, 
    .custom-table tbody, 
    .custom-table tr, 
    .custom-table td {
        display: block;
        width: 100%;
    }

    .custom-table tbody tr {
        margin-bottom: 15px;
        background: #ffffff;
        padding: 18px;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.06);
        border: 1px solid #eee;
    }

    .custom-table tbody tr:hover {
        background: #fff !important;
    }

    .custom-table tbody td {
        padding: 10px 5px;
        text-align: right;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: none;
        position: relative;
    }

    .custom-table tbody td::before {
        content: attr(data-label);
        font-weight: 700;
        color: #0C4079;
        flex-basis: 55%;
        text-align: right;
    }

    .custom-table tbody td:last-child {
        margin-top: 10px;
        padding-top: 15px;
        border-top: 1px solid #eee;
        display: block;
        text-align: center !important;
    }

    .action-btns {
        justify-content: center !important;
        flex-wrap: wrap;
    }

    .btn-action {
        margin: 4px;
    }
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

    <!-- Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h1 class="page-title">
                <i class="fas fa-arrows-rotate"></i>
                مزامنة المهندسين
            </h1>
            @if(user_can('engineer_sync.create'))
            <a href="{{ route('engineer-sync.create') }}" class="btn-add">
                <i class="fas fa-plus"></i>
                تسجيل مزامنة
            </a>
            @endif
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stats-card">
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">إجمالي السجلات</div>
            <i class="fas fa-database stats-icon"></i>
        </div>

        <div class="stats-card">
            <div class="stat-value">{{ $stats['synced'] }}</div>
            <div class="stat-label">زامنوا</div>
            <i class="fas fa-check-circle stats-icon"></i>
        </div>

        <div class="stats-card">
            <div class="stat-value">{{ $stats['not_synced'] }}</div>
            <div class="stat-label">لم يزامنوا</div>
            <i class="fas fa-times-circle stats-icon"></i>
        </div>
    </div>

    <!-- Filter -->
    <div class="filter-card">
        <form method="GET" action="{{ route('engineer-sync.index') }}" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">التاريخ</label>
                <input type="date" name="date" class="form-control"
                       value="{{ $date }}" max="{{ now()->format('Y-m-d') }}">
            </div>

            <div class="col-md-6 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-main flex-fill">
                    <i class="fas fa-search"></i> بحث
                </button>
                <a href="{{ route('engineer-sync.index') }}" class="btn btn-secondary">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-card">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المهندس</th>
                    <th>التاريخ</th>
                    <th>الحالة</th>
                    <th>المسجل</th>
                    @if(user_can('engineer_sync.edit') || user_can('engineer_sync.delete'))
                    <th>الإجراءات</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($syncs as $sync)
                <tr>
                    <td data-label="#">{{ $loop->iteration + ($syncs->currentPage() - 1) * $syncs->perPage() }}</td>

                    <td data-label="المهندس">
                        <div class="user-info">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($sync->engineer->full_name) }}&background=0C4079&color=fff&size=90"
                                 class="user-avatar">
                            <div class="user-details">
                                <h6>{{ $sync->engineer->full_name }}</h6>
                                <p>{{ $sync->engineer->national_id }}</p>
                            </div>
                        </div>
                    </td>

                    <td data-label="التاريخ">{{ $sync->sync_date->format('Y-m-d') }}</td>

                    <td data-label="الحالة">
                        @if($sync->is_synced)
                            <span class="badge-custom badge-success">زامن</span>
                        @else
                            <span class="badge-custom badge-danger">لم يزامن</span>
                        @endif
                    </td>

                    <td data-label="المسجل">
                        {{ $sync->recordedBy->name ?? '-' }}
                    </td>

                    @if(user_can('engineer_sync.edit') || user_can('engineer_sync.delete'))

                    <td data-label="الإجراءات">
                        <div class="action-btns">
                            @if(user_can('engineer_sync.edit'))
                            <a href="{{ route('engineer-sync.edit', $sync->id) }}"
                               class="btn-action btn-edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif

                            @if(user_can('engineer_sync.delete'))
                            <form action="{{ route('engineer-sync.destroy', $sync->id) }}"
                                  method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted p-5">
                        <i class="fas fa-arrows-rotate fa-3x mb-3"></i>
                        <br>
                        لا توجد سجلات مزامنة لهذا التاريخ
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($syncs->hasPages())
        <div class="pagination-wrapper">
            {{ $syncs->links() }}
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
            text: 'سيتم حذف سجل المزامنة نهائياً',
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