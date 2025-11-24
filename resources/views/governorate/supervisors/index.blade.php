@extends('layouts.app')

@section('content')

<h3 class="mb-4">مشرفين الحصر في محافظتك</h3>

<table class="table table-striped">
    <thead>
        <tr>
            <th>الاسم</th>
            <th>رقم الجوال</th>
            <th>المدينة</th>
            <th>منطقة العمل الرئيسية</th>
            <th>تفاصيل</th>
        </tr>
    </thead>
    <tbody>
        @foreach($supervisors as $s)
        <tr>
            <td>{{ $s->name }}</td>
            <td>{{ $s->phone }}</td>
            <td>{{ $s->city->name ?? '—' }}</td>
            <td>{{ $s->mainWorkArea->name ?? '—' }}</td>
            <td>
                <a href="{{ route('governorate.supervisors.show', $s->id) }}" class="btn btn-primary btn-sm">
                    عرض
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
