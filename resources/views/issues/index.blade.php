@extends('layouts.app')

@section('title', 'مشاكل تطبيق الحصر')

@push('styles')
<style>
    .page-header {
        background: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
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

    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        display: flex;
        align-items: center;
        gap: 15px;
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-icon.total { background: #e3f2fd; color: #1976d2; }
    .stat-icon.open { background: #ffebee; color: #c62828; }
    .stat-icon.progress { background: #fff3e0; color: #e65100; }
    .stat-icon.closed { background: #e8f5e9; color: #2e7d32; }

    .stat-info h4 {
        font-size: 14px;
        color: #666;
        margin: 0 0 5px 0;
    }

    .stat-info p {
        font-size: 24px;
        font-weight: 700;
        color: #0C4079;
        margin: 0;
    }

    .filters-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .issues-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .issues-table {
        width: 100%;
        border-collapse: collapse;
    }

    .issues-table thead {
        background: #f8f9fa;
    }

    .issues-table th {
        padding: 12px 10px;
        text-align: center;
        font-weight: 600;
        color: #0C4079;
        border: 1px solid #dee2e6;
        font-size: 14px;
    }

    .issues-table td {
        padding: 12px 10px;
        text-align: center;
        border: 1px solid #dee2e6;
        font-size: 13px;
    }

    .issues-table tbody tr:hover {
        background: #f8f9fa;
    }

    .badge-status {
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-status.open { background: #ffebee; color: #c62828; }
    .badge-status.in_progress { background: #fff3e0; color: #e65100; }
    .badge-status.closed { background: #e8f5e9; color: #2e7d32; }

    .badge-priority {
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-priority.low { background: #e3f2fd; color: #1976d2; }
    .badge-priority.medium { background: #fff3e0; color: #e65100; }
    .badge-priority.high { background: #ffebee; color: #c62828; }

    .btn-primary {
        background: #0C4079;
        border-color: #0C4079;
        padding: 10px 20px;
        font-weight: 600;
    }

    .btn-primary:hover {
        background: #083058;
        border-color: #083058;
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
       .btn-action:hover {
        transform: translateY(-2px);
    }
    
    .btn-view {
        background: #e3f2fd;
        color: #1976d2;
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

    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h1 class="page-title">
                <i class="fas fa-tools"></i>
                مشاكل تطبيق الحصر
            </h1>
            @if(user_can('issues.create'))
            <a href="{{ route('issues.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                إضافة تذكرة جديدة
            </a>
            @endif
        </div>
    </div>

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

    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="stat-info">
                <h4>إجمالي التذاكر</h4>
                <p>{{ $stats['total'] }}</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon open">
                <i class="fas fa-folder-open"></i>
            </div>
            <div class="stat-info">
                <h4>مفتوحة</h4>
                <p>{{ $stats['open'] }}</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon progress">
                <i class="fas fa-spinner"></i>
            </div>
            <div class="stat-info">
                <h4>قيد المعالجة</h4>
                <p>{{ $stats['in_progress'] }}</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon closed">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h4>مغلقة</h4>
                <p>{{ $stats['closed'] }}</p>
            </div>
        </div>
    </div>

    <div class="filters-card">
        <form action="{{ route('issues.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="">الكل</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>مفتوحة</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>مغلقة</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">الأولوية</label>
                    <select name="priority" class="form-select">
                        <option value="">الكل</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>منخفضة</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>متوسطة</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>عالية</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">بحث</label>
                    <input type="text" name="search" class="form-control" placeholder="ابحث عن تذكرة..." value="{{ request('search') }}">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>
                        بحث
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="issues-card">
        @if($issues->count())
            <div class="table-responsive">
                <table class="issues-table custom-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>نوع المشكلة</th>
                            <th>مقدم التذكرة</th>
                            <th>النوع</th>
                            <th>الوصف</th>
                            <th>الأولوية</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($issues as $index => $issue)
                            <tr>
                                <td data-label="#">{{ $issues->firstItem() + $index }}</td>
                                <td data-label="نوع المشكلة">{{ $issue->problem->name ?? 'غير محدد' }}</td>
                                <td data-label="مقدم التذكرة">{{ $issue->submitter_name }}</td>
                                <td data-label="النوع">
                                    <span class="badge bg-secondary">{{ $issue->submitter_type }}</span>
                                </td>
                                <td style="max-width: 250px; text-align: right;" data-label="الوصف">
                                    {{ Str::limit($issue->description, 50) }}
                                </td>
                                <td data-label="الأولوية">
                                    <span class="badge-priority {{ $issue->priority }}">
                                        @if($issue->priority == 'low') منخفضة
                                        @elseif($issue->priority == 'medium') متوسطة
                                        @else عالية @endif
                                    </span>
                                </td>
                                <td data-label="الحالة">
                                    <span class="badge-status {{ $issue->status }}">
                                        @if($issue->status == 'open') مفتوحة
                                        @elseif($issue->status == 'in_progress') قيد المعالجة
                                        @else مغلقة @endif
                                    </span>
                                </td>
                                <td data-label="التاريخ">{{ $issue->created_at->format('Y-m-d') }}</td>
                                <td data-label="الإجراءات">
                                    <a href="{{ route('issues.show', $issue) }}" class="btn-action btn-view" title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $issues->links('vendor.pagination.bootstrap-custom') }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">لا توجد تذاكر</p>
            </div>
        @endif
    </div>

</div>
@endsection