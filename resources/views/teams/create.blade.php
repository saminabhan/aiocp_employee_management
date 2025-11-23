@extends('layouts.app')

@section('title', 'إضافة فريق جديد')

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
        <i class="fas fa-users me-2"></i>
        إضافة فريق جديد
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
        <form action="{{ route('teams.store') }}" method="POST">
            @csrf

            <div class="row mb-3">

                {{-- اسم الفريق --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">اسم الفريق <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="أدخل اسم الفريق" required>
                    @error('name')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                {{-- المحافظة --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">المحافظة</label>

                    @if($user->role->name === 'admin')
                        <select id="governorate" name="governorate_id"
                                class="form-select">
                            <option value="">اختر المحافظة</option>
                            @foreach($governorates as $gov)
                                <option value="{{ $gov->id }}" {{ old('governorate_id') == $gov->id ? 'selected' : '' }}>
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

                    @if($user->role->name === 'governorate_manager')
                        @foreach($engineers as $eng)
                            <button type="button"
                                    class="engineer-btn {{ in_array($eng->id, old('engineer_ids', [])) ? 'active' : '' }}"
                                    data-id="{{ $eng->id }}">
                                <i class="fas fa-user-cog ms-1"></i> {{ $eng->full_name }}
                            </button>
                        @endforeach
                    @endif

                </div>

                {{-- هنا التعديل المهم ليرسل Array --}}
                <div id="engineersInputs"></div>

            </div>

            {{-- تفعيل الفريق --}}
            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox"
                       name="is_active" value="1" 
                       {{ old('is_active', true) ? 'checked' : '' }}
                       style="transform: scale(1.2);">
                <label class="form-check-label fw-bold ms-2">الفريق مفعل</label>
            </div>

            <hr>

            <div class="d-flex justify-content-between">
                <a href="{{ route('teams.index') }}" class="btn btn-secondary btn-back">
                    <i class="fas fa-arrow-right ms-1"></i> رجوع
                </a>

                <button type="submit" class="btn btn-primary btn-submit">
                    <i class="fas fa-check ms-1"></i> حفظ الفريق
                </button>
            </div>

        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>

let selectedEngineers = @json(old('engineer_ids', []));

// =============== تحميل المهندسين (للأدمين) ===============
@if($user->role->name === 'admin')
document.getElementById('governorate').addEventListener('change', function () {

    let govId = this.value;
    let box = document.getElementById('engineersBox');

    box.innerHTML = `<div class="w-100 text-center py-2">
                        <span class="spinner-border text-primary"></span>
                    </div>`;

    if (govId === "") {
        box.innerHTML = "";
        selectedEngineers = [];
        updateEngineerInputs();
        return;
    }

    fetch(`/teams/get-engineers/${govId}`)
        .then(res => res.json())
        .then(data => {
            box.innerHTML = "";

            if (data.length === 0) {
                box.innerHTML = `<div class="w-100 text-center text-muted py-3">
                    <i class="fas fa-user-slash fa-2x mb-2"></i>
                    <p>لا يوجد مهندسين متاحين في هذه المحافظة</p>
                </div>`;
                return;
            }

            data.forEach(e => {
                box.innerHTML += `
                    <button type="button"
                        class="engineer-btn"
                        data-id="${e.id}">
                        <i class="fas fa-user ms-1"></i> ${e.full_name}
                    </button>
                `;
            });

            bindEngineerButtons();
        })
        .catch(error => {
            box.innerHTML = `<div class="alert alert-danger">حدث خطأ في تحميل المهندسين</div>`;
            console.error('Error:', error);
        });
});
@endif


// =============== اختيار مهندس ===============
function bindEngineerButtons() {

    document.querySelectorAll('.engineer-btn').forEach(btn => {

        btn.addEventListener('click', function () {

            let id = parseInt(this.dataset.id);

            if (selectedEngineers.includes(id)) {
                selectedEngineers = selectedEngineers.filter(x => x !== id);
                this.classList.remove('active');

            } else {
                selectedEngineers.push(id);
                this.classList.add('active');
            }

            updateEngineerInputs();
        });
    });
}


// =============== توليد input array للارسال ===============
function updateEngineerInputs() {

    let container = document.getElementById('engineersInputs');
    container.innerHTML = "";

    selectedEngineers.forEach(id => {
        container.innerHTML += `<input type="hidden" name="engineer_ids[]" value="${id}">`;
    });
}

// تفعيل الأزرار عند التحميل (لـ governorate_manager)
bindEngineerButtons();
updateEngineerInputs();
</script>
@endpush