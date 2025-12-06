@extends('layouts.app')

@section('title', 'سجل نشاطات المستخدمين')

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

    /* Responsive */
    @media (max-width: 768px) {
        .custom-table thead { display: none; }

        .custom-table, 
        .custom-table tbody,
        .custom-table tr,
        .custom-table td {
            display: block;
            width: 100%;
        }

        .custom-table tbody tr {
            margin-bottom: 15px;
            background: white;
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
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-history"></i>
        سجل نشاطات المستخدمين
    </h1>
</div>

<div class="table-card">

    <div class="table-header">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="بحث في السجل...">
            <i class="fas fa-search"></i>
        </div>
    </div>

    <table class="custom-table">
        <thead>
            <tr>
                <th>#</th>
                <th>المستخدم</th>
                <th>الرابط</th>
                <th>الطريقة</th>
                <th>IP</th>
                <th>التاريخ</th>
            </tr>
        </thead>

        <tbody>
            @forelse($activities as $a)
            <tr>
                <td data-label="#"> {{ $loop->iteration + ($activities->currentPage() - 1) * $activities->perPage() }} </td>
                <td data-label="المستخدم"> {{ $a->user->name ?? 'زائر' }} </td>
                <td data-label="الرابط"> {{ $a->url }} </td>
                <td data-label="الطريقة"> {{ $a->method }} </td>
                <td data-label="IP"> {{ $a->ip }} </td>
                <td data-label="التاريخ"> {{ $a->created_at->format('Y-m-d H:i') }} </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:35px; color:#888;">
                    <i class="fas fa-history" style="font-size:40px;"></i><br>
                    لا يوجد نشاطات مسجلة حالياً
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-wrapper">
        {{ $activities->links('vendor.pagination.bootstrap-custom') }}
    </div>

</div>

@endsection

@push('scripts')
<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    const value = this.value.toLowerCase();
    document.querySelectorAll('.custom-table tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
    });
});
</script>
@endpush
