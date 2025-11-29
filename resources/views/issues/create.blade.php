@extends('layouts.app')

@section('title', 'إضافة تذكرة جديدة')

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

    .btn-submit {
        background: #0C4079;
        border-color: #0C4079;
        padding: 12px 30px;
        font-weight: 600;
        width: 150px;
    }

    .btn-submit:hover {
        background: #083058;
        border-color: #083058;
    }

    .btn-back {
        background: #f0f0f0;
        color: #333;
        padding: 12px 30px;
        font-weight: 600;
        border: none;
        width: 150px;
    }

    .btn-back:hover {
        background: #e0e0e0;
        color: #333;
    }

    .required-star {
        color: #dc3545;
    }

    .priority-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .priority-card {
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .priority-card:hover {
        border-color: #0C4079;
        transform: translateY(-2px);
    }

    .priority-card input[type="radio"] {
        display: none;
    }

    .priority-card input[type="radio"]:checked + label {
        color: #0C4079;
        font-weight: 700;
    }

    .priority-card.low.selected {
        border-color: #1976d2;
        background: #e3f2fd;
    }

    .priority-card.medium.selected {
        border-color: #e65100;
        background: #fff3e0;
    }

    .priority-card.high.selected {
        border-color: #c62828;
        background: #ffebee;
    }

    .priority-icon {
        font-size: 32px;
        margin-bottom: 10px;
    }

    .priority-icon.low { color: #1976d2; }
    .priority-icon.medium { color: #e65100; }
    .priority-icon.high { color: #c62828; }
</style>
@endpush

@section('content')
<div class="container" dir="rtl">

    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-plus-circle"></i>
            إضافة تذكرة جديدة
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
        <form action="{{ route('issues.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

            {{-- المهندس (للأدمن فقط) --}}
                @if(count($engineers) > 0)
                <div class="mb-4">
                    <label class="form-label">
                        اختر المهندس <small class="text-muted">(اختياري - اتركه فارغاً لتذكرة شخصية)</small>
                    </label>
                    <select name="engineer_id" class="form-select">
                            <option value="">تذكرة شخصية</option>
                        @foreach($engineers as $engineer)
                            <option value="{{ $engineer->id }}" {{ old('engineer_id') == $engineer->id ? 'selected' : '' }}>
                                {{ $engineer->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="mb-4">
                <label class="form-label">
                    نوع المشكلة <span class="required-star">*</span>
                </label>
                <select name="problem_type_id" class="form-select" required>
                    <option value="">-- اختر نوع المشكلة --</option>
                    @foreach($problemTypes as $type)
                        <option value="{{ $type->id }}" {{ old('problem_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">
                    الأولوية <span class="required-star">*</span>
                </label>
                <div class="priority-cards">
                    <div class="priority-card low {{ old('priority') == 'low' ? 'selected' : '' }}" onclick="selectPriority('low')">
                        <div class="priority-icon low">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                        <input type="radio" name="priority" id="priority_low" value="low" {{ old('priority', 'medium') == 'low' ? 'checked' : '' }}>
                        <label for="priority_low" style="cursor: pointer; margin: 0;">منخفضة</label>
                    </div>

                    <div class="priority-card medium {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}" onclick="selectPriority('medium')">
                        <div class="priority-icon medium">
                            <i class="fas fa-minus"></i>
                        </div>
                        <input type="radio" name="priority" id="priority_medium" value="medium" {{ old('priority', 'medium') == 'medium' ? 'checked' : '' }}>
                        <label for="priority_medium" style="cursor: pointer; margin: 0;">متوسطة</label>
                    </div>

                    <div class="priority-card high {{ old('priority') == 'high' ? 'selected' : '' }}" onclick="selectPriority('high')">
                        <div class="priority-icon high">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                        <input type="radio" name="priority" id="priority_high" value="high" {{ old('priority') == 'high' ? 'checked' : '' }}>
                        <label for="priority_high" style="cursor: pointer; margin: 0;">عالية</label>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">
                    وصف المشكلة <span class="required-star">*</span>
                </label>
                <textarea name="description" class="form-control" rows="6" 
                          placeholder="اشرح المشكلة بالتفصيل..." required>{{ old('description') }}</textarea>
                <small class="text-muted">يرجى وصف المشكلة بوضوح لتسهيل المعالجة</small>
            </div>

<hr class="my-4">

<h4 class="mb-3 text-primary">المرفقات</h4>

<div id="attachments_wrapper">

    <div class="attachment-item border p-3 rounded mb-3">
        <div class="row">

            <div class="col-md-4">
                <label class="form-label">نوع المرفق</label>
                <select name="attachments[0][attachment_type_id]" class="form-select">
                    <option value="">اختر النوع</option>
                    @foreach($attachmentTypes as $att)
                        <option value="{{ $att->id }}">{{ $att->name }}</option>
                    @endforeach
                </select>
            </div>

          <div class="col-md-6">
    <label class="form-label">الملف</label>
    <input type="file" 
           name="attachments[0][file]" 
           class="form-control"
           accept="image/*,application/pdf,video/*">
    <small class="text-muted">
        الصور: JPG, PNG, WebP, GIF, SVG, HEIC | 
        الفيديوهات: MP4, MOV, AVI وغيرها | 
        المستندات: PDF
        (الحد الأقصى: 20 ميجابايت)
    </small>
</div>


            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger w-100 remove-attachment">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

        </div>
    </div>

</div>

<button type="button" id="addAttachment" class="btn btn-secondary mb-4">
    <i class="fas fa-plus-circle me-1"></i> إضافة مرفق آخر
</button>

            <div class="d-flex justify-content-between gap-3">
                <a href="{{ route('issues.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-right me-2"></i>
                    رجوع
                </a>

                <button type="submit" class="btn btn-primary btn-submit">
                    <i class="fas fa-save me-2"></i>
                    حفظ
                </button>
            </div>

        </form>
    </div>

</div>

<script>
function selectPriority(priority) {
    document.querySelectorAll('.priority-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    document.querySelector('.priority-card.' + priority).classList.add('selected');
    
    document.getElementById('priority_' + priority).checked = true;
}
</script>

<script>
let attachmentIndex = 1;

document.getElementById('addAttachment').addEventListener('click', function () {

    let wrapper = document.getElementById('attachments_wrapper');

    let html = `
    <div class="attachment-item border p-3 rounded mb-3">
        <div class="row">

            <div class="col-md-4">
                <label class="form-label">نوع المرفق</label>
                <select name="attachments[${attachmentIndex}][attachment_type_id]" class="form-select" required>
                    <option value="">اختر النوع</option>
                    @foreach($attachmentTypes as $att)
                        <option value="{{ $att->id }}">{{ $att->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">الملف</label>
                <input type="file" name="attachments[${attachmentIndex}][file]" 
                       class="form-control" accept="image/*,application/pdf,video/mp4" required>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger w-100 remove-attachment">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

        </div>
    </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);
    attachmentIndex++;
});

document.addEventListener('click', function (e) {
    if (e.target.closest('.remove-attachment')) {
        e.target.closest('.attachment-item').remove();
    }
});
</script>

@endsection