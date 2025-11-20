@extends('layouts.app')

@section('title', 'عرض المستخدم')

@push('styles')
<style>
    .profile-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .info-title {
        font-weight: 600;
        font-size: 15px;
        color: #555;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 17px;
        font-weight: 600;
        color: #222;
    }

    .section-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #eee;
    }

    .permission-badge {
        background: #e3f2fd;
        color: #0d47a1;
        padding: 7px 12px;
        margin: 5px;
        display: inline-block;
        border-radius: 10px;
        font-size: 14px;
    }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between mb-4">
    <h3 class="fw-bold">عرض بيانات المستخدم</h3>

    <a href="{{ route('users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> رجوع
    </a>
</div>

<div class="profile-card">

    {{-- معلومات المستخدم --}}
    <div class="section-title">
        <i class="fas fa-user-circle"></i> المعلومات الشخصية
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

@endsection
