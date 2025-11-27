@extends('layouts.app')

@section('title', 'إدارة الفرق')

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

    .badge-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-active {
        background: #d4edda;
        color: #155724;
    }

    .badge-inactive {
        background: #f8d7da;
        color: #721c24;
    }

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

    .btn-view {
        background: #e3f2fd;
        color: #1976d2;
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
</style>
@endpush

@section('content')

<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-users-cog"></i>
        إدارة الفرق
    </h1>
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

<div class="table-card">
    <div class="table-header">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="بحث عن فريق...">
            <i class="fas fa-search"></i>
        </div>

        @if(user_can('teams.create'))
        <a href="{{ route('teams.create') }}" class="btn-add">
            <i class="fas fa-plus"></i>
            إضافة فريق جديد
        </a>
        @endif
    </div>

    <table class="custom-table">
        <thead>
            <tr>
                <th>#</th>
                <th>اسم الفريق</th>
                <th>المحافظة</th>
                <th>عدد المهندسين</th>
                <th>الحالة</th>
                @php
                    $role = Auth::user()->role;
                    $roleName = $role->name ?? null;
                @endphp
                @if ($roleName !== 'survey_supervisor')
                    <th>الكود الرئيسي</th>
                @endif

                <th>الكود الفرعي</th>
                <th>الإجراءات</th>
            </tr>
        </thead>

        <tbody>
            @forelse($teams as $team)
            <tr>
                <td data-label="#">{{ $loop->iteration + ($teams->currentPage() - 1) * $teams->perPage() }}</td>

                <td data-label="اسم الفريق">
                    <strong>{{ $team->name }}</strong>
                </td>

                <td data-label="المحافظة">
                    <span class="badge badge-status badge-active">
                        {{ $team->governorate->name ?? 'غير محدد' }}
                    </span>
                </td>

                <td data-label="عدد المهندسين">
                    <span class="badge badge-status badge-active">
                        {{ $team->engineers_count }} مهندس
                    </span>
                </td>

                <td data-label="الحالة">
                    @if($team->is_active)
                        <span class="badge-status badge-active">مفعل</span>
                    @else
                        <span class="badge-status badge-inactive">معطل</span>
                    @endif
                </td>

                @php
                    $role = Auth::user()->role;
                    $roleName = $role->name ?? null;
                @endphp
                @if ($roleName !== 'survey_supervisor')

                <td data-label="كود منطقة العمل الرئيسي" class="text-center">
                    <span class="badge-status badge-active">{{ $team->mainWorkArea->name ?? 'غير محدد' }}</span>
                </td>
                @endif

                <td data-label="كود منطقة العمل الفرعي" class="text-center">
                    <span class="badge-status badge-active">{{ $team->subWorkArea->name ?? 'غير محدد' }}</span>
                </td>

                <td data-label="الإجراءات">
                    <div class="action-btns">
                        <a href="{{ route('teams.show', $team) }}" class="btn-action btn-view" title="عرض">
                            <i class="fas fa-eye"></i>
                        </a>

                        @if(user_can('teams.edit'))
                        <a href="{{ route('teams.edit', $team) }}" class="btn-action btn-edit" title="تعديل">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('teams.toggle', $team) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-action btn-view" title="تفعيل / تعطيل">
                                <i class="fas fa-power-off"></i>
                            </button>
                        </form>
                        @endif

                        @if(user_can('teams.delete'))
                        <form action="{{ route('teams.destroy', $team) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الفريق؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete" title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="10" style="text-align:center; padding:40px; color:#999;">
                    <i class="fas fa-users" style="font-size:48px; margin-bottom:15px"></i>
                    لا توجد فرق حالياً
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($teams->hasPages())
    <div class="pagination-wrapper">
        {{ $teams->links() }}
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
