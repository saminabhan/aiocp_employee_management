@extends('layouts.app')

@push('styles')
    <style>
            .header-card {
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
    .btn-back {
        background: #6c757d;
        color: white;
        padding: 10px 22px;
        border-radius: 8px;
        font-weight: 600;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-back:hover {
        background: #5a6268;
        color: #fff;
    }
    </style>
@endpush
@section('content')
<div class="container">

    <div class="header-card d-flex justify-content-between align-items-center flex-wrap">
        <h1 class="page-title">
            <i class="fas fa-edit"></i>
            تعديل ثابت
        </h1>

        <a href="{{ route('constants.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> رجوع
        </a>
    </div>


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
            <form action="{{ route('constants.update', $constant->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">اسم الثابت</label>
                    <input type="text" name="name" class="form-control" value="{{ $constant->name }}" required>
                </div>

                @if($constant->parent == null)
                    <div class="mb-3">
                        <label class="form-label fw-bold">التصنيف</label>
                        <select name="parent" class="form-select">
                            <option value="">أساسي</option>
                            @foreach($parents as $p)
                                @if($p->id != $constant->id)
                                    <option value="{{ $p->id }}" 
                                        {{ $constant->parent == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                @else
                    <input type="hidden" name="parent" value="{{ $constant->parent }}">
                @endif

                @if($constant->parent == 55)
                    <div class="mb-3 mt-3">
                        <label class="form-label fw-bold">اختر المحافظة</label>
                        <select name="governorate_id" class="form-select" required>
                            <option value="">اختر المحافظة</option>
                            @foreach($governorates as $gov)
                                <option value="{{ $gov->id }}" 
                                    {{ $constant->governorate_id == $gov->id ? 'selected' : '' }}>
                                    {{ $gov->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">الوصف</label>
                        <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $constant->description) }}</textarea>
                    </div>

                @endif



                <button class="btn btn-success px-4">
                    <i class="fas fa-save"></i> تحديث
                </button>

            </form>

        </div>
    </div>

</div>
@endsection
