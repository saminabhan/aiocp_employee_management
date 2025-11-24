@extends('layouts.app')

@section('content')

<h3 class="mb-3">تفاصيل مشرف الحصر</h3>

<div class="card mb-4">
    <div class="card-body">
        <p><strong>الاسم:</strong> {{ $supervisor->name }}</p>
        <p><strong>الجوال:</strong> {{ $supervisor->phone }}</p>
        <p><strong>المدينة:</strong> {{ $supervisor->city->name ?? '—' }}</p>
        <p><strong>منطقة العمل الرئيسية:</strong> {{ $supervisor->mainWorkArea->name ?? '—' }}</p>
    </div>
</div>

<h4 class="mb-3">الفرق التابعة له</h4>

@foreach($teams as $team)
    <div class="card mb-3">
        <div class="card-header">
            فريق: {{ $team->name }}
        </div>
        <div class="card-body">
            <strong>موظفين الفريق:</strong>
            <ul>
                @foreach($team->members as $m)
                    <li>{{ $m->name }} — {{ $m->phone }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endforeach

@endsection
