@extends('layouts.app')

@section('title', 'إدارة المستخدمين')

@push('styles')
<style>
.page-wrapper {
    padding: 20px;
    min-height: 100vh;
    font-family: 'Cairo', sans-serif;
}

.header-card {
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

.sub-toolbar {
    background: #fff;
    padding: 18px;
    border-radius: 15px;
    margin-bottom: 15px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.06);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.search-box {
    width: 320px;
    background: #f7f9fc;
    border: 2px solid #d4dbea;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 15px;
    transition: all ease-in-out 0.25s;
}
.search-box:focus {
    border-color: #0d3c66;
    box-shadow: 0 0 10px rgba(13,60,102,0.2);
}

.count-badge {
    background: #0C4079;
    color: #fff;
    padding: 8px 15px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 15px;
}

.table-card {
    background: #fff;
    border-radius: 16px;
    padding: 0;
    overflow: hidden;
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
}

.table thead {
    background: #0d3c66;
    color: #fff;
}
.table thead th {
    padding: 16px;
    font-size: 15px;
    text-align: center;
    border: none;
}

.table tbody tr {
    transition: 0.2s;
}
.table tbody tr:hover {
    background: #f4f8ff;
}

.table tbody td {
    padding: 16px;
    font-size: 15px;
    vertical-align: middle;
    text-align: center;
    border: none;
    border-bottom: 1px solid #f0f3f8;
}

.action-btns {
    display: flex;
    justify-content: center;
    gap: 8px;
}

.btn-action {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    font-size: 17px;
    transition: 0.2s ease;
}

.btn-action:hover {
    transform: translateY(-2px);
}

.btn-view   { background: #e3f2fd; color: #1769aa; }
.btn-edit   { background: #fff4e5; color: #ef6c00; }
.btn-delete { background: #fdecea; color: #c62828; }

@media (max-width: 768px) {
    .search-box { width: 100%; }
    .sub-toolbar{ flex-direction: column; gap: 12px; }

    .table thead { display: none; }

    .table tbody tr {
        display: block;
        padding: 15px;
        margin-bottom: 12px;
        border-radius: 12px;
        border: 1px solid #f1f1f1;
    }

    .table tbody td {
        display: flex;
        justify-content: space-between;
        padding: 10px 6px;
        border-bottom: 1px solid #eee;
    }

    .table tbody td:last-child { border-bottom: none; }

    .table tbody td::before {
        content: attr(data-label);
        font-weight: 700;
        color: #0d3c66;
    }
}

</style>@endpush

@section('content')

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
@if (Session::has('error'))
<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        toast: true,
        position: 'bottom-start',
        icon: 'error',
        title: '{{ Session::get("error") }}',
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


<div class="page-wrapper">

    <div class="header-card d-flex justify-content-between align-items-center flex-wrap">
        <h1 class="page-title">
            <i class="fas fa-users-cog"></i>
            إدارة المستخدمين
        </h1>

        @if(user_can('users.create'))
        <a href="{{ route('users.create') }}" class="btn-add">
            <i class="fas fa-plus"></i> إضافة مستخدم
        </a>
        @endif
    </div>

    <div class="sub-toolbar">
        <input type="text" id="searchInput" class="search-box"
               placeholder="ابحث عن مستخدم بالاسم أو اسم المستخدم...">

        <div class="count-badge">
            عدد المستخدمين: {{ count($users) }}
        </div>
    </div>

    <div class="table-card">
        <table class="table table-hover mb-0">
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
                    <td data-label="#">{{ $loop->iteration }}</td>
                    <td data-label="الاسم">{{ $user->name }}</td>
                    <td data-label="اسم المستخدم">{{ $user->username }}</td>
                    <td data-label="الدور">
                        @if($user->role?->display_name)
                            {{ $user->role->display_name }}
                            @if ($user->governorate_id)
                                - {{ $user->governorate?->name }}
                            @endif
                        @else
                            موظف
                        @endif
                    </td>

                    <td data-label="التحكم">
                        <div class="action-btns">

                            @if($user->id == 4)
                                <span class="badge bg-secondary">مدير النظام</span>
                            @else

                                @if(user_can('users.view'))
                                <a href="{{ route('users.show', $user->id) }}" class="btn-action btn-view">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endif

                                @if(user_can('users.edit'))
                                <a href="{{ route('users.edit', $user->id) }}" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif

                                @if(user_can('users.delete'))
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')"
                                      style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button class="btn-action btn-delete">
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
    </div>

</div>

@endsection

@push('scripts')
<script>
document.getElementById("searchInput").addEventListener("keyup", function () {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll(".table tbody tr");

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(value) ? "" : "none";
    });
});
</script>
@endpush
