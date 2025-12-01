@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="fw-bold mb-4">إضافة ثابت جديد</h3>

    <div class="card shadow-sm">
        <div class="card-body">
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('constants.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">اسم الثابت (أساسي)</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                @if(request('parent') == 55)
                    <div class="mb-3 mt-3">
                        <label class="form-label fw-bold">اختر المحافظة</label>
                        <select name="governorate_id" class="form-select" required>
                            <option value="">اختر المحافظة</option>
                            @foreach($governorates as $gov)
                                <option value="{{ $gov->id }}">{{ $gov->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">الوصف</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description', $constant->description ?? '') }}</textarea>
                    </div>

                @endif

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
