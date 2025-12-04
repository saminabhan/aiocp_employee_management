@extends('layouts.app')

@section('title', 'مشرفين الحصر في محافظتك')

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

    .action-btns {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .btn-view {
        background: #e3f2fd;
        color: #1976d2;
        border-radius: 8px;
        padding: 6px 12px;
        text-decoration: none;
        font-weight: 600;
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
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-user-tie"></i>
        مشرفين الحصر في محافظتك
    </h1>
</div>

<div class="table-card">

    <div class="table-header">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="بحث عن مشرف...">
            <i class="fas fa-search"></i>
        </div>
    </div>

    <table class="custom-table">
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>رقم الجوال</th>
                <th>المدينة</th>
                <th>منطقة العمل الرئيسية</th>
                <th>تفاصيل</th>
            </tr>
        </thead>

        <tbody>
            @forelse($supervisors as $s)
            <tr>
                <td data-label="#">
                    {{ $loop->iteration }}
                </td>

                <td data-label="الاسم">
                    <strong>{{ $s->name }}</strong>
                </td>

                <td data-label="رقم الجوال">
                    {{ $s->phone }}
                </td>

                <td data-label="المدينة">
                    {{ $s->city->name ?? '—' }}
                </td>

                <td data-label="منطقة العمل الرئيسية">
                    <span class="badge-status badge-active">
                        {{ $s->mainWorkArea->name ?? '—' }}
                    </span>
                </td>

                <td data-label="تفاصيل" class="text-center">
                    <a href="{{ route('governorate.supervisors.show', $s->id) }}" class="btn-view">
                                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:40px; color:#999;">
                    <i class="fas fa-user-tie" style="font-size:48px; margin-bottom:15px"></i>
                    لا يوجد مشرفين حالياً
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

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
