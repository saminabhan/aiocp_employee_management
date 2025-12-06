@extends('layouts.app')

@section('title', 'سجل الأخطاء')

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
    }

    .table-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .table-header {
        padding: 20px 25px;
        border-bottom: 1px solid #e8e8e8;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .search-box {
        position: relative;
        width: 300px;
    }

    .search-box input {
        width: 100%;
        padding: 10px 40px 10px 15px;
        border: 1px solid #e8e8e8;
        border-radius: 8px;
    }

    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
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
    }

    .custom-table td {
        padding: 15px;
        text-align: right;
        border-bottom: 1px solid #f0f0f0;
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

    .btn-view {
        background: #e3f2fd;
        color: #1976d2;
    }

    /* =================== Responsive =================== */
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

        .custom-table tbody td {
            display: flex;
            justify-content: space-between;
            padding: 10px 5px;
            border: none;
        }

        .custom-table tbody td::before {
            content: attr(data-label);
            font-weight: 700;
            color: #0C4079;
        }

        .custom-table tbody td:last-child {
            padding-top: 15px;
            border-top: 1px solid #eee;
            display: block;
            text-align: center !important;
        }
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-exclamation-triangle"></i>
        سجل الأخطاء
    </h1>
</div>

<div class="table-card">
    <div class="table-header">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="بحث عن خطأ...">
            <i class="fas fa-search"></i>
        </div>
    </div>

    <table class="custom-table">
        <thead>
            <tr>
                <th>#</th>
                <th>الرسالة</th>
                <th>المستخدم</th>
                <th>التاريخ</th>
                <th>الإجراء</th>
            </tr>
        </thead>

        <tbody>
            @forelse($errors as $e)
            <tr>
                <td data-label="#"> {{ $e->id }} </td>

                <td data-label="الرسالة">
                    {{ Str::limit($e->message, 40) }}
                </td>

                <td data-label="المستخدم">
                    {{ $e->user->name ?? 'زائر' }}
                </td>

                <td data-label="التاريخ">
                    {{ $e->created_at }}
                </td>

                <td data-label="الإجراء">
                     <a href="{{ route('errors.show', $e->id) }}" class="btn-action btn-view" title="عرض">
                            <i class="fas fa-eye"></i>
                        </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; padding:40px; color:#999;">
                    <i class="fas fa-exclamation-circle" style="font-size:48px; margin-bottom:15px"></i>
                    لا توجد أخطاء مسجلة
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($errors->hasPages())
    <div class="pagination-wrapper p-4">
        {{ $errors->links('vendor.pagination.bootstrap-custom') }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    const value = this.value.toLowerCase();
    const rows = document.querySelectorAll('.custom-table tbody tr');

    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
    });
});
</script>
@endpush
