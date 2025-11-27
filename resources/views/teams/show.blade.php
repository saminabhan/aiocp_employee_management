@extends('layouts.app')

@section('title', 'تفاصيل الفريق')

@push('styles')
<style>

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

    .layout-wrapper {
        display: flex;
        gap: 25px;
    }

    @media(max-width: 992px) {
        .layout-wrapper {
            flex-direction: column;
        }
    }

    /* ====== CARDS ====== */
    .card-modern {
        background: #fff;
        border-radius: 14px;
        padding: 22px;
        flex: 1;
        border: 1px solid #e8e8e8;
        box-shadow: 0 3px 6px rgba(0,0,0,0.06);
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #0C4079;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 12px;
        margin-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    /* ====== TEAM INFO LINES ====== */
    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        font-size: 15px;
        border-bottom: 1px dashed #f1f1f1;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #0C4079;
    }

    .info-value {
        font-weight: 500;
        text-align: left;
    }

    /* ====== BADGES ====== */
    .badge-pill {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-green {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .badge-red {
        background: #ffebee;
        color: #c62828;
    }

    .badge-blue {
        background: #e3f2fd;
        color: #0d47a1;
    }

    /* ====== ENGINEER ITEM ====== */
    .engineer-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px;
        margin-bottom: 8px;
        border: 1px solid #e8e8e8;
        border-radius: 12px;
        background: #fafbfd;
        text-decoration: none;
        transition: .2s;
    }

    .engineer-card:hover {
        background: #eef4ff;
        transform: translateX(-4px);
        border-color: #c4d7ff;
    }

    .eng-name {
        font-size: 15px;
        font-weight: 600;
        color: #0C4079;
    }

    .btn-modern {
        padding: 7px 15px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
    }

</style>
@endpush

@section('content')

<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-users-cog"></i>
        تفاصيل الفريق
    </h1>
</div>

<div class="layout-wrapper">

    <div class="card-modern">

        <h4 class="section-title">
            <i class="fas fa-info-circle"></i>
            معلومات الفريق
        </h4>

        <div class="info-item">
            <div class="info-label">اسم الفريق:</div>
            <div class="info-value">{{ $team->name }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">المحافظة:</div>
            <div class="info-value">
                <span class="badge-pill badge-blue">
                    {{ $team->governorate->name ?? 'غير محدد' }}
                </span>
            </div>
        </div>

        <div class="info-item">
            <div class="info-label">الحالة:</div>
            <div class="info-value">
                @if($team->is_active)
                    <span class="badge-pill badge-green">مفعل</span>
                @else
                    <span class="badge-pill badge-red">معطل</span>
                @endif
            </div>
        </div>

        <div class="info-item">
            <div class="info-label">كود العمل الرئيسي:</div>
            <div class="info-value">
                @if($team->main_work_area_code)
                    <span class="badge-pill badge-blue">{{ $team->mainWorkArea->name }}</span>
                @else
                    <span class="badge-pill badge-red">غير موجود</span>
                @endif
            </div>
        </div>

        <div class="info-item">
            <div class="info-label">كود العمل الفرعي:</div>
            <div class="info-value">
                @if($team->sub_work_area_code)
                    <span class="badge-pill badge-blue">{{ $team->subWorkArea->name }}</span>
                @else
                    <span class="badge-pill badge-red">غير موجود</span>
                @endif
            </div>
        </div>

        <div class="info-item">
            <div class="info-label">تاريخ الإنشاء:</div>
            <div class="info-value">{{ $team->created_at->format('Y-m-d H:i') }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">آخر تحديث:</div>
            <div class="info-value">{{ $team->updated_at->format('Y-m-d H:i') }}</div>
        </div>

        <div class="mt-3 d-flex gap-2">

            @if(user_can('teams.edit'))
            <a href="{{ route('teams.edit', $team) }}" class="btn btn-warning btn-modern">
                <i class="fas fa-edit"></i> تعديل
            </a>
            @endif

            <a href="{{ route('teams.index') }}" class="btn btn-primary btn-modern">
                <i class="fas fa-arrow-right"></i> رجوع
            </a>
        </div>

    </div>

    <div class="card-modern">

        <h4 class="section-title">
            <i class="fas fa-users"></i>
            المهندسين في الفريق
            <span class="badge-pill badge-blue">{{ $engineers->count() }}</span>
        </h4>

        @if($engineers->count() > 0)

            @foreach($engineers as $engineer)
                <a href="{{ route('engineers.show', $engineer) }}" class="engineer-card">
                    <span class="eng-name">
                        <i class="fas fa-user-tie"></i>
                        {{ $engineer->full_name }}
                    </span>
                    <span class="badge-pill badge-green">مهندس</span>
                </a>
            @endforeach

        @else
            <div class="text-center py-4">
                <i class="fas fa-user-slash fa-2x text-muted"></i>
                <div class="mt-2 text-muted">لا يوجد مهندسين في هذا الفريق</div>

                @if(user_can('teams.edit'))
                <a href="{{ route('teams.edit', $team) }}" class="btn btn-primary btn-modern mt-2">
                    <i class="fas fa-plus"></i> إضافة مهندس
                </a>
                @endif
            </div>
        @endif

    </div>

</div>

@endsection
