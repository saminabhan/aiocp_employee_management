@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="fw-bold mb-4">إضافة ثابت جديد</h3>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('constants.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">اسم الثابت (أساسي)</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <!-- إذا فيه parent معّدل -->
                @if(request()->has('parent'))
                    <input type="hidden" name="parent" value="{{ request('parent') }}">

                    <div class="alert alert-info">
                        إضافة ثابت فرعي تحت اسم:
                        <strong>{{ $parentName }}</strong>
                    </div>
                @endif

                <button class="btn btn-primary">حفظ</button>
                <a href="{{ route('constants.index') }}" class="btn btn-secondary">رجوع</a>

            </form>

        </div>
    </div>

</div>
@endsection
