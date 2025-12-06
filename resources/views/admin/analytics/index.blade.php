@extends('layouts.app')

@section('title', 'تحليلات الزيارات')

@push('styles')
<!-- نفس ستايل صفحة إدارة الفرق بالضبط -->
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
        outline: none;
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

    .custom-table tbody tr:hover {
        background: #f8f9fa;
    }

    .pagination-wrapper {
        padding: 20px 25px;
        display: flex;
        justify-content: center;
    }

    /* ============= Responsive ============= */

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
            padding: 10px 5px;
            display: flex;
            justify-content: space-between;
            border: none;
        }

        .custom-table tbody td::before {
            content: attr(data-label);
            font-weight: 700;
            color: #0C4079;
        }

        .custom-table tbody td:last-child {
            margin-top: 10px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-chart-line"></i>
        تحليلات زيارات الصفحات
    </h1>
</div>

<div class="table-card">

    <div class="table-header">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="بحث في الزيارات...">
            <i class="fas fa-search"></i>
        </div>
    </div>

    <table class="custom-table">
        <thead>
            <tr>
                <th>#</th>
                <th>المستخدم</th>
                <th>الصفحة</th>
                <th>عدد الزيارات</th>
                <th>آخر زيارة</th>
            </tr>
        </thead>

        <tbody>
            @forelse($visits as $v)
            <tr>
                <td data-label="#">
                    {{ $loop->iteration + ($visits->currentPage() - 1) * $visits->perPage() }}
                </td>

                <td data-label="المستخدم">
                    {{ $v->user->name ?? 'زائر' }}
                </td>

                <td data-label="الصفحة">
                    {{ $v->url }}
                </td>

                <td data-label="عدد الزيارات">
                    {{ $v->count }}
                </td>

                <td data-label="آخر زيارة">
                    {{ \Carbon\Carbon::parse($v->last_visited_at)->format('Y-m-d h:i A') }}
                    <span class="text-muted d-block">
                        ({{ \Carbon\Carbon::parse($v->last_visited_at)->diffForHumans() }})
                    </span>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="5" style="text-align:center; padding:40px; color:#999;">
                    <i class="fas fa-chart-line" style="font-size:48px; margin-bottom:15px"></i>
                    لا توجد زيارات مسجلة حالياً
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($visits->hasPages())
    <div class="pagination-wrapper">
        {{ $visits->links('vendor.pagination.bootstrap-custom') }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('.custom-table tbody tr');

    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(searchValue)
            ? ''
            : 'none';
    });
});
</script>
@endpush
