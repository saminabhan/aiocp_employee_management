@extends('layouts.app')

@section('title', 'الملف الشخصي')

@push('styles')
<style>
.profile-card {
    background: #fff;
    border-radius: 14px;
    padding: 25px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
}

.engineer-header {
    display: flex;
    align-items: center;
    gap: 20px;
}

.engineer-avatar {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    object-fit: cover;
}
.section-title {
    font-size: 18px;
    font-weight: bold;
    margin-top: 30px;
    margin-bottom: 10px;
}
.bg-primary{
    background-color: #0C4079 !important;
    color: #fff;
    padding: 5px 10px;
    border-radius: 8px;
}
</style>
@endpush


@section('content')

<div class="profile-card">
    
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

    {{-- === Header === --}}
    <div class="engineer-header">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0C4079&color=fff&size=100" 
             class="engineer-avatar">

        <div class="profile-info">
            <h4>{{ $user->name }}</h4>
            <p class="text-muted mb-1">@ {{ $user->username }}</p>

            <span class="badge bg-primary">
                {{ $user->role?->display_name ?? 'موظف' }}
            </span>
        </div>
    </div>


    {{-- === بيانات المستخدم === --}}
    <h5 class="section-title">بيانات المستخدم</h5>
    
    <div class="row mt-3">
        <div class="col-md-3 mb-3">
            <label class="fw-bold">الاسم الكامل</label>
            <input type="text" class="form-control" value="{{ $user->name }}" disabled>
        </div>

        <div class="col-md-3 mb-3">
            <label class="fw-bold">اسم المستخدم</label>
            <input type="text" class="form-control" value="{{ $user->username }}" disabled>
        </div>

         <div class="col-md-3 mb-3">
            <label class="fw-bold">رقم الهاتف</label>
            <input type="text" class="form-control" value="{{ $user->phone }}" disabled>
        </div>

        <div class="col-md-3 mb-3">
            <label class="fw-bold">الدور</label>
            <input type="text" class="form-control" 
                   value="{{ $user->role?->display_name ?? 'موظف' }}" disabled>
        </div>
    </div>


    {{-- === أزرار التحكم === --}}
    <div class="mt-4 text-end">
        @if(user_can('profile.edit'))
        <a href="{{ route('profile.edit') }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> تعديل البيانات
        </a>
        @endif
        

        <a href="{{ route('profile.change-password') }}" class="btn btn-secondary">
            <i class="fas fa-lock"></i> تغيير كلمة المرور
        </a>
    </div>

</div>

@endsection
