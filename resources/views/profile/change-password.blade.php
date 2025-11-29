@extends('layouts.app')

@section('title', 'تغيير كلمة المرور')

@push('styles')
<style>
.profile-card {
    background: #fff;
    border-radius: 14px;
    padding: 25px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
}

.section-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 15px;
}

.form-label {
    font-weight: bold;
}

.btn-warning {
    background-color: #f0ad4e;
    border: none;
    font-weight: bold;
}

.btn-secondary {
    font-weight: bold;
}
</style>
@endpush

@section('content')

<div class="profile-card">
@if ($errors->any())
    <div class="alert alert-danger d-flex align-items-start gap-2 p-3" style="border-radius: 10px;">
        <i class="fas fa-exclamation-triangle fa-lg mt-1"></i>

        <div>
            <h6 class="mb-2 fw-bold">حدثت بعض الأخطاء:</h6>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

    <h5 class="section-title">
        <i class="fas fa-key"></i> تغيير كلمة المرور
    </h5>

    <form action="{{ route('profile.update-password') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mt-3 g-3">

            <div class="col-md-4">
                <label class="form-label">كلمة المرور الحالية</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">كلمة المرور الجديدة</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

        </div>

        <div class="mt-4 text-end">
            <button class="btn btn-warning px-4">
                <i class="fas fa-check"></i> تحديث كلمة المرور
            </button>

            <a href="{{ route('profile.index') }}" class="btn btn-secondary px-4">
                <i class="fas fa-arrow-right"></i> رجوع
            </a>
        </div>

    </form>

</div>

@endsection
