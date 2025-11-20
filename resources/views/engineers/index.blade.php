@extends('layouts.app')

@section('title', 'إدارة المهندسين')

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

    .engineer-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
    }

    .engineer-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .engineer-details h6 {
        margin: 0;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .engineer-details p {
        margin: 0;
        font-size: 12px;
        color: #999;
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

    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: none;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
    }
    
    @media (max-width: 992px) {

    .table-header {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }

    .search-box {
        width: 100%;
    }

    .btn-add {
        width: 100%;
        justify-content: center;
    }

    .custom-table th,
    .custom-table td {
        padding: 10px;
        font-size: 13px;
    }

    .engineer-avatar {
        width: 40px;
        height: 40px;
    }

    .engineer-details h6 {
        font-size: 13px;
    }

    .engineer-details p {
        font-size: 11px;
    }
}

@media (max-width: 768px) {

    .page-title {
        font-size: 20px;
        text-align: center;
    }

    .page-header {
        text-align: center;
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

    .btn-add {
        padding: 10px;
        font-size: 14px;
    }

    .engineer-avatar {
        width: 35px;
        height: 35px;
    }

    .engineer-details h6 {
        font-size: 12px;
    }
}

</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-users"></i>
        إدارة المهندسين
    </h1>
</div>

@if(session('success'))
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    {{ session('success') }}
</div>
@endif

<div class="table-card">
    <div class="table-header">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="بحث عن مهندس...">
            <i class="fas fa-search"></i>
        </div>
        @if(user_can('engineers.create'))
        <a href="{{ route('engineers.create') }}" class="btn-add">
            <i class="fas fa-plus"></i>
            إضافة مهندس جديد
        </a>
        @endif
    </div>

    <table class="custom-table">
        <thead>
            <tr>
                <th>#</th>
                <th>المهندس</th>
                <th>رقم الهوية</th>
                <th>الجوال</th>
                <th>المحافظة</th>
                <th>التخصص</th>
                <th>سنوات الخبرة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($engineers as $engineer)
            <tr>
                <td data-label="#">{{ $loop->iteration + ($engineers->currentPage() - 1) * $engineers->perPage() }}</td>
                <td data-label="المهندس">
                    <div class="engineer-info">
                        @if($engineer->personal_image)
                            <img src="{{ asset('storage/' . $engineer->personal_image) }}" alt="{{ $engineer->full_name }}" class="engineer-avatar">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($engineer->full_name) }}&background=0C4079&color=fff&size=90" alt="{{ $engineer->full_name }}" class="engineer-avatar">
                        @endif
                        <div class="engineer-details">
                            <h6>{{ $engineer->full_name }}</h6>
                            <p>{{ $engineer->gender->name ?? 'غير محدد' }}</p>
                        </div>
                    </div>
                </td>
                <td data-label="رقم الهوية">{{ $engineer->national_id }}</td>
                <td data-label="الجوال">{{ $engineer->mobile_1 }}</td>
                <td data-label="المحافظة">{{ $engineer->homeGovernorate->name ?? 'غير محدد' }}</td>
                <td data-label="التخصص">{{ $engineer->specialization ?? 'غير محدد' }}</td>
                <td data-label="سنوات الخبرة">
                    <span class="badge-status badge-active">
                        {{ $engineer->experience_years }} سنة
                    </span>
                </td>
                <td data-label="الإجراءات">
                    <div class="action-btns">
                        @if(user_can('engineers.view'))
                        <a href="{{ route('engineers.show', $engineer) }}" class="btn-action btn-view" title="عرض">
                            <i class="fas fa-eye"></i>
                        </a>
                        @endif

                         @if(user_can('engineers.edit'))
                        <a href="{{ route('engineers.edit', $engineer) }}" class="btn-action btn-edit" style="
                        pointer-events: none;
                        opacity: 0.5;
                        cursor: not-allowed;" title="تعديل">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endif

                         @if(user_can('engineers.delete'))
                        <form action="{{ route('engineers.destroy', $engineer) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المهندس؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete" style="
                        pointer-events: none;
                        opacity: 0.5;
                        cursor: not-allowed;" title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                    <i class="fas fa-users" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                    لا توجد بيانات مهندسين حالياً
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($engineers->hasPages())
    <div class="pagination-wrapper">
        {{ $engineers->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('.custom-table tbody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
</script>
@endpush