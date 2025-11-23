@extends('layouts.app')

@section('title', 'تفاصيل الفريق')

@push('styles')
<style>

    /* ====== Layout ====== */

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

    .page-grid {
        display: grid;
        grid-template-columns: 0.9fr 1.1fr;
        gap: 20px;
        margin-top: 10px;
    }

    @media(max-width: 992px) {
        .page-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ====== Card ====== */
    .details-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e6e6e6;
        padding: 18px 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }

    .card-title {
        font-size: 17px;
        font-weight: 700;
        color: #0C4079;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f0f0f0;
    }

    /* ====== Team Info ====== */
    .detail-row {
        display: flex;
        padding: 6px 0;
        font-size: 14px;
        border-bottom: 1px dashed #f0f0f0;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        width: 130px;
        font-weight: 600;
        color: #0C4079;
    }

    .detail-value {
        font-weight: 500;
    }

    .badge-active {
        background: #e8f5e9;
        color: #388e3c;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    .badge-inactive {
        background: #ffebee;
        color: #c62828;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    /* ====== Engineers List ====== */
    .engineer-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 16px;
        border: 1px solid #e8e8e8;
        border-radius: 10px;
        background: #fafafa;
        transition: .2s;
        margin-bottom: 10px;
        text-decoration: none !important;
    }

    .engineer-item:hover {
        background: #eef3ff;
        border-color: #d0d8ff;
        transform: translateX(-4px);
    }

    .engineer-info {
        font-size: 14px;
        color: #333;
    }

    .engineer-info strong {
        font-size: 15px;
        color: #0C4079;
    }

    .engineer-info small {
        color: #777;
    }

    /* ===== Buttons ===== */
    .btn-sm-custom {
        padding: 6px 13px;
        font-size: 13px;
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-users-cog"></i>
        إدارة الفرق
    </h1>
</div>

<div class="page-grid">

    {{-- RIGHT SIDE — TEAM DETAILS --}}
    <div class="details-card">

        <h4 class="card-title">
            <i class="fas fa-info-circle"></i>
            معلومات الفريق
        </h4>

        <div class="detail-row">
            <div class="detail-label">اسم الفريق:</div>
            <div class="detail-value">{{ $team->name }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">المحافظة:</div>
            <div class="detail-value">
                <span class="badge-active">
                    {{ $team->governorate->name ?? 'غير محدد' }}
                </span>
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">الحالة:</div>
            <div class="detail-value">
                @if($team->is_active)
                    <span class="badge-active">مفعل</span>
                @else
                    <span class="badge-inactive">معطل</span>
                @endif
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-label">تاريخ الإنشاء:</div>
            <div class="detail-value">{{ $team->created_at->format('Y-m-d H:i') }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">آخر تحديث:</div>
            <div class="detail-value">{{ $team->updated_at->format('Y-m-d H:i') }}</div>
        </div>

        <div class="mt-3 d-flex gap-2">
            @if(user_can('teams.edit'))
            <a href="{{ route('teams.edit', $team) }}" class="btn btn-warning btn-sm-custom">
                <i class="fas fa-edit"></i> تعديل
            </a>
            @endif

            <a href="{{ route('teams.index') }}" class="btn btn-primary btn-sm-custom">
                <i class="fas fa-arrow-right"></i> رجوع
            </a>
        </div>

    </div>


    {{-- LEFT SIDE — ENGINEERS --}}
    <div class="details-card">

        <h4 class="card-title">
            <i class="fas fa-users"></i>
            المهندسين في الفريق
            <span class="badge-active">{{ $engineers->count() }}</span>
        </h4>

        @if($engineers->count() > 0)
            @foreach($engineers as $engineer)
                <a href="{{ route('engineers.show', $engineer) }}" class="engineer-item">
                    <div class="engineer-info">
                        <strong>
                            <i class="fas fa-user-tie text-primary"></i>
                            {{ $engineer->full_name }}
                        </strong><br>
                        <small><i class="fas fa-envelope"></i> {{ $engineer->email }}</small>
                    </div>

                    <span class="badge-active">مهندس</span>
                </a>
            @endforeach
        @else
            <div class="text-center py-3">
                <i class="fas fa-user-slash fa-2x text-muted"></i>
                <p class="text-muted mt-2">لا يوجد مهندسين في هذا الفريق</p>

                @if(user_can('teams.edit'))
                <a href="{{ route('teams.edit', $team) }}" class="btn btn-primary btn-sm-custom">
                    <i class="fas fa-plus"></i> إضافة مهندس
                </a>
                @endif
            </div>
        @endif

    </div>

</div>

@endsection