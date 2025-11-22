@extends('layouts.app')

@section('title', 'تفاصيل المهندس')

@push('styles')
<style>
    /* ---------- COMPACT PROFILE ---------- */
    .compact-profile-wrapper {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    /* الصورة والاسم على اليمين */
   .profile-right-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 15px; /* المسافة الطولية بين الصورة والاسم */
    min-width: 190px;
    text-align: center;
}

.profile-avatar-compact {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 3px solid #0C4079;
    object-fit: cover;
    box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    flex-shrink: 0;
}

.profile-info-compact {
    display: flex;
    flex-direction: column;
    gap: 3px;
    align-items: center;
    text-align: center;
}


    .profile-name {
        font-size: 18px;
        font-weight: 700;
        color: #0C4079;
        margin: 0;
        line-height: 1.3;
    }

    .profile-spec {
        color: #666;
        font-size: 13px;
    }

    /* التابات على اليسار */
    .profile-left-section {
        flex: 1;
        display: flex;
        justify-content: flex-start;
    }

    .profile-left-section .nav-tabs {
        border: none;
        gap: 6px;
        flex-wrap: wrap;
    }

    .profile-left-section .nav-tabs .nav-link {
        font-weight: 600;
        padding: 8px 14px;
        color: #0C4079;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        background: #f8f9fa;
        transition: all 0.2s;
        font-size: 13px;
        white-space: nowrap;
    }

    .profile-left-section .nav-tabs .nav-link:hover {
        background: #e9ecef;
        border-color: #0C4079;
    }

    .profile-left-section .nav-tabs .nav-link.active {
        background: #0C4079;
        color: white;
        border-color: #0C4079;
    }

    /* ---------- CONTENT AREA ---------- */
    .content-area {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        max-height: calc(100vh - 250px);
        overflow-y: auto;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 15px;
    }

    .info-item {
        background: #f8f9fa;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #e7e7e7;
    }

    .info-label {
        font-weight: 600;
        color: #0C4079;
        margin-bottom: 5px;
        font-size: 13px;
    }

    .info-value {
        font-size: 14px;
        color: #333;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #0C4079;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e8e8e8;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ---------- BADGES ---------- */
    .badge-custom {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-primary {
        background: #e3f2fd;
        color: #1976d2;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-danger {
        background: #f8d7da;
        color: #721c24;
    }

    .badge-warning {
        background: #fff3e0;
        color: #e65100;
    }

    /* ---------- BACK BUTTON ---------- */
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f0f0f0;
        color: #333;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 15px;
        transition: all 0.3s;
        font-size: 14px;
    }

    .back-btn:hover {
        background: #e0e0e0;
        color: #333;
    }

    /* ---------- TABLE DESIGN ---------- */
    .issues-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        font-size: 13px;
    }

    .issues-table thead {
        background: #f8f9fa;
    }

    .issues-table th {
        padding: 10px 8px;
        text-align: center;
        font-weight: 600;
        color: #0C4079;
        border: 1px solid #dee2e6;
        font-size: 13px;
    }

    .issues-table td {
        padding: 10px 8px;
        text-align: center;
        border: 1px solid #dee2e6;
    }

    .issues-table tbody tr:hover {
        background: #f8f9fa;
    }

    /* ---------- BUTTONS ---------- */
    .btn-outline-primary {
        --bs-btn-color: #083061;
        --bs-btn-border-color: #083061;
        --bs-btn-hover-color: #fff;
        --bs-btn-hover-bg: #083061;
        --bs-btn-hover-border-color: #083061;
    }

    .btn-sm {
        padding: 5px 12px;
        font-size: 13px;
    }

    /* ---------- MODAL IMPROVEMENTS ---------- */
    .modal-content {
        border-radius: 12px;
    }

    .modal-header {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        padding: 15px 20px;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-title {
        font-size: 16px;
    }

    /* ---------- SCROLLBAR ---------- */
    .content-area::-webkit-scrollbar {
        width: 8px;
    }

    .content-area::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .content-area::-webkit-scrollbar-thumb {
        background: #0C4079;
        border-radius: 10px;
    }

    .content-area::-webkit-scrollbar-thumb:hover {
        background: #083058;
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 1200px) {
        .profile-left-section .nav-tabs .nav-link {
            padding: 7px 12px;
            font-size: 12px;
        }
    }

    @media (max-width: 992px) {
        .compact-profile-wrapper {
            flex-direction: column;
            align-items: stretch;
        }

        .profile-right-section {
            justify-content: center;
            min-width: auto;
        }

        .profile-left-section {
            justify-content: center;
        }

        .content-area {
            max-height: none;
        }
    }

    @media (max-width: 768px) {
        .profile-right-section {
            flex-direction: column;
            text-align: center;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .profile-avatar-compact {
            width: 65px;
            height: 65px;
        }

        .profile-name {
            font-size: 16px;
        }

        .compact-profile-wrapper {
            padding: 15px;
        }

        .content-area {
            padding: 20px;
        }

        .back-btn {
            font-size: 13px;
            padding: 7px 14px;
        }
    }

    @media (max-width: 576px) {
        .profile-left-section .nav-tabs {
            justify-content: center;
        }

        .profile-left-section .nav-tabs .nav-link {
            font-size: 11px;
            padding: 6px 10px;
        }

        .issues-table {
            font-size: 11px;
        }

        .issues-table th,
        .issues-table td {
            padding: 8px 5px;
        }
    }
</style>
@endpush

@section('content')

<a href="{{ route('engineers.index') }}" class="back-btn">
    <i class="fas fa-arrow-right"></i>
    العودة إلى القائمة
</a>

@if (Session::has('success'))
<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        toast: true,
        position: 'bottom-start',
        icon: 'success',
        title: '{{ Session::get("success") }}',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        backdrop: false,
        customClass: {
            popup: 'medium-small-toast'
        }
    });
});
</script>
@endif

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

<!-- COMPACT PROFILE HEADER -->
<div class="compact-profile-wrapper">
    
    <!-- Right: Avatar + Name -->
    <div class="profile-right-section">
        @if($engineer->personal_image)
            <img src="{{ asset('storage/' . $engineer->personal_image) }}" class="profile-avatar-compact" alt="صورة المهندس">
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($engineer->full_name) }}&background=0C4079&color=fff&size=140" class="profile-avatar-compact" alt="صورة المهندس">
        @endif

        <div class="profile-info-compact">
            <h1 class="profile-name">{{ $engineer->full_name }}</h1>
            <div class="profile-spec">{{ $engineer->specialization ?? 'مهندس' }}</div>
        </div>
    </div>

    <!-- Left: Tabs Navigation -->
    <div class="profile-left-section">
        <ul class="nav nav-tabs" id="engineerTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#personal">البيانات الشخصية</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#home">عنوان السكن</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#work">مكان العمل</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#job">معلومات الوظيفة</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#app">بيانات تطبيق الحصر</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#bank">البنك</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#attachments">المرفقات</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#employee-attendance">الدوام</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" data-bs-target="#employee-app-problems">مشاكل تطبيق الحصر</a>
            </li>
        </ul>
    </div>

</div>

<!-- TABS CONTENT -->
<div class="tab-content content-area">

    <!-- PERSONAL -->
    <div class="tab-pane fade show active" id="personal">
        <div class="section-title"><i class="fas fa-id-card"></i> البيانات الشخصية</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">رقم الهوية</div>
                <div class="info-value">{{ $engineer->national_id }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">الجنس</div>
                <div class="info-value">{{ $engineer->gender->name ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">الحالة الاجتماعية</div>
                <div class="info-value">{{ $engineer->maritalStatus->name ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">تاريخ الميلاد</div>
                <div class="info-value">
                    @if($engineer->birth_date)
                        {{ $engineer->birth_date->format('Y-m-d') }}
                        <span class="badge-custom badge-primary">{{ $engineer->age }} سنة</span>
                    @else
                        غير محدد
                    @endif
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">رقم الجوال</div>
                <div class="info-value">{{ $engineer->mobile_1 }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">جوال إضافي</div>
                <div class="info-value">{{ $engineer->mobile_2 ?? 'غير محدد' }}</div>
            </div>
        </div>
    </div>

    <!-- HOME -->
    <div class="tab-pane fade" id="home">
        <div class="section-title"><i class="fas fa-home"></i> عنوان السكن</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">المحافظة</div>
                <div class="info-value">{{ $engineer->homeGovernorate->name ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">المدينة</div>
                <div class="info-value">{{ $engineer->homeCity->name ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item" style="grid-column:1/-1;">
                <div class="info-label">العنوان بالتفصيل</div>
                <div class="info-value">{{ $engineer->home_address_details ?? 'غير محدد' }}</div>
            </div>
        </div>
    </div>

    <!-- WORK -->
    <div class="tab-pane fade" id="work">
        <div class="section-title"><i class="fas fa-building"></i> مكان العمل</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">المحافظة</div>
                <div class="info-value">{{ $engineer->workGovernorate->name ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">المدينة</div>
                <div class="info-value">{{ $engineer->workCity->name ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">كود منطقة العمل</div>
                <div class="info-value">
                    <span class="badge-custom badge-success">{{ $engineer->work_area_code ?? 'غير محدد' }}</span>
                </div>
            </div>
            <div class="info-item" style="grid-column:1/-1;">
                <div class="info-label">مكان العمل بالتفصيل</div>
                <div class="info-value">{{ $engineer->work_address_details ?? 'غير محدد' }}</div>
            </div>
        </div>
    </div>

    <!-- JOB -->
    <div class="tab-pane fade" id="job">
        <div class="section-title"><i class="fas fa-briefcase"></i> معلومات الوظيفة</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">سنوات الخبرة</div>
                <div class="info-value">
                    <span class="badge-custom badge-success">{{ $engineer->experience_years }} سنة</span>
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">التخصص</div>
                <div class="info-value">{{ $engineer->engineer_specialization?->name ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">الراتب</div>
                <div class="info-value">{{ $engineer->salary_amount ? number_format($engineer->salary_amount,2).' '.$engineer->salaryCurrency->name : 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">تاريخ بدء العمل</div>
                <div class="info-value">{{ $engineer->work_start_date ? $engineer->work_start_date->format('Y-m-d') : 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">تاريخ نهاية العمل</div>
                <div class="info-value">{{ $engineer->work_end_date ? $engineer->work_end_date->format('Y-m-d') : 'غير محدد' }}</div>
            </div>
        </div>
    </div>

    <!-- APP INFO -->
    <div class="tab-pane fade" id="app">
        <div class="section-title"><i class="fas fa-mobile-alt"></i> بيانات التطبيق</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">اسم المستخدم</div>
                <div class="info-value">{{ $engineer->app_username ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">كلمة المرور</div>
                <div class="info-value">{{ $engineer->app_password ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">نوع الهاتف</div>
                <div class="info-value">{{ $engineer->phone_type ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">اسم الهاتف</div>
                <div class="info-value">{{ $engineer->phone_name ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">إصدار النظام</div>
                <div class="info-value">{{ $engineer->os_version ?? 'غير محدد' }}</div>
            </div>
        </div>
    </div>

    <!-- BANK -->
    <div class="tab-pane fade" id="bank">
        <div class="section-title"><i class="fas fa-university"></i> معلومات الحساب البنكي</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">البنك</div>
                <div class="info-value">{{ $engineer->bank_name ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">رقم الحساب</div>
                <div class="info-value">{{ $engineer->bank_account_number ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">رقم الآيبان</div>
                <div class="info-value">{{ $engineer->iban_number ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item" style="grid-column:1/-1;">
                <div class="info-label">اسم صاحب الحساب</div>
                <div class="info-value">
                    @php
                        $owner = array_filter([$engineer->account_owner_first, $engineer->account_owner_second, $engineer->account_owner_third, $engineer->account_owner_last]);
                    @endphp
                    {{ count($owner) ? implode(' ', $owner) : 'غير محدد' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">هوية صاحب الحساب</div>
                <div class="info-value">{{ $engineer->account_owner_national_id ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">جوال صاحب الحساب</div>
                <div class="info-value">{{ $engineer->account_owner_mobile ?? 'غير محدد' }}</div>
            </div>
        </div>
    </div>

    <!-- ATTACHMENTS -->
    <div class="tab-pane fade" id="attachments">
        <div class="section-title"><i class="fas fa-paperclip"></i> المرفقات</div>
        @if($engineer->attachments->count())
            <div class="info-grid">
                @foreach($engineer->attachments as $att)
                    <div class="info-item">
                        <div class="info-label">{{ $att->type->name ?? 'نوع غير محدد' }}</div>
                        <div class="info-value">{{ $att->file_name }}</div>
                        @if($att->details)
                            <div style="font-size:13px; color:#555; margin-top:6px;">{{ $att->details }}</div>
                        @endif
                        <div class="mt-2 d-flex gap-2">
                            <a href="{{ asset('storage/'.$att->file_path) }}" target="_blank" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> عرض
                            </a>
                            <a href="{{ asset('storage/'.$att->file_path) }}" download class="btn btn-dark btn-sm">
                                <i class="fas fa-download"></i> تحميل
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-muted">لا توجد مرفقات</div>
        @endif
    </div>

    <!-- ATTENDANCE -->
    <div class="tab-pane fade" id="employee-attendance">
        <div class="section-title">
            <i class="fas fa-user-clock"></i> جدول دوام الموظف
        </div>
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-light">
                    <tr>
                        <th>التاريخ</th>
                        <th>اليوم</th>
                        <th>الدوام</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2025-06-22</td>
                        <td>الأحد</td>
                        <td>8:00 - 4:00</td>
                        <td><span class="badge-custom badge-success">حاضر</span></td>
                    </tr>
                    <tr>
                        <td>2025-06-23</td>
                        <td>الاثنين</td>
                        <td>8:00 - 4:00</td>
                        <td><span class="badge-custom badge-warning text-dark">متأخر</span></td>
                    </tr>
                    <tr>
                        <td>2025-06-24</td>
                        <td>الثلاثاء</td>
                        <td>8:00 - 4:00</td>
                        <td><span class="badge-custom badge-danger">غائب</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- PROBLEMS -->
    <div class="tab-pane fade" id="employee-app-problems">
        <div class="section-title">
            <i class="fas fa-exclamation-triangle"></i> مشاكل تطبيق حصر الأضرار
        </div>

        <!-- زر إضافة مشكلة -->
        <div class="mb-3">
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProblemModal">
                <i class="fas fa-plus"></i> إضافة مشكلة جديدة
            </button>
        </div>

        <!-- جدول المشاكل -->
        @if($engineer->issues->count())
            <div class="table-responsive">
                <table class="issues-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نوع المشكلة</th>
                            <th>الوصف</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($engineer->issues as $index => $issue)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $issue->problem->name }}</td>
                                <td>{{ Str::limit($issue->description, 40) }}</td>
                                <td>
                                    <span class="badge-custom 
                                        @if($issue->status=='open') badge-danger 
                                        @elseif($issue->status=='in_progress') badge-warning
                                        @else badge-success @endif">
                                        @if($issue->status=='open') مفتوحة
                                        @elseif($issue->status=='in_progress') قيد المعالجة
                                        @else مغلقة @endif
                                    </span>
                                </td>
                                <td>{{ $issue->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#showProblemModal-{{ $issue->id }}">
                                        <i class="fas fa-eye"></i> عرض
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal عرض التفاصيل -->
                            <div class="modal fade" id="showProblemModal-{{ $issue->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="fas fa-bug text-primary"></i>
                                                تفاصيل المشكلة
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <strong class="text-muted">نوع المشكلة:</strong>
                                                    <p>{{ $issue->problem->name }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong class="text-muted">الحالة:</strong>
                                                    <p>
                                                        <span class="badge-custom 
                                                            @if($issue->status=='open') badge-danger 
                                                            @elseif($issue->status=='in_progress') badge-warning
                                                            @else badge-success @endif">
                                                            @if($issue->status=='open') مفتوحة
                                                            @elseif($issue->status=='in_progress') قيد المعالجة
                                                            @else مغلقة @endif
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <strong class="text-muted">وصف المشكلة:</strong>
                                                    <p>{{ $issue->description }}</p>
                                                </div>
                                                @if($issue->solution)
                                                    <div class="col-12">
                                                        <strong class="text-muted">رد الدعم الفني:</strong>
                                                        <p class="p-3 bg-light border rounded">{{ $issue->solution }}</p>
                                                    </div>
                                                @endif
                                                <div class="col-md-6">
                                                    <strong class="text-muted">تاريخ الإنشاء:</strong>
                                                    <p>{{ $issue->created_at->format('Y-m-d H:i A') }}</p>
                                                </div>
                                                @if($issue->updated_at != $issue->created_at)
                                                    <div class="col-md-6">
                                                        <strong class="text-muted">آخر تحديث:</strong>
                                                        <p>{{ $issue->updated_at->format('Y-m-d H:i A') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            @if($issue->status != 'closed')
                                                <button type="button" class="btn btn-primary btn-sm" 
                                                    data-bs-dismiss="modal"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#statusModal-{{ $issue->id }}">
                                                    <i class="fas fa-edit"></i> تحديث الحالة
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">إغلاق</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal تحديث الحالة -->
                            <div class="modal fade" id="statusModal-{{ $issue->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('issues.updateStatus', $issue->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="active_tab" value="employee-app-problems">

                                            <div class="modal-header">
                                                <h5 class="modal-title">تحديث حالة المشكلة</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">الحالة الجديدة:</label>
                                                    <select name="status" id="statusSelect-{{ $issue->id }}" class="form-select">
                                                        <option value="open" {{ $issue->status=='open' ? 'selected' : '' }}>مفتوحة</option>
                                                        <option value="in_progress" {{ $issue->status=='in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
                                                        <option value="closed" {{ $issue->status=='closed' ? 'selected' : '' }}>مغلقة</option>
                                                    </select>
                                                </div>

                                                <div id="solutionBox-{{ $issue->id }}" style="display:{{ $issue->status=='closed' ? 'block' : 'none' }};">
                                                    <label class="form-label">وصف الحل:</label>
                                                    <textarea name="solution" class="form-control" rows="3" 
                                                        placeholder="اشرح كيف تم حل المشكلة">{{ $issue->solution }}</textarea>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">إلغاء</button>
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-save"></i> حفظ التحديث
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <script>
                                document.getElementById('statusSelect-{{ $issue->id }}').addEventListener('change', function () {
                                    let box = document.getElementById('solutionBox-{{ $issue->id }}');
                                    box.style.display = this.value === "closed" ? "block" : "none";
                                });
                            </script>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted text-center">لا توجد مشاكل مسجلة لهذا المهندس.</p>
        @endif
    </div>

</div>

<!-- Modal إضافة مشكلة جديدة -->
<div class="modal fade" id="addProblemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('engineers.issues.store', $engineer->id) }}" method="POST">
                @csrf
                <input type="hidden" name="active_tab" value="employee-app-problems">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle text-primary"></i>
                        إضافة مشكلة جديدة
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">نوع المشكلة <span class="text-danger">*</span></label>
                        <select name="problem_type_id" class="form-select" required>
                            <option value="">-- اختر نوع المشكلة --</option>
                            @foreach($problemTypes as $problem)
                                <option value="{{ $problem->id }}">{{ $problem->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">وصف المشكلة <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="description" rows="4" 
                            placeholder="اكتب تفاصيل المشكلة بشكل واضح ودقيق..." required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save"></i> حفظ المشكلة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script لتفعيل نفس التاب عند العودة --}}
@if(session('active_tab'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tab = '{{ session("active_tab") }}';
    const trigger = document.querySelector(`[data-bs-target="#${tab}"]`);
    if (trigger) {
        const tabInstance = new bootstrap.Tab(trigger);
        tabInstance.show();
    }
});
</script>
@endif

@endsection