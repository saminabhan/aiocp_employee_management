@extends('layouts.app')

@section('title', 'إضافة فريق جديد')

@push('styles')
<style>
    .page-header {
        background: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #0C4079;
        margin: 0;
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
    .form-check-input:checked {
        background-color: #0C4079;
        border-color: #0C4079;
    }
</style>
@endpush

@section('content')
<div class="container">
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-users-cog"></i>
        إضافة فريق جديد
    </h1>
</div>
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

                <div class="col-md-6 mb-3">
                    <label class="form-label">المحافظة</label>

                @php
                    $user = Auth::user();
                    $roleName = $user->role->name ?? null;
                    $isSupervisor = $roleName === 'survey_supervisor';

                    $canChangeGovernorate =
                        $roleName === 'admin' || ($roleName === null && $user->hasPermission('teams.create'));


                @endphp


                @if ($canChangeGovernorate)
                    <select id="governorate" name="governorate_id" class="form-select">
                        <option value="">اختر المحافظة</option>
                        @foreach($governorates as $gov)
                            <option value="{{ $gov->id }}" {{ old('governorate_id', $user->governorate_id) == $gov->id ? 'selected' : '' }}>
                                {{ $gov->name }}
                            </option>
                        @endforeach
                    </select>
               
                @else
                    <input type="hidden" name="governorate_id" value="{{ $user->governorate_id }}">
                    <input type="text" class="form-control" value="{{ $user->governorate->name ?? 'غير محدد' }}" disabled>
                @endif
                </div>
            </div>

            <div class="mb-3" id="mainCodeContainer" 
                @if($roleName === 'governorate_manager') style="display: block;" @else style="display: none;" @endif>
                <label class="form-label">كود منطقة العمل الرئيسي <span class="text-danger">*</span></label>

                @php
                    $isSupervisor = $roleName === 'survey_supervisor' || $roleName === 'field_supervisor';
                    $isGovernorateManager = $roleName === 'governorate_manager';
                @endphp

                @if ($isSupervisor)
                    <input type="hidden" id="main_work_area_code" name="main_work_area_code"
                        value="{{ $user->main_work_area_code }}">
                    <input type="text" class="form-control" value="{{ $user->mainWorkArea->name ?? 'غير محدد' }}" disabled>
                
                @elseif ($isGovernorateManager)
                    <select id="main_work_area_code" name="main_work_area_code" class="form-select" required>
                        <option value="">اختر الكود الرئيسي</option>
                        @foreach($mainCodes as $code)
                            <option value="{{ $code->id }}" {{ old('main_work_area_code') == $code->id ? 'selected' : '' }}>
                                {{ $code->name }}
                            </option>
                        @endforeach
                    </select>
                
                @else
                    <select id="main_work_area_code" name="main_work_area_code" class="form-select" required>
                        <option value="">اختر الكود الرئيسي</option>
                    </select>
                @endif
            </div>
            <div class="mb-3">
                <label class="form-label">كود منطقة العمل الفرعي <span class="text-danger">*</span></label>
                <select id="sub_work_area_code" name="sub_work_area_code" class="form-select" required>
                    <option value="">اختر الكود الفرعي</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">المهندسين</label>

                <div id="engineersBox" class="engineers-box d-flex flex-wrap gap-2">
                    @if ($isSupervisor)
                        @foreach($engineers as $eng)
                            <button type="button"
                                    class="engineer-btn {{ in_array($eng->id, old('engineer_ids', [])) ? 'active' : '' }}"
                                    data-id="{{ $eng->id }}">
                                <i class="fas fa-user-cog ms-1"></i> {{ $eng->full_name }}
                            </button>
                        @endforeach
                    @else
                        <div class="w-100 text-center text-muted py-3">
                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                            <p>اختر كود منطقة العمل الرئيسي أولاً لعرض المهندسين</p>
                        </div>
                    @endif
                </div>

                <div id="engineersInputs"></div>
            </div>

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

const usedSubCodes = @json($usedSubCodes ?? []);

const isSupervisor = @json($isSupervisor ?? false);
const isAdminOrManager = @json($isAdminOrManager ?? false);

@php
    $roleName = Auth::user()->role->name ?? null;
    $isGovernorateManager = $roleName === 'governorate_manager';
@endphp

document.getElementById('main_work_area_code')?.addEventListener('change', function () {
    let mainId = this.value;
    let subSelect = document.getElementById('sub_work_area_code');

    subSelect.innerHTML = `<option value="">جاري التحميل...</option>`;

    if (mainId === "") {
        subSelect.innerHTML = `<option value="">اختر الكود الفرعي</option>`;
        if (isAdminOrManager || @json($isGovernorateManager)) {
            document.getElementById('engineersBox').innerHTML = `
                <div class="w-100 text-center text-muted py-3">
                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                    <p>اختر كود منطقة العمل الرئيسي أولاً لعرض المهندسين</p>
                </div>`;
            selectedEngineers = [];
            updateEngineerInputs();
        }
        return;
    }

    fetch(`/teams/get-sub-codes/${mainId}`)
        .then(res => res.json())
        .then(data => {
            subSelect.innerHTML = `<option value="">اختر الكود الفرعي</option>`;
            data.forEach(c => {
                if (!usedSubCodes.includes(c.id)) {
                    subSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                }
            });
        })
        .catch(error => {
            console.error('Error loading sub codes:', error);
            subSelect.innerHTML = `<option value="">حدث خطأ في التحميل</option>`;
        });

    if (isAdminOrManager || @json($isGovernorateManager)) {
        loadEngineers(mainId);
    }
});

function loadEngineers(mainCodeId) {
    let box = document.getElementById('engineersBox');

    selectedEngineers = [];
    updateEngineerInputs();

    box.innerHTML = `<div class="w-100 text-center py-2">
                        <span class="spinner-border text-primary"></span>
                        <p class="mt-2">جاري تحميل المهندسين...</p>
                    </div>`;

    fetch(`/teams/get-engineers-by-main-code/${mainCodeId}`)
        .then(res => res.json())
        .then(data => {
            box.innerHTML = "";

            if (data.length === 0) {
                box.innerHTML = `<div class="w-100 text-center text-muted py-3">
                    <i class="fas fa-user-slash fa-2x mb-2"></i>
                    <p>لا يوجد مهندسين متاحين لهذا الكود</p>
                </div>`;
                return;
            }

            data.forEach(e => {
                box.innerHTML += `
                    <button type="button"
                        class="engineer-btn"
                        data-id="${e.id}">
                        <i class="fas fa-user ms-1"></i> ${e.first_name} ${e.second_name} ${e.third_name} ${e.last_name}
                    </button>
                `;
            });

            bindEngineerButtons();
        })
        .catch(error => {
            box.innerHTML = `<div class="alert alert-danger">حدث خطأ في تحميل المهندسين</div>`;
            console.error('Error:', error);
        });
}

if (isSupervisor) {
    window.addEventListener('DOMContentLoaded', function() {
        let mainId = document.getElementById('main_work_area_code').value;
        let subSelect = document.getElementById('sub_work_area_code');
        
        if (mainId) {
            subSelect.innerHTML = `<option value="">جاري التحميل...</option>`;
            
            fetch(`/teams/get-sub-codes/${mainId}`)
                .then(res => res.json())
                .then(data => {
                    subSelect.innerHTML = `<option value="">اختر الكود الفرعي</option>`;
                    data.forEach(c => {
                        if (!usedSubCodes.includes(c.id)) {
                            subSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                        }
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    subSelect.innerHTML = `<option value="">حدث خطأ في التحميل</option>`;
                });
        }
    });
}

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

function updateEngineerInputs() {
    let container = document.getElementById('engineersInputs');
    container.innerHTML = "";

    selectedEngineers.forEach(id => {
        container.innerHTML += `<input type="hidden" name="engineer_ids[]" value="${id}">`;
    });
}

if (isSupervisor) {
    bindEngineerButtons();
    updateEngineerInputs();
}

document.getElementById('governorate')?.addEventListener('change', function () {
    let govId = this.value;
    let mainSelect = document.getElementById('main_work_area_code');
    let container = document.getElementById('mainCodeContainer');

    mainSelect.innerHTML = `<option value="">جاري التحميل...</option>`;

    if (govId === "") {
        container.style.display = "none";
        mainSelect.innerHTML = `<option value="">اختر الكود الرئيسي</option>`;
        return;
    }

    container.style.display = "block";

    fetch(`/teams/get-main-codes-by-gov/${govId}`)
        .then(res => res.json())
        .then(data => {
            mainSelect.innerHTML = `<option value="">اختر الكود الرئيسي</option>`;
            data.forEach(c => {
                mainSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
            });
        })
        .catch(error => {
            console.error('Error loading main codes:', error);
            mainSelect.innerHTML = `<option value="">حدث خطأ في التحميل</option>`;
        });
});

window.addEventListener('DOMContentLoaded', function () {
    @if ($isGovernorateManager)
        let container = document.getElementById('mainCodeContainer');
        container.style.display = "block";

        let mainSelect = document.getElementById('main_work_area_code');
        
        let oldMainValue = "{{ old('main_work_area_code') }}";
        if (oldMainValue) {
            mainSelect.value = oldMainValue;
            mainSelect.dispatchEvent(new Event('change'));
        }
    @else
        let govSelect = document.getElementById('governorate');
        let govId = govSelect?.value;

        if (!govId) return;

        let container = document.getElementById('mainCodeContainer');
        let mainSelect = document.getElementById('main_work_area_code');

        container.style.display = "block";
        mainSelect.innerHTML = `<option value="">جاري التحميل...</option>`;

        fetch(`/teams/get-main-codes-by-gov/${govId}`)
            .then(res => res.json())
            .then(data => {
                mainSelect.innerHTML = `<option value="">اختر الكود الرئيسي</option>`;
                data.forEach(c => {
                    mainSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                });

                let oldValue = "{{ old('main_work_area_code') }}";
                if (oldValue) {
                    mainSelect.value = oldValue;
                    mainSelect.dispatchEvent(new Event('change'));
                }
            })
            .catch(error => {
                console.error("Error:", error);
                mainSelect.innerHTML = `<option value="">حدث خطأ في التحميل</option>`;
            });
    @endif
});

</script>
@endpush