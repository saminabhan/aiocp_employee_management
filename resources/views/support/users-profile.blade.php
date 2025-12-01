@extends('layouts.app')

@section('title', 'بيانات المستخدم')

@push('styles')
<style>
    .page-header {
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

    .details-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .info-item {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #e7e7e7;
    }

    .info-label {
        font-weight: 600;
        color: #0C4079;
        margin-bottom: 8px;
        font-size: 13px;
    }

    .info-value {
        font-size: 15px;
        color: #333;
    }

    .btn-back {
        background: #f0f0f0;
        color: #333;
        padding: 10px 20px;
        font-weight: 600;
        border: none;
    }

    .btn-back:hover {
        background: #e0e0e0;
        color: #333;
    }

</style>
@endpush

@section('content')
<div class="container" dir="rtl">

    {{-- Header --}}
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h1 class="page-title">
                <i class="fas fa-user"></i>
                بيانات المستخدم
            </h1>

            <a href="{{ redirect()->back()->getTargetUrl() }}" class="btn btn-back">
                <i class="fas fa-arrow-right me-2"></i>
                رجوع
            </a>
        </div>
    </div>

    {{-- User Details --}}
    <div class="details-card">

        <div class="info-grid">

            <div class="info-item">
                <div class="info-label">الاسم الكامل</div>
                <div class="info-value">{{ $user->name }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">نوع الحساب</div>
                <div class="info-value">
                   @php
                        $roles = [
                            'admin'        => 'مدير النظام',
                            'governorate_manager' => 'مدير محافظة',
                            'survey_supervisor'         => 'مشرف مهندسين الحصر',
                            'engineer'            => 'مهندس',
                            'supervisor'          => 'مشرف',
                            'user'                => 'مستخدم',
                        ];

                        $roleName = $roles[$user->role->name ?? ''] ?? 'غير محدد';
                    @endphp

                    <span class="badge bg-secondary">
                        {{ $roleName }}
                    </span>

                </div>
            </div>

            <div class="info-item">
                <div class="info-label">رقم الهاتف</div>
                <div class="info-value">{{ $user->phone ?? 'غير متوفر' }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">المحافظة</div>
                <div class="info-value">{{ $user->governorate->name ?? 'غير متوفر' }}</div>
            </div>

           

        </div>

    </div>

</div>
@endsection
