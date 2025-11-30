@extends('layouts.app')

@section('title', 'تعديل الدوام')

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

    .form-label {
        font-weight: 600;
        color: #0C4079;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border-radius: 8px;
        padding: 12px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0C4079;
        box-shadow: 0 0 0 0.2rem rgba(12, 64, 121, 0.25);
    }

    .required-star {
        color: #dc3545;
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

    .badge-type {
        display: inline-block;
        padding: 5px 12px;
        font-size: 13px;
        border-radius: 6px;
        font-weight: 600;
        margin-top: 5px;
    }

    .badge-engineer {
        background: #e3f2fd;
        color: #1976d2;
    }

    .badge-supervisor {
        background: #e8f5e9;
        color: #388e3c;
    }

    .status-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }

    .status-card {
        border: 2px solid #ddd;
        padding: 18px;
        border-radius: 12px;
        text-align: center;
        cursor: pointer;
        transition: .3s;
    }

    .status-card i {
        font-size: 35px;
        margin-bottom: 10px;
    }

    .status-card.present.active {
        border-color: #28a745;
        background: #d4edda;
    }
    .status-card.absent.active {
        border-color: #dc3545;
        background: #f8d7da;
    }
    .status-card.leave.active {
        border-color: #ffc107;
        background: #fff3cd;
    }
    .status-card.weekend.active {
        border-color: #6c757d;
        background: #e9ecef;
    }

    .btn-submit {
        background: #0C4079;
        border-color: #0C4079;
        padding: 12px 30px;
        font-weight: 600;
        width: 160px;
    }

    .btn-submit:hover {
        background: #083058;
        border-color: #083058;
    }

    .btn-back {
        background: #f0f0f0;
        color: #333;
        padding: 12px 30px;
        width: 160px;
        font-weight: 600;
        border: none;
    }

    .btn-back:hover {
        background: #e0e0e0;
        color: #333;
    }

    .info-alert {
        background: #e8f4fd;
        border-right: 4px solid #0C4079;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        color: #0C4079;
    }
</style>
@endpush

@section('content')
<div class="container" dir="rtl">

    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-edit"></i>
            تعديل الدوام
        </h1>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">

        <div class="user-info-box">
            @if($attendance->user_type === 'engineer' && $attendance->engineer)
                
                <img src="{{ $attendance->engineer->personal_image 
                        ? asset('storage/'.$attendance->engineer->personal_image)
                        : 'https://ui-avatars.com/api/?name='.urlencode($attendance->engineer->name).'&background=0C4079&color=fff&size=120' }}" 
                     alt="{{ $attendance->engineer->name }}">

                <div class="user-info-details">
                    <h5>{{ $attendance->engineer->full_name }}</h5>
                    <p>
                        <i class="fas fa-id-badge ms-1"></i>
                        {{ $attendance->engineer->national_id }}
                    </p>
                    <span class="badge-type badge-engineer">
                        <i class="fas fa-hard-hat ms-1"></i>
                        مهندس حصر
                    </span>
                </div>

            @else

                <img src="https://ui-avatars.com/api/?name={{ urlencode($attendance->supervisor->name) }}&background=0C4079&color=fff&size=120" 
                     alt="{{ $attendance->supervisor->name }}">

                <div class="user-info-details">
                    <h5>{{ $attendance->supervisor->name }}</h5>
                    <p>
                        <i class="fas fa-user ms-1"></i>
                        {{ $attendance->supervisor->username }}
                    </p>
                    <span class="badge-type badge-supervisor">
                        <i class="fas fa-user-tie ms-1"></i>
                        مشرف حصر
                    </span>
                </div>

            @endif
        </div>

        <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="form-label">تاريخ الدوام</label>
                
                <div class="info-alert">
                    <i class="fas fa-calendar-alt ms-2"></i>
                    {{ $attendance->attendance_date->format('Y-m-d') }}
                    ({{ ['Saturday'=>'السبت','Sunday'=>'الأحد','Monday'=>'الاثنين','Tuesday'=>'الثلاثاء','Wednesday'=>'الأربعاء','Thursday'=>'الخميس','Friday'=>'الجمعة'][$attendance->attendance_date->format('l')] }})
                </div>
            </div>

            <label class="form-label">حالة الحضور <span class="required-star">*</span></label>

            <div class="status-cards mb-4">

                <label class="status-card present {{ $attendance->status == 'present' ? 'active' : '' }}">
                    <input type="radio" name="status" value="present" hidden {{ $attendance->status == 'present' ? 'checked' : '' }}>
                    <i class="fas fa-check-circle text-success"></i>
                    <div>حاضر</div>
                </label>

                <label class="status-card absent {{ $attendance->status == 'absent' ? 'active' : '' }}">
                    <input type="radio" name="status" value="absent" hidden {{ $attendance->status == 'absent' ? 'checked' : '' }}>
                    <i class="fas fa-times-circle text-danger"></i>
                    <div>غائب</div>
                </label>

                <label class="status-card leave {{ $attendance->status == 'leave' ? 'active' : '' }}">
                    <input type="radio" name="status" value="leave" hidden {{ $attendance->status == 'leave' ? 'checked' : '' }}>
                    <i class="fas fa-briefcase text-warning"></i>
                    <div>إجازة</div>
                </label>

                <label class="status-card weekend {{ $attendance->status == 'weekend' ? 'active' : '' }}">
                    <input type="radio" name="status" value="weekend" hidden {{ $attendance->status == 'weekend' ? 'checked' : '' }}>
                    <i class="fas fa-calendar-times text-secondary"></i>
                    <div>عطلة</div>
                </label>

            </div>

            @error('status')
                <div class="text-danger small mb-3">{{ $message }}</div>
            @enderror

            <div class="mb-4">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="أدخل أي ملاحظات...">{{ old('notes', $attendance->notes) }}</textarea>
            </div>

            <div class="alert alert-info mb-4">
                <i class="fas fa-info-circle ms-1"></i>
                <small>
                    تم التسجيل بواسطة:
                    <strong>{{ $attendance->recordedBy->name ?? 'غير معروف' }}</strong>
                    في {{ $attendance->created_at->format('Y-m-d H:i') }}
                </small>
            </div>

            {{-- الأزرار --}}
            <div class="d-flex justify-content-between">
                <a href="{{ route('attendance.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-right"></i> رجوع
                </a>

                <button type="submit" class="btn btn-submit btn-primary">
                    <i class="fas fa-save"></i> حفظ التعديلات
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
        this.querySelector("input").checked = true;
    });
});
</script>
@endpush