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

</style>
@endpush


@section('content')
<div class="container" dir="rtl">

    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-calendar-check"></i>
            تسجيل دوام جديد
        </h1>
    </div>


    <div class="form-card">

        <form action="{{ route('attendance.store') }}" method="POST" id="attendanceForm">
            @csrf

            <label class="form-label">نوع المستخدم <span class="required-star">*</span></label>

            <div class="user-type-boxes mb-4">
                <label class="user-type-card {{ old('user_type')=='engineer' ? 'active' : '' }}" data-type="engineer">
                    <input type="radio" name="user_type" value="engineer" hidden {{ old('user_type')=='engineer' ? 'checked' : '' }}>
                    <i class="fas fa-hard-hat"></i>
                    <div>مهندس حصر</div>
                </label>

                <label class="user-type-card {{ old('user_type')=='supervisor' ? 'active' : '' }}" data-type="supervisor">
                    <input type="radio" name="user_type" value="supervisor" hidden {{ old('user_type')=='supervisor' ? 'checked' : '' }}>
                    <i class="fas fa-user-tie"></i>
                    <div>مشرف حصر</div>
                </label>
            </div>


            <div class="mb-4">
                <label class="form-label">اختر الشخص <span class="required-star">*</span></label>

                <select name="user_id" id="engineerSelect" class="form-select user-select" style="display:none;">
                    <option value="">-- اختر المهندس --</option>
                    @foreach($engineers as $engineer)
                        <option value="{{ $engineer->id }}" {{ old('user_id')==$engineer->id ? 'selected' : '' }}>
                            {{ $engineer->full_name }} - {{ $engineer->national_id }}
                        </option>
                    @endforeach
                </select>

                <select name="user_id" id="supervisorSelect" class="form-select user-select" style="display:none;">
                    <option value="">-- اختر المشرف --</option>
                    @foreach($supervisors as $supervisor)
                        <option value="{{ $supervisor->id }}" {{ old('user_id')==$supervisor->id ? 'selected' : '' }}>
                            {{ $supervisor->name }} - {{ $supervisor->mainWorkArea->name }}
                        </option>
                    @endforeach
                </select>

            </div>


            <div class="mb-4">
                <label class="form-label">تاريخ الدوام <span class="required-star">*</span></label>

                <!-- <div class="alert alert-info py-2">
                    <i class="fas fa-info-circle"></i>
                    يمكن التسجيل ليوم واحد للخلف فقط.
                </div> -->

                <input type="date" name="attendance_date" id="attendanceDate"
                       class="form-control"
                       value="{{ old('attendance_date', now()->format('Y-m-d')) }}"
                       min="{{ $minDate }}" max="{{ $maxDate }}">

                <div class="date-warning" id="fridayWarning">
                    <i class="fas fa-exclamation-triangle"></i>
                    التاريخ المختار يوم جمعة (إجازة أسبوعية)
                </div>
            </div>


            <label class="form-label">حالة الحضور <span class="required-star">*</span></label>

            <div class="status-cards mb-4">

                <label class="status-card present {{ old('status','present')=='present' ? 'active' : '' }}">
                    <input type="radio" name="status" value="present" hidden {{ old('status','present')=='present' ? 'checked' : '' }}>
                    <i class="fas fa-check-circle text-success"></i>
                    <div>حاضر</div>
                </label>

                <label class="status-card absent {{ old('status')=='absent' ? 'active' : '' }}">
                    <input type="radio" name="status" value="absent" hidden {{ old('status')=='absent' ? 'checked' : '' }}>
                    <i class="fas fa-times-circle text-danger"></i>
                    <div>غائب</div>
                </label>

                <label class="status-card leave {{ old('status')=='leave' ? 'active' : '' }}">
                    <input type="radio" name="status" value="leave" hidden {{ old('status')=='leave' ? 'checked' : '' }}>
                    <i class="fas fa-briefcase text-warning"></i>
                    <div>إجازة</div>
                </label>

            </div>


            <div class="mb-4">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
            </div>


            <div class="d-flex justify-content-between">
                <a href="{{ route('attendance.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-right"></i> رجوع
                </a>

                <button type="submit" class="btn btn-submit btn-primary">
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

        document.querySelectorAll('.user-select').forEach(sel => {
            sel.style.display = "none";
            sel.removeAttribute("name");
        });

        if (type === "engineer") {
            engineerSelect.style.display = "block";
            engineerSelect.setAttribute("name", "user_id");
        } else {
            supervisorSelect.style.display = "block";
            supervisorSelect.setAttribute("name", "user_id");
        }
    });
});


document.querySelectorAll('.status-card').forEach(card => {
    card.addEventListener('click', function () {
        document.querySelectorAll('.status-card').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        this.querySelector("input").checked = true;
    });
});


function checkFriday() {
    const date = new Date(attendanceDate.value);
    fridayWarning.style.display = (date.getDay() === 5) ? 'block' : 'none';
}
attendanceDate.addEventListener("change", checkFriday);
checkFriday();


let checkTimeout;
document.getElementById('attendanceForm').addEventListener('change', function(e) {

    if (!['user_type','user_id','attendance_date'].includes(e.target.name)) return;

    clearTimeout(checkTimeout);

    checkTimeout = setTimeout(() => {

        let userType = document.querySelector('input[name="user_type"]:checked')?.value;
        let userId = document.querySelector('.user-select[style*="block"]')?.value;
        let date = attendanceDate.value;

        if (!userType || !userId || !date) return;

        fetch('{{ route("attendance.checkAvailability") }}', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ user_type: userType, user_id: userId, date: date })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.available) {
                Swal.fire({
                    icon: 'warning',
                    title: 'تنبيه',
                    text: data.message,
                });
            }
        });

    }, 400);
});

</script>
@endpush
