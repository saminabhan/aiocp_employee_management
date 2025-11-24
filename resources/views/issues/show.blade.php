@extends('layouts.app')

@section('title', 'تفاصيل التذكرة')

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

    .details-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .info-item {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #e7e7e7;
    }

    .info-label {
        font-weight: 600;
        color: #0C4079;
        margin-bottom: 8px;
        font-size: 13px;
    }

    .info-value {
        font-size: 15px;
        color: #333;
    }

    .description-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #e7e7e7;
        margin-bottom: 25px;
    }

    .solution-box {
        background: #e8f5e9;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #a5d6a7;
        margin-bottom: 25px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #0C4079;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e8e8e8;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .badge-status {
        padding: 6px 14px;
        border-radius: 15px;
        font-size: 13px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-status.open { background: #ffebee; color: #c62828; }
    .badge-status.in_progress { background: #fff3e0; color: #e65100; }
    .badge-status.closed { background: #e8f5e9; color: #2e7d32; }

    .badge-priority {
        padding: 6px 14px;
        border-radius: 15px;
        font-size: 13px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-priority.low { background: #e3f2fd; color: #1976d2; }
    .badge-priority.medium { background: #fff3e0; color: #e65100; }
    .badge-priority.high { background: #ffebee; color: #c62828; }

    .btn-back {
        background: #f0f0f0;
        color: #333;
        padding: 10px 20px;
        font-weight: 600;
        border: none;
    }

    .btn-back:hover {
        background: #e0e0e0;
        color: #333;
    }

    .btn-update-status {
        background: #0C4079;
        border-color: #0C4079;
        padding: 10px 20px;
        font-weight: 600;
    }

    .btn-update-status:hover {
        background: #083058;
        border-color: #083058;
    }

    .btn-delete {
        background: #dc3545;
        border-color: #dc3545;
        padding: 10px 20px;
        font-weight: 600;
    }

    .btn-delete:hover {
        background: #c82333;
        border-color: #bd2130;
    }

    .modal-header {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
    }
    .attachment-preview img:hover {
        opacity: 0.9;
        transition: opacity 0.3s ease;
    }
    
    .card-footer .btn {
        font-size: 13px;
        font-weight: 600;
    }
        .badge-custom {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-primary {
        background: #e3f2fd;
        color: #1976d2;
    }

    .badge-primary:hover {
        background: #bbdefb;
        color: #1976d2;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }
    .badge-success:hover {
        background: #c3e6cb;
        color: #155724;
    }
</style>
@endpush

@section('content')
<div class="container" dir="rtl">

    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h1 class="page-title">
                <i class="fas fa-ticket-alt"></i>
                تفاصيل التذكرة #{{ $issue->id }}
            </h1>
            <a href="{{ route('issues.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-right me-2"></i>
                رجوع
            </a>
        </div>
    </div>

@if (Session::has('success'))
<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        toast: true,
        position: 'bottom-start',
        icon: 'success',
        title: '{{ Session::get("success") }}',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        backdrop: false,
        customClass: {
            popup: 'medium-small-toast'
        }
    });
});
</script>
@endif

    <!-- Details Card -->
    <div class="details-card">
        
        <!-- Basic Info -->
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">نوع المشكلة</div>
                <div class="info-value">{{ $issue->problem->name ?? 'غير محدد' }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">مقدم التذكرة</div>
                <div class="info-value">
                    {{ $issue->submitter_name }}
                    <span class="badge bg-secondary ms-2">{{ $issue->submitter_type }}</span>
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">الأولوية</div>
                <div class="info-value">
                    <span class="badge-priority {{ $issue->priority }}">
                        @if($issue->priority == 'low') منخفضة
                        @elseif($issue->priority == 'medium') متوسطة
                        @else عالية @endif
                    </span>
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">الحالة</div>
                <div class="info-value">
                    <span class="badge-status {{ $issue->status }}">
                        @if($issue->status == 'open') مفتوحة
                        @elseif($issue->status == 'in_progress') قيد المعالجة
                        @else مغلقة @endif
                    </span>
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">تاريخ الإنشاء</div>
                <div class="info-value">{{ $issue->created_at->format('Y-m-d H:i') }}</div>
            </div>

            @if($issue->created_at != $issue->updated_at)
                <div class="info-item">
                    <div class="info-label">آخر تحديث</div>
                    <div class="info-value">{{ $issue->updated_at->format('Y-m-d H:i') }}</div>
                </div>
            @endif
        </div>

        <!-- Description -->
        <div class="section-title">
            <i class="fas fa-align-right"></i>
            وصف المشكلة
        </div>
        <div class="description-box">
            {{ $issue->description }}
        </div>
{{-- استبدل قسم المرفقات في ملف show.blade.php بهذا الكود --}}

@if($issue->attachments && $issue->attachments->count() > 0)
<div class="section-title mt-4">
    <i class="fas fa-paperclip"></i>
    المرفقات ({{ $issue->attachments->count() }})
</div>

<div class="row g-3">
    @foreach($issue->attachments as $attachment)
        <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; overflow: hidden;">
                
                {{-- Header: نوع المرفق --}}
                <div class="card-header bg-light border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge-custom badge-primary">
                            <i class="fas fa-tag me-1"></i>
                            {{ $attachment->type->name ?? 'غير محدد' }}
                        </span>
                        <small class="text-muted">
                            {{ number_format($attachment->file_size / 1024, 2) }} KB
                        </small>
                    </div>
                </div>

                {{-- Body: معاينة الملف --}}
                <div class="card-body p-0">
                    <div class="attachment-preview" style="height: 250px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                        
                        {{-- صورة --}}
                        @if(str_starts_with($attachment->mime_type, 'image/'))
                            <a href="{{ asset('storage/' . $attachment->file_path) }}" 
                               target="_blank" 
                               class="d-block w-100 h-100">
                                <img src="{{ asset('storage/' . $attachment->file_path) }}" 
                                     alt="{{ $attachment->file_name }}"
                                     class="w-100 h-100"
                                     style="object-fit: cover; cursor: pointer;"
                                     loading="lazy">
                            </a>

                        {{-- فيديو --}}
                        @elseif(str_starts_with($attachment->mime_type, 'video/'))
                            <video controls 
                                   class="w-100 h-100" 
                                   style="background: #000; object-fit: contain;"
                                   preload="metadata">
                                <source src="{{ asset('storage/' . $attachment->file_path) }}" 
                                        type="{{ $attachment->mime_type }}">
                                متصفحك لا يدعم تشغيل الفيديو
                            </video>

                        {{-- PDF --}}
                        @elseif($attachment->mime_type === 'application/pdf')
                            <div class="text-center py-4">
                                <i class="fas fa-file-pdf fa-5x text-danger mb-3"></i>
                                <p class="fw-bold mb-2">ملف PDF</p>
                                <p class="text-muted small mb-0">{{ $attachment->file_name }}</p>
                            </div>

                        {{-- ملفات أخرى --}}
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-file fa-5x text-secondary mb-3"></i>
                                <p class="fw-bold mb-2">ملف مرفق</p>
                                <p class="text-muted small mb-0">{{ $attachment->file_name }}</p>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Footer: الأزرار --}}
                <div class="card-footer bg-white border-top">
                    <div class="d-grid gap-2">
                        {{-- زر المعاينة للصور والـ PDF --}}
                        @if(str_starts_with($attachment->mime_type, 'image/') || $attachment->mime_type === 'application/pdf')
                            <a href="{{ asset('storage/' . $attachment->file_path) }}" 
                               target="_blank" 
                               class="badge-custom badge-primary btn btn-sm ">
                                <i class="fas fa-eye me-1"></i>
                                معاينة
                            </a>
                        @endif

                        {{-- زر التحميل --}}
                        <a href="{{ asset('storage/' . $attachment->file_path) }}" 
                           download="{{ $attachment->file_name }}"
                           class="badge-custom badge-success btn btn-sm ">
                            <i class="fas fa-download me-1"></i>
                            تحميل المرفق
                        </a>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
</div>
@endif

        <!-- Solution (if exists) -->
        @if($issue->solution)
            <div class="section-title">
                <i class="fas fa-check-circle"></i>
                الحل
            </div>
            <div class="solution-box">
                {{ $issue->solution }}
            </div>
        @endif

        <!-- Actions -->
        <hr class="my-4">
        <div class="d-flex justify-content-between gap-3 flex-wrap">
            <div class="d-flex gap-2">
                @if(auth()->user()->hasRole(['admin', 'support']) && $issue->status != 'closed')
                    <button type="button" class="btn btn-primary btn-update-status" 
                            data-bs-toggle="modal" 
                            data-bs-target="#updateStatusModal">
                        <i class="fas fa-edit me-2"></i>
                        تحديث الحالة
                    </button>
                @endif
                
                @if(auth()->user()->hasRole('admin'))
                    <button type="button" class="btn btn-danger btn-delete" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-2"></i>
                        حذف
                    </button>
                @endif
            </div>
        </div>

    </div>

</div>

<!-- Modal تحديث الحالة -->
@if(auth()->user()->hasRole(['admin', 'support']))
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('issues.updateStatus', $issue) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">تحديث حالة التذكرة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" dir="rtl">
                    <div class="mb-3">
                        <label class="form-label fw-bold">الحالة الجديدة</label>
                        <select name="status" id="statusSelect" class="form-select" required>
                            <option value="open" {{ $issue->status == 'open' ? 'selected' : '' }}>مفتوحة</option>
                            <option value="in_progress" {{ $issue->status == 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
                            <option value="closed" {{ $issue->status == 'closed' ? 'selected' : '' }}>مغلقة</option>
                        </select>
                    </div>

                    <div id="solutionBox" style="display: {{ $issue->status == 'closed' ? 'block' : 'none' }};">
                        <label class="form-label fw-bold">وصف الحل</label>
                        <textarea name="solution" class="form-control" rows="4" 
                                  placeholder="اشرح كيف تم حل المشكلة...">{{ $issue->solution }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        حفظ التحديث
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('statusSelect').addEventListener('change', function() {
    let solutionBox = document.getElementById('solutionBox');
    solutionBox.style.display = this.value === 'closed' ? 'block' : 'none';
});
</script>
@endif

<!-- Modal الحذف -->
@if(auth()->user()->hasRole('admin'))
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" dir="rtl">
                <p>هل أنت متأكد من حذف هذه التذكرة؟ لا يمكن التراجع عن هذا الإجراء.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form action="{{ route('issues.destroy', $issue) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>
                        حذف نهائي
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection