@extends('layouts.app')

@section('title', 'تسجيل مزامنة مهندسين')

@push('styles')
<style>
/* === نفس الستايل بدون أي تغيير === */
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
}
.status-cards {
    display: grid;
    grid-template-columns: repeat(2,1fr);
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
    font-size: 36px;
    margin-bottom: 10px;
}
.status-card.synced.active {
    border-color: #28a745;
    background: #d4edda;
}
.status-card.not-synced.active {
    border-color: #dc3545;
    background: #f8d7da;
}
.employees-table-wrapper {
    max-height: 500px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 8px;
}
.employees-table thead {
    position: sticky;
    top: 0;
    background: #0C4079;
    color: white;
}
.selected-count {
    background: #e3f2fd;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 10px;
    color: #0C4079;
    font-weight: 600;
    display: none;
}
.btn-submit {
    background: #0C4079;
    border-color: #0C4079;
    padding: 12px 30px;
    font-weight: 600;
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
    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<div class="container" dir="rtl">

    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-sync-alt"></i>
            تسجيل مزامنة مهندسين
        </h1>
    </div>

    <div class="form-card">

        <form action="{{ route('engineer-sync.store') }}" method="POST" id="syncForm">
            @csrf

            {{-- التاريخ --}}
            <div class="mb-4">
                <label class="form-label">تاريخ المزامنة *</label>
                <input
                    type="date"
                    name="sync_date"
                    id="dateInput"
                    class="form-control"
                    value="{{ $selectedDate ?? now()->format('Y-m-d') }}"
                    min="{{ $minDate ?? now()->subMonth()->format('Y-m-d') }}"
                    max="{{ $maxDate ?? now()->addMonth()->format('Y-m-d') }}"
                >

                <div class="date-warning mt-2" id="fridayWarning" style="display:none">
                    <i class="fas fa-exclamation-triangle"></i>
                    التاريخ المختار يوم جمعة (إجازة أسبوعية)
                </div>
            </div>

            {{-- حالة المزامنة --}}
            <div class="mb-4">
                <label class="form-label">حالة المزامنة *</label>
                <div class="status-cards">
                    <label class="status-card synced active">
                        <input type="radio" name="is_synced" value="1" hidden checked>
                        <i class="fas fa-check-circle text-success"></i>
                        <div>تمت المزامنة</div>
                    </label>

                    <label class="status-card not-synced">
                        <input type="radio" name="is_synced" value="0" hidden>
                        <i class="fas fa-times-circle text-danger"></i>
                        <div>لم تتم</div>
                    </label>
                </div>
            </div>

            {{-- العدّاد --}}
            <div class="selected-count" id="selectedCount">
                تم اختيار <span id="countNumber">0</span> مهندس
            </div>

            {{-- جدول المهندسين --}}
            <div class="employees-table-wrapper mb-4">
                <table class="table employees-table mb-0">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>المنطقة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($availableEngineers as $index => $engineer)
                        <tr>
                            <td>
                                <input type="checkbox"
                                       name="engineer_ids[]"
                                       value="{{ $engineer->id }}"
                                       class="engineer-checkbox">
                            </td>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $engineer->full_name }}</td>
                            <td>{{ $engineer->mainWorkAreaCode?->name ?? 'غير محدد' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                جميع المهندسين مسجلين لهذا اليوم
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ملاحظات --}}
            <div class="mb-4">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>

            {{-- أزرار --}}
            <div class="d-flex justify-content-between">
                <a href="{{ route('engineer-sync.index') }}" class="btn btn-secondary">
                    رجوع
                </a>
                <button type="submit" class="btn btn-submit btn-primary" id="submitBtn" disabled>
                    حفظ
                </button>
            </div>

        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
const checkboxes = document.querySelectorAll('.engineer-checkbox');
const selectAll = document.getElementById('selectAll');
const countBox = document.getElementById('selectedCount');
const countNumber = document.getElementById('countNumber');
const submitBtn = document.getElementById('submitBtn');

function updateCount() {
    const count = document.querySelectorAll('.engineer-checkbox:checked').length;
    countNumber.textContent = count;
    countBox.style.display = count > 0 ? 'block' : 'none';
    submitBtn.disabled = count === 0;
}

checkboxes.forEach(cb => cb.addEventListener('change', updateCount));

selectAll?.addEventListener('change', function () {
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateCount();
});

document.querySelectorAll('.status-card').forEach(card => {
    card.addEventListener('click', function () {
        document.querySelectorAll('.status-card').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        this.querySelector('input').checked = true;
    });
});
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const dateInput = document.getElementById('dateInput');
    const fridayWarning = document.getElementById('fridayWarning');

    flatpickr(dateInput, {
        dateFormat: "Y-m-d",
        defaultDate: dateInput.value,
        minDate: dateInput.min,
        maxDate: dateInput.max,
        locale: "ar",
        allowInput: true,
        disableMobile: true,
        position: "auto right",
        appendTo: dateInput.parentElement,
        onChange: function(selectedDates, dateStr) {
            checkFriday(selectedDates[0]);

            // ⭐⭐⭐ هذا هو السطر المهم ⭐⭐⭐
            window.location.href =
                `{{ route('engineer-sync.create') }}?date=${dateStr}`;
        }
    });

    function checkFriday(date) {
        if (!date) return;
        fridayWarning.style.display = (date.getDay() === 5) ? 'block' : 'none';
    }

    checkFriday(new Date(dateInput.value));
});
</script>

@endpush
