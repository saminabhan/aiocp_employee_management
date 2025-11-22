@extends('layouts.app')

@section('title', 'تعديل مستخدم')

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
                            <i class="fas fa-user-edit text-primary me-2"></i>
                            تعديل المستخدم
                        </h4>
                        <p class="text-muted mb-0 mt-1">قم بتعديل البيانات المطلوبة</p>
                    </div>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                    </a>
                </div>
            </div>

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

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
                                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">اسم المستخدم</label>
                                        <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">رقم الهاتف</label>
                                        <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">كلمة المرور الجديدة</label>
                                        <input type="password" name="password" class="form-control" placeholder="اتركه فارغاً إذا لا تريد تغييره">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-medium">اختر الدور</label>
                                        <select id="role" name="role_id" class="form-select">
                                            <option value="">— اختر الدور —</option>

                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}"
                                                    data-permissions="{{ $role->permissions->pluck('id')->toJson() }}"
                                                    data-requires-governorate="{{ $role->name === 'governorate_manager' ? '1' : '0' }}"
                                                    {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                    {{ $role->display_name }}
                                                </option>
                                            @endforeach

                                            <option value="custom" {{ $user->role_id === null ? 'selected' : '' }}>مخصص</option>
                                        </select>
                                    </div>

                                    <!-- حقل المحافظة -->
                                    @php
                                        $showGovernorate = $user->role && $user->role->name === 'governorate_manager';
                                    @endphp
                                    <div class="col-md-6 mb-3" id="governorate-field" style="display: {{ $showGovernorate ? 'block' : 'none' }};">
                                        <label class="form-label fw-medium">
                                            اختر المحافظة
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="governorate_id" id="governorate_id" class="form-select" {{ $showGovernorate ? 'required' : '' }}>
                                            <option value="">— اختر المحافظة —</option>
                                            @foreach($governorates as $gov)
                                                <option value="{{ $gov->id }}" {{ $user->governorate_id == $gov->id ? 'selected' : '' }}>
                                                    {{ $gov->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right -->
                    <div class="col-lg-4">

                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-cogs text-primary me-2"></i>
                                    صلاحيات المستخدم
                                </h5>
                            </div>

                            <div class="card-body p-2" style="max-height: 550px; overflow-y: auto;">

                                <!-- Role permissions summary -->
                                <div id="role-permissions" class="mb-3 px-2 py-2 bg-light rounded"></div>

                                <hr>

                                @php
                                    $categoryNames = [
                                        'Dashboard' => 'لوحة التحكم',
                                        'Users' => 'إدارة المستخدمين',
                                        'engineers' => 'إدارة المهندسين',
                                        'constants' => 'إدارة الثوابت',
                                    ];

                                    $permissionsByCategory = $permissions->groupBy('category');
                                    $userPerms = $user->permissions->pluck('id')->toArray();
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
                                                               @if(in_array($permission->id, $userPerms)) checked @endif
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
                            <i class="fas fa-save me-2"></i>تحديث المستخدم
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

    // Roles permissions data
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
        ids.forEach(id => html += `<span class="badge bg-success me-1 mb-1">${permissionNames[id]}</span>`);
        roleDiv.innerHTML = html;
    }

    // When selecting a role
    roleSelect.addEventListener("change", () => {
        let role = roleSelect.value;
        const selectedOption = roleSelect.options[roleSelect.selectedIndex];
        const requiresGovernorate = selectedOption.getAttribute("data-requires-governorate") === "1";

        // Show or hide governorate field
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

    // Manual change of permissions → custom role
    perms.forEach(p => {
        p.addEventListener("change", () => {

            const selected = Array.from(perms)
                .filter(x => x.checked)
                .map(x => parseInt(x.value));

            let matched = false;

            for (const [role, list] of Object.entries(rolesData)) {
                if (list.length === selected.length &&
                    list.every(p => selected.includes(p))) {
                    matched = true;
                    roleSelect.value = role;
                    showRolePermissions(list);
                    break;
                }
            }

            if (!matched) {
                roleSelect.value = "custom";
                showRolePermissions(selected);
                // Hide governorate on custom
                governorateField.style.display = "none";
                governorateSelect.required = false;
            }
        });
    });

    // Show role permissions on load
    (function initPermissionsOnLoad() {
        let currentRole = roleSelect.value;

        if (currentRole && currentRole !== "custom") {
            showRolePermissions(rolesData[currentRole] ?? []);
        } else {
            const selected = Array.from(perms)
                .filter(x => x.checked)
                .map(x => parseInt(x.value));

            showRolePermissions(selected);
        }
    })();

});
</script>
@endpush