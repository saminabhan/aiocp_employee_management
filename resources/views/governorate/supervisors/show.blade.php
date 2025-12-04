@extends('layouts.app')

@section('title', 'تفاصيل مشرف الحصر')

@push('styles')
<style>

.page-header {
    background: white;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.page-title {
    font-size: 24px;
    font-weight: 700;
    color: #0C4079;
    display: flex;
    align-items: center;
    gap: 10px;
}
.info-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.info-title {
    font-size: 18px;
    font-weight: 700;
    color: #0C4079;
    margin-bottom: 20px;
    border-bottom: 2px solid #f1f3f5;
    padding-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.team-card {
    background: #fafbfc;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e9ecef;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    transition: 0.25s;
}
.team-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 14px rgba(0,0,0,0.1);
}
.team-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 12px;
    margin-bottom: 15px;
    border-bottom: 2px solid #f1f3f5;
}
.team-icon {
    width: 40px;
    height: 40px;
    background: #e3f2fd;
    color: #0C4079;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}
.team-name {
    font-size: 17px;
    font-weight: 700;
    color: #0C4079;
}
.member-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.member-item {
    background: #fff;
    border: 1px solid #e9ecef;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    transition: 0.2s;
}
.member-item:hover {
    background: #f5faff;
    border-color: #0C4079;
}
.no-members {
    text-align: center;
    padding: 12px;
    color: #adb5bd;
    font-size: 13px;
}
</style>
@endpush

@section('content')
<div class="container" dir="rtl">

    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-user-tie"></i>
            تفاصيل مشرف الحصر
        </h1>
    </div>

    <div class="info-card">
        <div class="info-title">
            <i class="fas fa-id-card"></i>
            بيانات المشرف
        </div>

        <p><strong>الاسم:</strong> {{ $supervisor->name }}</p>
        <p><strong>الجوال:</strong> {{ $supervisor->phone }}</p>
        <p><strong>المدينة:</strong> {{ $supervisor->city->name ?? '—' }}</p>
        <p><strong>منطقة العمل الرئيسية:</strong> {{ $supervisor->mainWorkArea->name ?? '—' }}</p>
    </div>

    <div class="info-card">
        <div class="info-title">
            <i class="fas fa-users"></i>
            الفرق التابعة له ({{ count($teams) }})
        </div>

@if(count($teams) > 0)
    @foreach($teams as $team)
        <div class="team-card mb-3">

            <div class="team-header">
                <div class="team-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="team-name">
                    فريق: {{ $team->name }} <p class="text-muted">{{$team->subWorkArea->name}}</p>
                </div>
            </div>

            <strong class="mb-2 d-block">موظفين الفريق:</strong>

@php
    $engineerIds = $team->engineer_ids ?? [];

    if (!is_array($engineerIds)) {
        $engineerIds = json_decode($engineerIds, true) ?? [];
    }

    $engineers = \App\Models\Engineer::whereIn('id', $engineerIds)
        ->where('main_work_area_code', $supervisor->main_work_area_code)
        ->get();
@endphp

            @if($engineers->count() > 0)
                <ul class="member-list">
                    @foreach($engineers as $m)
                        <li class="member-item">
                            <span>{{ $m->full_name }}</span>
                            <span>{{ $m->mobile_1 }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="no-members">
                    <i class="fas fa-minus-circle"></i>
                    لا يوجد موظفين في هذا الفريق
                </div>
            @endif

        </div>
    @endforeach
@else
    <div class="no-members">
        <i class="fas fa-minus-circle"></i>
        لا توجد فرق تابعة لهذا المشرف
    </div>
@endif
    </div>

</div>
@endsection
