@extends('layouts.app')

@section('title', 'إدارة المستخدمين')


@push('styles')
<style>
    .action-btns {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
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

<div class="d-flex justify-content-between mb-3">
    <h3>إدارة المستخدمين</h3>

     @if(user_can('users.create'))
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> إضافة مستخدم
    </a>
    @endif
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

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>الاسم</th>
            <th>اسم المستخدم</th>
            <th>الدور</th>
            <th width="150px">التحكم</th>
        </tr>
    </thead>

    <tbody>
        @foreach($users as $user)
        <tr>
    <td data-label="#">{{$loop->iteration}}</td>
    <td data-label="الاسم">{{ $user->name }}</td>
    <td data-label="اسم المستخدم">{{ $user->username }}</td>
    <td data-label="الدور">{{ $user->role?->display_name ?? 'موظف' }}</td>

    <td data-label="التحكم">
        <div class="action-btns">

            {{-- المدير رقم 1 لا يمكن تعديله أو حذفه --}}
            @if($user->id == 1)
                <span class="badge bg-secondary">مدير النظام</span>
            @else

                @if(user_can('users.view'))
                <a href="{{ route('users.show', $user->id) }}" class="btn-action btn-view" title="عرض">
                    <i class="fas fa-eye"></i>
                </a>
                @endif

                @if(user_can('users.edit'))
                <a href="{{ route('users.edit', $user->id) }}" class="btn-action btn-edit" title="تعديل">
                    <i class="fas fa-edit"></i>
                </a>
                @endif

                @if(user_can('users.delete'))
                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                      style="display: inline;"
                      onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-delete" title="حذف">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
                @endif

            @endif

        </div>
    </td>
</tr>

        @endforeach
    </tbody>
</table>

@endsection
