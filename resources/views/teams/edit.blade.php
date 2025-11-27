@extends('layouts.app')

@section('title', 'تعديل الفريق')

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
        background-color: #0C4079;
        border-color: #0C4079;
    }
</style>
@endpush

@section('content')
<div class="container">

<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-edit me-2"></i>
        تعديل الفريق: {{ $team->name }}
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
        <form action="{{ route('teams.update', $team->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">

                <div class="col-md-6 mb-3">
                    <label class="form-label">اسم الفريق <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $team->name) }}"
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
                        $canManageTeams = $user->hasPermission('teams.edit');
                        $isAdminOrManager = $roleName === 'admin' || ($roleName === null && $user->hasPermission('teams.create'));

                    @endphp

                    @if ($isAdminOrManager)
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
                        <input type="hidden" name="governorate_id" value="{{ $team->governorate_id }}">
                        <input type="text" class="form-control" value="{{ $team->governorate->name ?? 'غير محدد' }}" disabled>
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">كود منطقة العمل الرئيسي <span class="text-danger">*</span></label>

                @php
                    $isSupervisor = in_array($roleName, ['survey_supervisor', 'field_supervisor']);
                    $isGovernorateManager = $roleName === 'governorate_manager';
                @endphp

                @if ($isSupervisor)
                    <input type="hidden" id="main_work_area_code" name="main_work_area_code"
                           value="{{ $team->main_work_area_code }}">
                    <input type="text" class="form-control" value="{{ $team->mainWorkArea->name ?? 'غير محدد' }}" disabled>
                
                @elseif ($isGovernorateManager)
                    <select id="main_work_area_code" name="main_work_area_code" class="form-select" required>
                        <option value="">اختر الكود الرئيسي</option>
                        @foreach($mainCodes as $code)
                            <option value="{{ $code->id }}" 
                                {{ old('main_work_area_code', $team->main_work_area_code) == $code->id ? 'selected' : '' }}>
                                {{ $code->name }}
                            </option>
                        @endforeach
                    </select>
                
                @else
                    <select id="main_work_area_code" name="main_work_area_code" class="form-select" required>
                        <option value="">اختر المحافظة أولاً</option>
                    </select>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">كود منطقة العمل الفرعي <span class="text-danger">*</span></label>
                <select id="sub_work_area_code" name="sub_work_area_code" class="form-select" required>
                    <option value="">اختر الكود الفرعي</option>
                    @foreach($subCodes as $code)
                        <option value="{{ $code->id }}" 
                            {{ old('sub_work_area_code', $team->sub_work_area_code) == $code->id ? 'selected' : '' }}>
                            {{ $code->name }}
                        </option>
                    @endforeach
                </select>
            </div>

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

            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox"
                       name="is_active" value="1" 
                       {{ old('is_active', $team->is_active) ? 'checked' : '' }}
                       style="transform: scale(1.2);">
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
let selectedEngineers = @json(old('engineer_ids', $team->engineer_ids ?? []));
const usedSubCodes = @json($usedSubCodes ?? []);
const currentTeamId = @json($team->id);

@php
    $roleName = Auth::user()->role->name ?? null;
    $isSupervisor = in_array($roleName, ['survey_supervisor', 'field_supervisor']);
    $isGovernorateManager = $roleName === 'governorate_manager';
    $canManageTeams = Auth::user()->hasPermission('teams.edit');
    $isAdminOrManager = in_array($roleName, ['admin', 'system_admin']) || $canManageTeams;
@endphp

const isSupervisor = @json($isSupervisor);
const isGovernorateManager = @json($isGovernorateManager);
const isAdminOrManager = @json($isAdminOrManager);

const TEAM_MAIN_ID = {!! json_encode($team->main_work_area_code ?? null) !!};
const TEAM_SUB_ID  = {!! json_encode($team->sub_work_area_code ?? null) !!};

function updateEngineerInputs() {
    let container = document.getElementById('engineersInputs');
    if (!container) return;
    container.innerHTML = "";
    selectedEngineers.forEach(id => {
        container.insertAdjacentHTML('beforeend', `<input type="hidden" name="engineer_ids[]" value="${id}">`);
    });
}

function bindEngineerButtons() {
    document.querySelectorAll('.engineer-btn').forEach(btn => {
        btn.removeEventListener && btn.removeEventListener('click', engineerBtnHandler);
        btn.addEventListener('click', engineerBtnHandler);
    });
}

function engineerBtnHandler(e) {
    const btn = e.currentTarget;
    let id = parseInt(btn.dataset.id);
    if (!id) return;

    if (selectedEngineers.includes(id)) {
        selectedEngineers = selectedEngineers.filter(x => x !== id);
        btn.classList.remove('active');
    } else {
        selectedEngineers.push(id);
        btn.classList.add('active');
    }

    updateEngineerInputs();
}

function loadSubCodes(mainId) {
    const subSelect = document.getElementById('sub_work_area_code');
    if(!subSelect) return;

    subSelect.innerHTML = `<option value="">جاري التحميل...</option>`;

    if (!mainId) {
        subSelect.innerHTML = `<option value="">اختر الكود الفرعي</option>`;
        return;
    }

    fetch(`/teams/get-sub-codes/${mainId}`)
        .then(res => res.json())
        .then(data => {
            subSelect.innerHTML = `<option value="">اختر الكود الفرعي</option>`;
            data.forEach(c => {
                if (!usedSubCodes.includes(c.id) || c.id == TEAM_SUB_ID) {
                    let selected = (c.id == TEAM_SUB_ID) ? 'selected' : '';
                    subSelect.insertAdjacentHTML('beforeend', `<option value="${c.id}" ${selected}>${c.name}</option>`);
                }
            });
        })
        .catch(err => {
            console.error('Error loading sub codes:', err);
            subSelect.innerHTML = `<option value="">حدث خطأ في التحميل</option>`;
        });
}

function loadEngineers(mainCodeId) {
    const box = document.getElementById('engineersBox');
    if (!box) return;

    const previouslySelected = [...selectedEngineers];

    box.innerHTML = `<div class="w-100 text-center py-2">
                        <span class="spinner-border text-primary"></span>
                        <p class="mt-2">جاري تحميل المهندسين...</p>
                     </div>`;

    fetch(`/teams/get-engineers-by-main-code/${mainCodeId}?team_id=${currentTeamId}`)
        .then(res => res.json())
        .then(data => {
            box.innerHTML = "";

            if (!Array.isArray(data) || data.length === 0) {
                box.innerHTML = `<div class="w-100 text-center text-muted py-3">
                                    <i class="fas fa-user-slash fa-2x mb-2"></i>
                                    <p>لا يوجد مهندسين متاحين لهذا الكود</p>
                                 </div>`;
                return;
            }

            data.forEach(e => {
                let isActive = previouslySelected.includes(e.id) ? 'active' : '';
                box.insertAdjacentHTML('beforeend', `
                    <button type="button" class="engineer-btn ${isActive}" data-id="${e.id}">
                        <i class="fas fa-user ms-1"></i> ${e.first_name} ${e.second_name} ${e.third_name} ${e.last_name}
                    </button>
                `);
            });

            bindEngineerButtons();
            updateEngineerInputs();
        })
        .catch(err => {
            console.error('Error loading engineers:', err);
            box.innerHTML = `<div class="alert alert-danger">حدث خطأ في تحميل المهندسين</div>`;
        });
}

document.getElementById('governorate')?.addEventListener('change', function () {
    const govId = this.value;
    const mainSelect = document.getElementById('main_work_area_code');
    const subSelect  = document.getElementById('sub_work_area_code');

    if (!mainSelect) return;

    mainSelect.innerHTML = `<option value="">جاري التحميل...</option>`;
    if (subSelect) subSelect.innerHTML = `<option value="">اختر الكود الفرعي</option>`;

    if (!govId) {
        mainSelect.innerHTML = `<option value="">اختر المحافظة أولاً</option>`;
        return;
    }

    fetch(`/teams/get-main-codes-by-gov/${govId}`)
        .then(res => res.json())
        .then(data => {
            mainSelect.innerHTML = `<option value="">اختر الكود الرئيسي</option>`;
            data.forEach(c => {
                mainSelect.insertAdjacentHTML('beforeend', `<option value="${c.id}">${c.name}</option>`);
            });

            if (TEAM_MAIN_ID) {
                mainSelect.value = TEAM_MAIN_ID;
                loadSubCodes(TEAM_MAIN_ID);
            } else {
                mainSelect.value = "";
            }
        })
        .catch(err => {
            console.error('Error loading main codes:', err);
            mainSelect.innerHTML = `<option value="">حدث خطأ أثناء التحميل</option>`;
        });
});

function onMainCodeChangedByUser() {
    const mainSelect = document.getElementById('main_work_area_code');
    if (!mainSelect) return;

    mainSelect.addEventListener('change', function () {
        const mainId = this.value;

        loadSubCodes(mainId);

        if (isAdminOrManager || isGovernorateManager) {
            if (mainId) {
                loadEngineers(mainId);
            } else {
                document.getElementById('engineersBox').innerHTML = `
                    <div class="w-100 text-center text-muted py-3">
                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                        <p>اختر كود منطقة العمل الرئيسي أولاً لعرض المهندسين</p>
                    </div>`;
                selectedEngineers = [];
                updateEngineerInputs();
            }
        }
    });
}

window.addEventListener('DOMContentLoaded', function () {
    onMainCodeChangedByUser();
    bindEngineerButtons();
    updateEngineerInputs();

    if (isGovernorateManager && TEAM_MAIN_ID) {
        const mainSelect = document.getElementById('main_work_area_code');
        if (mainSelect && mainSelect.value) {
            loadSubCodes(TEAM_MAIN_ID);
        }
    }

    const gov = document.getElementById('governorate')?.value;
    if (gov && !isGovernorateManager) {
        document.getElementById('governorate').dispatchEvent(new Event('change'));
    } else if (!gov && TEAM_MAIN_ID && !isGovernorateManager) {
        const mainSelect = document.getElementById('main_work_area_code');
        if (mainSelect) {
            mainSelect.value = TEAM_MAIN_ID;
            loadSubCodes(TEAM_MAIN_ID);
        }
    }
});
</script>
@endpush