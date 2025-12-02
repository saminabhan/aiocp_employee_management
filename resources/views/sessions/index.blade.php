@extends('layouts.app')

@section('title', 'جلسات دخول النظام')

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

    .sessions-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .sessions-table {
        width: 100%;
        border-collapse: collapse;
    }

    .sessions-table thead {
        background: #f8f9fa;
    }

    .sessions-table th, .sessions-table td {
        padding: 12px 10px;
        text-align: center;
        border: 1px solid #dee2e6;
        font-size: 14px;
    }

</style>
@endpush

@section('content')
<div class="container" dir="rtl">

    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-user-clock"></i>
            جلسات دخول النظام
        </h1>
    </div>

    <div class="sessions-card">
        @if($sessions->count())
            <div class="table-responsive">
                <table class="sessions-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>المستخدم</th>
                            <th>رقم الهاتف</th>
                            <th>IP</th>
                            <th>المتصفح / الجهاز</th>
                            <th>تاريخ آخر نشاط</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessions as $i => $session)
                            <tr>
                                <td>{{ $sessions->firstItem() + $i }}</td>

                                <td>{{ $session->user['name'] ?? 'غير متوفر' }}</td>

                                <td>{{ $session->user['phone'] ?? 'غير موجود بالجلسة' }}</td>

                                <td>{{ $session->ip_address }}</td>

                             <td style="max-width: 250px; text-align: right;">
    @php
        $agent = new Jenssegers\Agent\Agent();
        $agent->setUserAgent($session->user_agent);

        $device = $agent->device() ?: 'غير معروف';
        $browser = $agent->browser() ?: 'غير معروف';
        $platform = $agent->platform() ?: 'غير معروف';

        // تحديد نوع الأيقونة
        $icon = 'fa-desktop';
        if ($agent->isPhone()) $icon = 'fa-mobile-alt';
        elseif ($agent->isTablet()) $icon = 'fa-tablet-alt';
    @endphp

    <div style="display: flex; flex-direction: column;">
        <span>
            <i class="fas {{ $icon }} text-primary"></i>
            <b>{{ $device }}</b>
        </span>

        <small class="text-muted">
            <i class="fas fa-globe"></i>
            {{ $browser }} — {{ $platform }}
        </small>
    </div>
</td>


                                <td>{{ $session->last_activity_formatted }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $sessions->links('vendor.pagination.bootstrap-custom') }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">لا توجد جلسات دخول</p>
            </div>
        @endif
    </div>

</div>
@endsection
