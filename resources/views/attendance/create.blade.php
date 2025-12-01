@extends('layouts.app')

@section('title', 'تسجيل دوام جديد')

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

    /* قسم اختيار النوع */
    .user-type-boxes {
        display: grid;
        grid-template-columns: repeat(2,1fr);
        gap: 15px;
    }

    .user-type-card {
        border: 2px solid #e0e0e0;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        cursor: pointer;
        transition: .3s;
    }

    .user-type-card:hover {
        border-color: #0C4079;
        background: #f7faff;
    }

    .user-type-card.active {
        border-color: #0C4079;
        background: #e3f2fd;
    }

    .user-type-card i {
        font-size: 34px;
        color: #0C4079;
        margin-bottom: 10px;
    }

    /* الحالة */
    .status-cards {
        display: grid;
        grid-template-columns: repeat(3,1fr);
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

    /* جدول الموظفين */
    .employees-table-wrapper {
        max-height: 500px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-top: 15px;
    }

    .employees-table {
        width: 100%;
        margin: 0;
    }

    .employees-table thead {
        position: sticky;
        top: 0;
        background: #0C4079;
        color: white;
        z-index: 10;
    }

    .employees-table th {
        padding: 12px;
        font-weight: 600;
        text-align: center;
        border-bottom: 2px solid #dee2e6;
    }

    .employees-table td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #dee2e6;
    }

    .employees-table tbody tr:hover {
        background: #f8f9fa;
    }

    .checkbox-cell {
        width: 50px;
    }

    .checkbox-cell input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .select-all-row {
        background: #f8f9fa;
        font-weight: 600;
    }

    .selected-count {
        background: #e3f2fd;
        padding: 10px 15px;
        border-radius: 8px;
        margin-top: 10px;
        color: #0C4079;
        font-weight: 600;
        display: none;
    }

    .no-employees {
        text-align: center;
        padding: 30px;
        color: #6c757d;
    }

    .no-employees i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    /* الأزرار */
    .btn-submit {
        background: #0C4079;
        border-color: #0C4079;
        padding: 12px 30px;
        font-weight: 600;
        width: 160px;
    }

    .btn-back {
        background: #f0f0f0;
        color: #333;
        padding: 12px 30px;
        width: 160px;
        font-weight: bold;
    }

    .date-warning {
        background: #fff3cd;
        border: 1px solid #ffc107;
        padding: 10px;
        border-radius: 8px;
        display: none;
        margin-top: 10px;
    }

    .filter-controls {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
    }
    
    input[type="date"] {
        direction: rtl;
        text-align: right;
    }

</style>
@endpush


@section('content')
<div class="container" dir="rtl">

    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-calendar-check"></i>
            تسجيل دوام جديد
        </h1>
    </div>


    <div class="form-card">

        <form action="{{ route('attendance.store') }}" method="POST" id="attendanceForm">
            @csrf

            <div class="row mb-4">
                <div class="mb-4">
                    <label class="form-label">نوع المستخدم <span class="required-star">*</span></label>
                    <div class="user-type-boxes">
                        <label class="user-type-card {{ $userType == 'engineer' ? 'active' : '' }}" data-type="engineer">
                            <input type="radio" name="user_type" value="engineer" hidden {{ $userType == 'engineer' ? 'checked' : '' }}>
                            <i class="fas fa-hard-hat"></i>
                            <div>مهندسين حصر</div>
                        </label>

                        <label class="user-type-card {{ $userType == 'supervisor' ? 'active' : '' }}" data-type="supervisor">
                            <input type="radio" name="user_type" value="supervisor" hidden {{ $userType == 'supervisor' ? 'checked' : '' }}>
                            <i class="fas fa-user-tie"></i>
                            <div>مشرفين حصر</div>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="form-label">تاريخ الدوام <span class="required-star">*</span></label>
                    <input type="date" name="attendance_date" id="attendanceDate"
                        class="form-control"
                        value="{{ $selectedDate }}"
                        min="{{ $minDate }}" max="{{ $maxDate }}">
                    
                    <div class="date-warning" id="fridayWarning">
                        <i class="fas fa-exclamation-triangle"></i>
                        التاريخ المختار يوم جمعة (إجازة أسبوعية)
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">حالة الحضور <span class="required-star">*</span></label>
                <div class="status-cards">
                    <label class="status-card present active">
                        <input type="radio" name="status" value="present" hidden checked>
                        <i class="fas fa-check-circle text-success"></i>
                        <div>حاضر</div>
                    </label>

                    <label class="status-card absent">
                        <input type="radio" name="status" value="absent" hidden>
                        <i class="fas fa-times-circle text-danger"></i>
                        <div>غائب</div>
                    </label>

                    <label class="status-card leave">
                        <input type="radio" name="status" value="leave" hidden>
                        <i class="fas fa-briefcase text-warning"></i>
                        <div>إجازة</div>
                    </label>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">اختر الموظفين <span class="required-star">*</span></label>
                
                <div class="selected-count" id="selectedCount">
                    تم اختيار <span id="countNumber">0</span> موظف
                </div>

                <div class="employees-table-wrapper">
                    <table class="employees-table table mb-0" id="engineersTable" style="{{ $userType == 'engineer' ? '' : 'display:none' }}">
                        <thead>
                            <tr>
                                <th class="checkbox-cell">
                                    <input type="checkbox" id="selectAllEngineers">
                                </th>
                                <th>#</th>
                                <th>الاسم الكامل</th>
                                <th>الرقم الوطني</th>
                                <th>المحافظة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($availableEngineers as $index => $engineer)
                            <tr>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="user_ids[]" value="{{ $engineer->id }}" class="employee-checkbox engineer-checkbox">
                                </td>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $engineer->full_name }}</td>
                                <td>{{ $engineer->national_id }}</td>
                                <td>{{ $engineer->workGovernorate->name ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="no-employees">
                                    <i class="fas fa-inbox"></i>
                                    <div>جميع المهندسين تم تسجيل دوامهم في هذا التاريخ</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <table class="employees-table table mb-0" id="supervisorsTable" style="{{ $userType == 'supervisor' ? '' : 'display:none' }}">
                        <thead>
                            <tr>
                                <th class="checkbox-cell">
                                    <input type="checkbox" id="selectAllSupervisors">
                                </th>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>منطقة العمل</th>
                                <th>المحافظة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($availableSupervisors as $index => $supervisor)
                            <tr>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="user_ids[]" value="{{ $supervisor->id }}" class="employee-checkbox supervisor-checkbox">
                                </td>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $supervisor->name }}</td>
                                <td>{{ $supervisor->mainWorkArea->name ?? '-' }}</td>
                                <td>{{ $supervisor->governorate->name ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="no-employees">
                                    <i class="fas fa-inbox"></i>
                                    <div>جميع المشرفين تم تسجيل دوامهم في هذا التاريخ</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="أضف ملاحظات عامة لجميع الموظفين المحددين..."></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('attendance.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-right"></i> رجوع
                </a>

                <button type="submit" class="btn btn-submit btn-primary" id="submitBtn" disabled>
                    <i class="fas fa-save"></i> حفظ الدوام
                </button>
            </div>

        </form>

    </div>

</div>
@endsection


@push('scripts')
<script>

document.querySelectorAll('.user-type-card').forEach(card => {
    card.addEventListener('click', function () {
        document.querySelectorAll('.user-type-card').forEach(c => c.classList.remove('active'));
        this.classList.add('active');

        let type = this.dataset.type;
        
        if (type === "engineer") {
            engineersTable.style.display = "table";
            supervisorsTable.style.display = "none";
            document.querySelectorAll('.supervisor-checkbox').forEach(cb => cb.checked = false);
        } else {
            engineersTable.style.display = "table";
            supervisorsTable.style.display = "none";
            document.querySelectorAll('.engineer-checkbox').forEach(cb => cb.checked = false);
        }
        
        updateSelectedCount();
        
        const date = attendanceDate.value;
        window.location.href = `{{ route('attendance.create') }}?date=${date}&user_type=${type}`;
    });
});

document.getElementById('selectAllEngineers')?.addEventListener('change', function() {
    document.querySelectorAll('.engineer-checkbox').forEach(cb => {
        cb.checked = this.checked;
    });
    updateSelectedCount();
});

document.getElementById('selectAllSupervisors')?.addEventListener('change', function() {
    document.querySelectorAll('.supervisor-checkbox').forEach(cb => {
        cb.checked = this.checked;
    });
    updateSelectedCount();
});

document.querySelectorAll('.employee-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedCount);
});

function updateSelectedCount() {
    const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');
    const count = checkedBoxes.length;
    
    document.getElementById('countNumber').textContent = count;
    document.getElementById('selectedCount').style.display = count > 0 ? 'block' : 'none';
    document.getElementById('submitBtn').disabled = count === 0;
}

function checkFriday() {
    const date = new Date(attendanceDate.value);
    fridayWarning.style.display = (date.getDay() === 5) ? 'block' : 'none';
}

attendanceDate.addEventListener("change", function() {
    checkFriday();
    const userType = document.querySelector('input[name="user_type"]:checked')?.value || 'engineer';
    window.location.href = `{{ route('attendance.create') }}?date=${this.value}&user_type=${userType}`;
});

checkFriday();

document.querySelectorAll('.status-card').forEach(card => {
    card.addEventListener('click', function () {
        document.querySelectorAll('.status-card').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        this.querySelector("input").checked = true;
    });
});

document.getElementById('attendanceForm').addEventListener('submit', function(e) {
    const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');
    
    if (checkedBoxes.length === 0) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: 'يجب اختيار موظف واحد على الأقل',
        });
        return false;
    }
});

updateSelectedCount();

</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    let input = document.getElementById('attendanceDate');

    flatpickr("#attendanceDate", {
        dateFormat: "Y-m-d",
        defaultDate: input.value,
        minDate: input.min,
        maxDate: input.max,
        locale: "ar",
        allowInput: true,
        position: "below right",
        disableMobile: true
    });
});
</script>
@endpush