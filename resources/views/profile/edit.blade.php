@extends('layouts.app')

@section('title', 'تعديل بيانات الحساب')

@push('styles')
<style>
.profile-card {
    background: #fff;
    padding: 25px;
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
}
</style>
@endpush

@section('content')

<div class="profile-card">

 @if ($errors->any())
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle me-1"></i>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <h4 class="mb-3">
        <i class="fas fa-user-edit"></i> تعديل بيانات الحساب
    </h4>

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label fw-bold">الاسم الكامل</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">اسم المستخدم</label>
                <input type="text" name="username" class="form-control"
                       value="{{ old('username', $user->username) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">رقم الجوال</label>
                <input type="text" name="phone" class="form-control"
                       value="{{ old('phone', $user->phone) }}">
            </div>

            {{-- الدور فقط للعرض وليس التعديل --}}
            <div class="col-md-6">
                <label class="form-label fw-bold">الدور</label>
                <input type="text" class="form-control bg-light"
                       value="{{ $user->role?->display_name ?? 'موظف' }}"
                       disabled>
            </div>

        </div>

        <div class="mt-4 text-end">
            <button class="btn btn-primary px-4">
                <i class="fas fa-save"></i> حفظ التغييرات
            </button>

            <a href="{{ route('profile.index') }}" class="btn btn-secondary px-4">
                <i class="fas fa-arrow-right"></i> رجوع
            </a>
        </div>

    </form>

</div>

@endsection
