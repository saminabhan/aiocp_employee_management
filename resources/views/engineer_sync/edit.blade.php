@extends('layouts.app')

@section('title', 'تعديل المزامنة')

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

    .form-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .user-info-box {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .user-info-box img {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #0C4079;
    }

    .user-info-details h5 {
        margin: 0 0 5px;
        font-size: 18px;
        color: #0C4079;
        font-weight: 700;
    }

    .user-info-details p {
        margin: 0;
        font-size: 14px;
        color: #6c757d;
    }

    .badge-engineer {
        background: #e3f2fd;
        color: #1976d2;
        padding: 5px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
    }

    .status-cards {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 25px;
    }

    .status-card {
        border: 2px solid #ddd;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        cursor: pointer;
        transition: .3s;
    }

    .status-card i {
        font-size: 35px;
        margin-bottom: 10px;
    }

    .status-card.active {
        border-color: #0C4079;
        background: #eef4fb;
    }

    .btn-submit {
        background: #0C4079;
        border-color: #0C4079;
        padding: 12px 30px;
        font-weight: 600;
        width: 160px;
    }

    .btn-back {
        background: #f0f0f0;
        padding: 12px 30px;
        width: 160px;
        font-weight: 600;
        border: none;
    }
</style>
@endpush

@section('content')
<div class="container" dir="rtl">

    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-sync-alt"></i>
            تعديل مزامنة المهندس
        </h1>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">

        {{-- معلومات المهندس --}}
        <div class="user-info-box">
            <img src="{{ $sync->engineer->personal_image
                ? asset('storage/'.$sync->engineer->personal_image)
                : 'https://ui-avatars.com/api/?name='.urlencode($sync->engineer->full_name).'&background=0C4079&color=fff' }}">

            <div class="user-info-details">
                <h5>{{ $sync->engineer->full_name }}</h5>
                <p>
                    <i class="fas fa-id-badge ms-1"></i>
                    {{ $sync->engineer->national_id }}
                </p>
                <span class="badge-engineer">
                    <i class="fas fa-sync-alt ms-1"></i>
                    مزامنة مهندس
                </span>
            </div>
        </div>

        {{-- التاريخ --}}
        <div class="alert alert-info mb-4">
            <i class="fas fa-calendar-alt ms-1"></i>
            تاريخ المزامنة:
            <strong>{{ $sync->sync_date->format('Y-m-d') }}</strong>
        </div>

        {{-- الفورم --}}
        <form action="{{ route('engineer-sync.update', $sync->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label class="form-label mb-3">حالة المزامنة</label>

            <div class="status-cards">

                <label class="status-card {{ $sync->is_synced ? 'active' : '' }}">
                    <input type="radio"
                        name="is_synced"
                        value="1"
                        hidden
                        {{ $sync->is_synced ? 'checked' : '' }}>
                    <i class="fas fa-check-circle text-success"></i>
                    <div>تمت المزامنة</div>
                </label>

                <label class="status-card {{ !$sync->is_synced ? 'active' : '' }}">
                    <input type="radio"
                        name="is_synced"
                        value="0"
                        hidden
                        {{ !$sync->is_synced ? 'checked' : '' }}>
                    <i class="fas fa-times-circle text-danger"></i>
                    <div>لم تتم المزامنة</div>
                </label>


            </div>

            <div class="mb-4">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $sync->notes) }}</textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('engineer-sync.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-right"></i> رجوع
                </a>

                <button type="submit" class="btn btn-submit btn-primary">
                    <i class="fas fa-save"></i> حفظ
                </button>
            </div>

        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.status-card').forEach(card => {
    card.addEventListener('click', function () {
        document.querySelectorAll('.status-card').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        this.querySelector('input').checked = true;
    });
});
</script>
@endpush
