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
            <!-- Header Card -->
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

                    <!-- Left: Basic info -->
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

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">اختر الدور</label>
                                        <select id="role" name="role_id" class="form-select">
                                            <option value="">— اختر الدور —</option>

                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}"
                                                    data-permissions="{{ $role->permissions->pluck('id')->toJson() }}"
                                                    data-requires-governorate="{{ $role->name === 'governorate_manager' ? '1' : '0' }}">
                                                    {{ $role->display_name }}
                                                </option>
                                            @endforeach

                                            <option value="custom" id="custom-role-option" style="display:none;">مخصص</option>
                                        </select>
                                    </div>

                                    <!-- حقل المحافظة - يظهر فقط عند اختيار دور مدير محافظة -->
                                    <div class="col-md-6 mb-3" id="governorate-field" style="display: none;">
                                        <label class="form-label fw-medium">
                                            اختر المحافظة
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="governorate_id" id="governorate_id" class="form-select">
                                            <option value="">— اختر المحافظة —</option>
                                            @foreach($governorates as $gov)
                                                <option value="{{ $gov->id }}">{{ $gov->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right: Permissions -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-cogs text-primary me-2"></i>
                                    صلاحيات المستخدم
                                </h5>
                            </div>

                            <div class="card-body p-2" style="max-height: 550px; overflow-y: auto;">

                                {{-- عرض الصلاحيات الخاصة بالدور أعلى --}}
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
                                                               style="cursor: pointer; transform: rotate(180deg);">
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
    const roleDiv = document.getElementById("role-permissions");
    const perms = document.querySelectorAll(".perm-check");
    const governorateField = document.getElementById("governorate-field");
    const governorateSelect = document.getElementById("governorate_id");

    // صلاحيات الأدوار
    const rolesData = {};
    Array.from(roleSelect.options).forEach(op => {
        if (op.value && op.value !== "custom") {
            rolesData[op.value] = JSON.parse(op.getAttribute("data-permissions"));
        }
    });

    const permissionNames = @json($permissions->pluck('display_name', 'id'));

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

    // عند اختيار دور
    roleSelect.addEventListener("change", () => {
        let role = roleSelect.value;
        const selectedOption = roleSelect.options[roleSelect.selectedIndex];
        const requiresGovernorate = selectedOption.getAttribute("data-requires-governorate") === "1";

        // إظهار أو إخفاء حقل المحافظة
        if (requiresGovernorate) {
            governorateField.style.display = "block";
            governorateSelect.required = true;
        } else {
            governorateField.style.display = "none";
            governorateSelect.required = false;
            governorateSelect.value = "";
        }

        if (role === "custom" || role === "") {
            perms.forEach(p => p.checked = false);
            roleDiv.innerHTML = '<span class="text-muted">صلاحيات مخصصة</span>';
            return;
        }

        let allowed = rolesData[role] ?? [];

        perms.forEach(p => p.checked = allowed.includes(parseInt(p.value)));
        showRolePermissions(allowed);
    });

    // عند تعديل الصلاحيات يدوياً → نصير "مخصص"
    perms.forEach(p => {
        p.addEventListener("change", () => {

            const selected = Array.from(perms)
                .filter(x => x.checked)
                .map(x => parseInt(x.value));

            // هل يشبه أحد الأدوار؟
            let matched = false;

            for (const [role, permsList] of Object.entries(rolesData)) {
                if (permsList.length === selected.length &&
                    permsList.every(p => selected.includes(p))) {
                    matched = true;
                    roleSelect.value = role;
                    showRolePermissions(permsList);
                    break;
                }
            }

            // لم يطابق أي دور → مخصص
            if (!matched) {
                roleSelect.value = "custom";
                showRolePermissions(selected);
                // إخفاء حقل المحافظة عند التخصيص
                governorateField.style.display = "none";
                governorateSelect.required = false;
            }
        });
    });

});
</script>
@endpush