@extends('layouts.app')

@section('title', 'تفاصيل الخطأ')

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

    /* ====== CARD ====== */
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

    /* ====== INFO ITEMS ====== */
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
        direction: ltr;
    }

    /* BADGES */
    .badge-pill {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-red {
        background: #ffebee;
        color: #c62828;
    }

    .badge-blue {
        background: #e3f2fd;
        color: #0d47a1;
    }

    /* STACK TRACE BOX */
    .trace-box {
        white-space: pre-wrap;
        direction: ltr;
        max-height: 350px;
        overflow: auto;
        background: #fafafa;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 12px;
        font-size: 13px;
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
        <i class="fas fa-exclamation-triangle"></i>
        تفاصيل الخطأ
    </h1>
</div>

<div class="layout-wrapper">

    <!-- ========= ERROR DETAILS ========= -->
    <div class="card-modern">

        <h4 class="section-title">
            <i class="fas fa-info-circle"></i>
            معلومات الخطأ
        </h4>

        <div class="info-item">
            <div class="info-label">الرسالة:</div>
            <div class="info-value text-danger">
                {{ $error->message ?? '-' }}
            </div>
        </div>

        <div class="info-item">
            <div class="info-label">الملف:</div>
            <div class="info-value text-muted">
                {{ $error->file ?? '-' }}
            </div>
        </div>

        <div class="info-item">
            <div class="info-label">رقم السطر:</div>
            <div class="info-value text-muted">
                {{ $error->line ?? '-' }}
            </div>
        </div>

        <div class="info-item">
            <div class="info-label">نوع الخطأ:</div>
            <div class="info-value">
                <span class="badge-pill badge-blue">
                    {{ $error->type ?? 'غير معروف' }}
                </span>
            </div>
        </div>

        <div class="info-item">
            <div class="info-label">تاريخ السجل:</div>
            <div class="info-value text-muted">
                {{ $error->created_at?->format('Y-m-d H:i:s') }}
            </div>
        </div>

        <h4 class="section-title mt-4">
            <i class="fas fa-code"></i>
            تفاصيل Stack Trace
        </h4>

        <div class="trace-box">
{{ $error->trace ?? 'لا يوجد تفاصيل' }}
        </div>

        <div class="mt-3">
            <a href="{{ route('errors.index') }}" class="btn btn-primary btn-modern">
                <i class="fas fa-arrow-right"></i> رجوع
            </a>
        </div>

    </div>

</div>

@endsection
