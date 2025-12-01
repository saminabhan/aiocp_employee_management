@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">تعديل ثابت</h3>
        <a href="{{ route('constants.index') }}" class="btn btn-secondary">
            رجوع
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
