@extends('layouts.auth')

@section('content')
 <div class="login-card animate__animated animate__fadeInUp animate__delay-0.5s">

    <div class="d-flex justify-content-center align-items-center gap-3 mb-4">
        <img src="{{ asset('assets/images/logo.png') }}" 
             alt="Logo" 
             class="img-fluid" 
             style="max-width: 130px; height: auto;">

        <img src="{{ asset('assets/images/Logo-Palimar.png') }}" 
             alt="Logo-Palimar" 
             class="img-fluid" 
             style="max-width: 120px; height: auto;">
    </div>

    <h1>تسجيل الدخول</h1>

    <p class="system-title text-muted" style="color: #4064aa;">
        نظام إدارة موظفين مشروع حصر الأضرار
    </p>

   @if (session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle me-1"></i>
        {{ session('error') }}
    </div>
@endif


       <form method="POST" action="{{ route('login') }}">
    @csrf

    <input type="text" name="username" class="form-control text-start" placeholder="اسم المستخدم" value="{{ old('username') }}" required>
    @error('email')
        <div class="text-danger mb-2 text-end">{{ $message }}</div>
    @enderror

    <input type="password" name="password" class="form-control text-start" placeholder="كلمة المرور" required>
    @error('password')
        <div class="text-danger mb-2 text-end">{{ $message }}</div>
    @enderror

   <button type="submit" class="btn btn-custom d-flex align-items-center justify-content-center" id="loginButton">
        <i class="fas fa-sign-in-alt me-1"></i> تسجيل الدخول
    </button>



</form>

        <div class="text-center mt-3">
            <p class="text-muted small mb-0">© جميع الحقوق محفوظة للهيئة العربية الدولية للإعمار في فلسطين 2025</p>
        </div>
</div>
@endsection