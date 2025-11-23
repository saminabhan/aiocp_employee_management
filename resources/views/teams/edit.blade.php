@extends('layouts.app')

@section('title', 'تعديل الفريق')

@push('styles')
<style>
    .page-title {
        font-weight: bold;
        font-size: 26px;
        color: #0c4079;
        margin-bottom: 20px;
    }

    .form-card {
        background: #ffffff;
        padding: 25px;
        border-radius: 14px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    }

    .form-label {
        font-weight: 600;
        color: #0c4079;
    }

    .btn-submit {
        width: 180px;
        height: 45px;
        font-size: 16px;
        font-weight: bold;
    }

    .btn-back {
        width: 140px;
        height: 45px;
        font-size: 15px;
        font-weight: bold;
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 12px;
        box-shadow: none !important;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0c4079;
        box-shadow: 0 0 0 0.15rem rgba(12, 64, 121, 0.2) !important;
    }

    .engineer-btn {
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 14px;
        border: 2px solid #0c4079;
        color: #0c4079;
        background: white;
        transition: 0.2s;
        cursor: pointer;
    }

    .engineer-btn.active {
        background: #0c4079;
        color: white;
    }

    .engineers-box {
        min-height: 80px;
        background: #f8f9fa;
        border-radius: 12px;
        padding: 12px;
        border: 1px solid #e0e6ed;
    }
    
     .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .category-header { font-size: .9rem; font-weight: 600; }
        .category-section { border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .category-section:last-child { border-bottom: 0; }
        .form-control, .form-select { padding: .75rem; }
        .form-check-input:checked {
            background-color: #0C4079;
            border-color: #0C4079;
        }
</style>
@endpush

@section('content')
<div class="container">

    <h3 class="page-title">
        <i class="fas fa-edit me-2"></i>
        تعديل الفريق: {{ $team->name }}
    </h3>

    @if ($errors->any())
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle me-1"></i>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="form-card">
        <form action="{{ route('teams.update', $team) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">

                <div class="col-md-6 mb-3">
                    <label class="form-label">اسم الفريق <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                           class="form-control"
                           value="{{ old('name', $team->name) }}"
                           required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">المحافظة</label>

                    @if($user->role->name === 'admin')
                        <select id="governorate" name="governorate_id" class="form-select">
                            <option value="">اختر المحافظة</option>
                            @foreach($governorates as $gov)
                                <option value="{{ $gov->id }}"
                                    {{ old('governorate_id', $team->governorate_id) == $gov->id ? 'selected' : '' }}>
                                    {{ $gov->name }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="governorate_id" value="{{ $user->governorate_id }}">
                        <input type="text" class="form-control" value="{{ $user->governorate->name }}" disabled>
                    @endif
                </div>
            </div>

            {{-- المهندسين --}}
            <div class="mb-4">
                <label class="form-label">المهندسين</label>

                <div id="engineersBox" class="engineers-box d-flex flex-wrap gap-2">
                    @foreach($engineers as $eng)
                        <button type="button"
                                class="engineer-btn {{ in_array($eng->id, old('engineer_ids', $team->engineer_ids ?? [])) ? 'active' : '' }}"
                                data-id="{{ $eng->id }}">
                            <i class="fas fa-user-cog ms-1"></i> {{ $eng->full_name }}
                        </button>
                    @endforeach
                </div>

                <div id="engineersInputs"></div>
            </div>

            {{-- حالة الفريق --}}
            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox"
                       name="is_active" value="1"
                       {{ old('is_active', $team->is_active) ? 'checked' : '' }}>
                <label class="form-check-label fw-bold ms-2">الفريق مفعل</label>
            </div>

            <hr>

            <div class="d-flex justify-content-between">
                <a href="{{ route('teams.index') }}" class="btn btn-secondary btn-back">
                    <i class="fas fa-arrow-right ms-1"></i> رجوع
                </a>

                <button type="submit" class="btn btn-primary btn-submit">
                    <i class="fas fa-save ms-1"></i> حفظ التعديلات
                </button>
            </div>

        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
// تحميل المهندسين المختارين - تحويل الكل لأرقام وإزالة التكرار
let initialEngineers = @json(old('engineer_ids', $team->engineer_ids ?? []));
let selectedEngineers = [...new Set(initialEngineers.map(id => parseInt(id)).filter(id => !isNaN(id)))];

console.log('Initial engineers:', selectedEngineers);

// تحديث hidden inputs
function updateEngineerInputs() {
    // إزالة التكرار والقيم غير الصحيحة
    selectedEngineers = [...new Set(selectedEngineers.filter(id => id && !isNaN(id)))];
    
    let container = document.getElementById('engineersInputs');
    container.innerHTML = "";
    
    console.log('Updating inputs with:', selectedEngineers);
    
    // إذا كانت القائمة فارغة، لا نضيف أي input
    // Laravel سيتعامل مع هذا كـ array فارغ
    selectedEngineers.forEach(id => {
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'engineer_ids[]';
        input.value = id;
        container.appendChild(input);
    });
}

// ربط حدث الضغط على المهندس
function bindEngineerButtons() {
    document.querySelectorAll('.engineer-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault(); // منع أي سلوك افتراضي
            
            let id = parseInt(this.dataset.id);
            console.log('Button clicked, engineer ID:', id);
            console.log('Before:', selectedEngineers);
            
            if (selectedEngineers.includes(id)) {
                // إزالة المهندس
                selectedEngineers = selectedEngineers.filter(x => x !== id);
                this.classList.remove('active');
                console.log('Removed engineer:', id);
            } else {
                // إضافة المهندس
                selectedEngineers.push(id);
                this.classList.add('active');
                console.log('Added engineer:', id);
            }
            
            console.log('After:', selectedEngineers);
            updateEngineerInputs();
        });
    });
}

// تهيئة الأزرار والمدخلات
bindEngineerButtons();
updateEngineerInputs();
</script>
@endpush
