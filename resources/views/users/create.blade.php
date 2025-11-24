@extends('layouts.app')

@section('title', 'إضافة مستخدم')

@push('styles')
<style>
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
    .card:hover { transform: translateY(-2px); transition: .2s; }
</style>
@endpush

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">

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

            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 text-dark fw-semibold">
                            <i class="fas fa-user-plus text-primary me-2"></i>
                            إضافة مستخدم جديد
                        </h4>
                        <p class="text-muted mb-0 mt-1">قم بتعبئة البيانات التالية</p>
                    </div>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                    </a>
                </div>
            </div>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="row">

                    <!-- Left -->
                    <div class="col-lg-8">

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-user text-primary me-2"></i>المعلومات الأساسية
                                </h5>
                            </div>

                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">الاسم</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">اسم المستخدم</label>
                                        <input type="text" name="username" class="form-control" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">رقم الهاتف</label>
                                        <input type="text" name="phone" class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">كلمة المرور</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>

                                    <!-- اختيار الدور -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">اختر الدور</label>
                                        <select id="role" name="role_id" class="form-select">
                                            <option value="">— اختر الدور —</option>

                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}"
                                                    data-name="{{ $role->name }}"
                                                    data-permissions="{{ $role->permissions->pluck('id')->toJson() }}">
                                                    {{ $role->display_name }}
                                                </option>
                                            @endforeach

                                            <option value="custom" style="display:none;">مخصص</option>
                                        </select>
                                    </div>

                                    <!-- المحافظة -->
                                    <div class="col-md-6 mb-3" id="field-governorate" style="display:none;">
                                        <label class="form-label fw-medium">اختر المحافظة</label>
                                        <select name="governorate_id" id="governorate_id" class="form-select">
                                            <option value="">— اختر المحافظة —</option>
                                            @foreach($governorates as $gov)
                                                <option value="{{ $gov->id }}">{{ $gov->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- المدينة -->
                                    <div class="col-md-6 mb-3" id="field-city" style="display:none;">
                                        <label class="form-label fw-medium">اختر المدينة</label>
                                        <select name="city_id" id="city_id" class="form-select" disabled>
                                            <option value="">— اختر المدينة —</option>
                                        </select>
                                    </div>

                                    <!-- كود منطقة العمل -->
                                    <div class="col-md-6 mb-3" id="field-main-area" style="display:none;">
                                        <label class="form-label fw-medium">اختر كود منطقة العمل الرئيسي</label>
                                        <select name="main_work_area_code" id="main_work_area_code" class="form-select">
                                            <option value="">— اختر كود منطقة العمل —</option>
                                            @foreach($mainWorkAreas as $area)
                                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right Permissions -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-cogs text-primary me-2"></i>
                                    صلاحيات المستخدم
                                </h5>
                            </div>

                            <div class="card-body p-2" style="max-height: 550px; overflow-y: auto;">

                                <div id="role-permissions" class="mb-3 px-2 py-2 bg-light rounded">
                                    <span class="text-muted">اختر دور لعرض صلاحياته</span>
                                </div>

                                <hr>

                                @php
                                    $categoryNames = [
                                        'Dashboard' => 'لوحة التحكم',
                                        'Users' => 'إدارة المستخدمين',
                                        'engineers' => 'إدارة المهندسين',
                                        'constants' => 'إدارة الثوابت',
                                        'teams' => 'إدارة الفرق',
                                    ];
                                    $permissionsByCategory = $permissions->groupBy('category');
                                @endphp

                                @foreach($permissionsByCategory as $category => $group)
                                    <div class="category-section mb-3">
                                        <h6 class="category-header bg-light p-2 rounded mb-2">
                                            <i class="fas fa-folder text-primary me-1"></i>
                                            {{ $categoryNames[$category] ?? $category }}
                                        </h6>

                                        <div class="row row-cols-1 g-2">
                                            @foreach($group as $permission)
                                                <div class="col">
                                                    <div class="form-check form-switch">
                                                        <input type="checkbox"
                                                            class="form-check-input perm-check"
                                                            name="permissions[]"
                                                            value="{{ $permission->id }}"
                                                            style="cursor:pointer; transform: rotate(180deg);">
                                                        <label class="form-check-label">
                                                            {{ $permission->display_name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <button class="btn btn-primary px-5 me-3">
                            <i class="fas fa-save me-2"></i>حفظ المستخدم
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-5">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </a>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {

    const roleSelect = document.getElementById("role");

    const fieldGov = document.getElementById("field-governorate");
    const fieldCity = document.getElementById("field-city");
    const fieldArea = document.getElementById("field-main-area");

    const governorateSelect = document.getElementById("governorate_id");
    const citySelect = document.getElementById("city_id");

    const perms = document.querySelectorAll(".perm-check");
    const roleDiv = document.getElementById("role-permissions");

    const permissionNames = @json($permissions->pluck('display_name', 'id'));

    /*--------------------------
     | تصنيف الصلاحيات حسب الدور
     --------------------------*/
    const rolesData = {};
    Array.from(roleSelect.options).forEach(op => {
        if (op.value && op.value !== "custom") {
            rolesData[op.value] = JSON.parse(op.dataset.permissions);
        }
    });

    function showRolePermissions(ids) {
        if (!ids || ids.length === 0) {
            roleDiv.innerHTML = '<span class="text-muted">لا توجد صلاحيات</span>';
            return;
        }
        let html = "";
        ids.forEach(id => {
            html += `<span class="badge bg-success me-1 mb-1">${permissionNames[id]}</span>`;
        });
        roleDiv.innerHTML = html;
    }

    /*--------------------------
     | 1) عند اختيار الدور
     --------------------------*/
    roleSelect.addEventListener("change", () => {

        const selected = roleSelect.options[roleSelect.selectedIndex];
        const roleName = selected.dataset.name;

        // إظهار حقول حسب الدور
        if (roleName === "survey_supervisor") {
            fieldGov.style.display = "block";
            fieldCity.style.display = "block";
            fieldArea.style.display = "block";

        } else if (roleName === "governorate_manager") {
            fieldGov.style.display = "block";
            fieldCity.style.display = "none";
            fieldArea.style.display = "none";

        } else {
            fieldGov.style.display = "none";
            fieldCity.style.display = "none";
            fieldArea.style.display = "none";
        }

        let permissions = rolesData[selected.value] ?? [];

        // علّم الصلاحيات
        perms.forEach(p => p.checked = permissions.includes(parseInt(p.value)));

        // اعرض الصلاحيات في الأعلى
        showRolePermissions(permissions);
    });

    /*--------------------------
     | 2) عند اختيار المحافظة → تحميل المدن
     --------------------------*/
    governorateSelect.addEventListener("change", () => {

    let govId = governorateSelect.value;

    citySelect.innerHTML = '<option value="">— اختر المدينة —</option>';
    citySelect.disabled = true;

    if (govId === "") return;

    fetch(`/get-cities/${govId}`)
        .then(res => res.json())
        .then(data => {
            data.forEach(city => {
                citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
            });
            citySelect.disabled = false;
        });
});


});
</script>
@endpush
