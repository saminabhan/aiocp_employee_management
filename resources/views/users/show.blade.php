@extends('layouts.app')

@section('title', 'عرض المستخدم')

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

    .profile-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #0C4079;
        margin-bottom: 18px;
        padding-bottom: 10px;
        border-bottom: 2px solid #eef1f6;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-title {
        font-weight: 600;
        font-size: 15px;
        color: #666;
        margin-bottom: 6px;
    }

    .info-value {
        font-size: 17px;
        font-weight: 700;
        color: #222;
    }

    .permission-badge {
        background: #e3f2fd;
        color: #0d47a1;
        padding: 8px 14px;
        margin: 6px;
        display: inline-block;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .btn-back {
        background: #6c757d;
        color: white;
        padding: 10px 22px;
        border-radius: 8px;
        font-weight: 600;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-back:hover {
        background: #5a6268;
        color: #fff;
    }
</style>
@endpush

@section('content')

<div class="page-wrapper">

    <div class="header-card d-flex justify-content-between align-items-center flex-wrap">
        <h1 class="page-title">
            <i class="fas fa-user-circle"></i>
            عرض بيانات المستخدم
        </h1>

        <a href="{{ route('users.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> رجوع
        </a>
    </div>

    <div class="profile-card">

        <div class="section-title">
            <i class="fas fa-id-card"></i> المعلومات الشخصية
        </div>

        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="info-title">الاسم الكامل:</div>
                <div class="info-value">{{ $user->name }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="info-title">اسم المستخدم:</div>
                <div class="info-value">{{ $user->username }}</div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="info-title">الدور:</div>
                <div class="info-value">{{ $user->role?->display_name ?? 'موظف' }}</div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="info-title">تاريخ الإنشاء:</div>
                <div class="info-value">{{ $user->created_at->format('Y-m-d') }}</div>
            </div>
        </div>

        <div class="section-title mt-4">
            <i class="fas fa-lock"></i> الصلاحيات
        </div>

        @php
            $rolePermissions = $user->role?->permissions ?? collect();
            $customPermissions = $user->permissions ?? collect();
        @endphp

        @if($user->role_id && $rolePermissions->count() > 0)
            @foreach($rolePermissions as $perm)
                <span class="permission-badge">
                    {{ $perm->display_name ?? $perm->name }}
                </span>
            @endforeach

        @elseif($customPermissions->count() > 0)
            @foreach($customPermissions as $perm)
                <span class="permission-badge">
                    {{ $perm->display_name ?? $perm->name }}
                </span>
            @endforeach

        @else
            <p class="text-muted">لا توجد صلاحيات لهذا المستخدم.</p>
        @endif

    </div>

</div>

@endsection
