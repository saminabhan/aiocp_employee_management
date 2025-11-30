@extends('layouts.app')

@section('title', 'تفاصيل المهندس')

@push('styles')
<style>
    /* ---------- PROFILE HEADER - CENTERED LAYOUT ---------- */
    .profile-header-wrapper {
        background: white;
        border-radius: 12px;
        padding: 30px 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 20px;
        text-align: center;
        position: relative;
    }

    /* الزر العودة في الزاوية */
    .back-btn-wrapper {
        position: absolute;
        top: 20px;
        right: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

        .back-btn-wrapper-left {
        position: absolute;
        top: 20px;
        left: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 1200px) {
        .back-btn-wrapper {
            top: 15px;
            right: 15px;
        }
        ..back-btn-wrapper-left{
            top: 15px;
            left: 15px;
        }

        .back-btn {
            padding: 7px 14px;
            font-size: 13px;
        }

        .profile-tabs-section .nav-tabs .nav-link {
            padding: 9px 12px;
            font-size: 12px;
        }
    }

    @media (max-width: 992px) {
        .profile-header-wrapper {
            padding: 50px 20px 25px 20px;
        }

        .back-btn-wrapper {
            position: static;
            margin-bottom: 0;
            width: 100%;
            justify-content: flex-end;
            padding-right: 10px;
        }
        
        .back-btn-wrapper-left{
            position: static;
            margin-bottom: 0;
            width: 100%;
            justify-content: flex-end;
            padding-right: 10px;
        }

        .back-btn {
            padding: 7px 12px;
            font-size: 12px;
        }

        .profile-avatar-compact {
            width: 100px;
            height: 100px;
        }

        .profile-name {
            font-size: 20px;
        }
    }

    @media (max-width: 768px) {
        .profile-header-wrapper {
            padding: 50px 15px 20px 15px;
            position: relative;
        }

        .back-btn-wrapper {
            position: absolute;
            top: 15px;
            right: 15px;
            padding-right: 0;
            z-index: 10;
        }

        ..back-btn-wrapper-left {
            position: absolute;
            top: 15px;
            left: 15px;
            padding-right: 0;
            z-index: 10;
        }

        .back-btn {
            padding: 6px 10px;
            font-size: 11px;
            gap: 5px;
        }

        .back-btn i {
            font-size: 12px;
        }

        .profile-avatar-compact {
            width: 100px;
            height: 100px;
        }

        .profile-name {
            font-size: 18px;
        }

        .profile-tabs-section .nav-tabs {
            gap: 2px;
        }

        .profile-tabs-section .nav-tabs .nav-link {
            padding: 6px 9px;
            font-size: 11px;
        }
    }

    @media (max-width: 576px) {
        .profile-header-wrapper {
            padding: 15px 12px;
            position: relative;
            border-radius: 12px;
            overflow: visible;
        }

        .back-btn-wrapper {
            position: static;
            width: 100%;
            display: flex;
            justify-content: flex-end;
            margin-bottom: 15px;
            padding-right: 0;
            z-index: 10;
        }

        .back-btn-wrapper-left {
            position: static;
            width: 100%;
            display: flex;
            justify-content: flex-start;
            margin-bottom: 15px;
            padding-right: 0;
            z-index: 10;
        }

        .back-btn {
            padding: 5px 8px;
            font-size: 10px;
            gap: 4px;
            background: #fff;
            border: 1px solid #e0e0e0;
        }

        .back-btn:hover {
            background: #f0f0f0;
        }

        .back-btn i {
            font-size: 11px;
        }

        .profile-center-section {
            margin-top: 0;
            width: 100%;
        }

        .profile-avatar-compact {
            width: 95px;
            height: 95px;
        }

        .profile-name {
            font-size: 16px;
        }

        .profile-spec {
            font-size: 12px;
        }

        .profile-tabs-section {
            margin-top: 8px;
            width: 100%;
        }

        .profile-tabs-section .nav-tabs {
            gap: 1px;
        }

        .profile-tabs-section .nav-tabs .nav-link {
            padding: 5px 8px;
            font-size: 10px;
            margin: 0 0.5px;
        }
    }

    /* الصورة والاسم والتخصص في النص */
    .profile-center-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 15px;
        flex: 1;
        width: 100%;
    }

    .profile-center-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }

    .profile-avatar-compact {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid #0C4079;
        object-fit: cover;
        box-shadow: 0 4px 12px rgba(12, 64, 121, 0.15);
        flex-shrink: 0;
    }

    .profile-info-compact {
        display: flex;
        flex-direction: column;
        gap: 5px;
        align-items: center;
        text-align: center;
    }

    .profile-name {
        font-size: 16px;
        font-weight: 800;
        color: #0C4079;
        margin: 0;
        line-height: 1.2;
    }

    .profile-spec {
        color: #666;
        font-size: 12px;
        font-weight: 500;
    }

    /* التابات الأفقية تحت الاسم */
    .profile-tabs-section {
        width: 100%;
        display: flex;
        justify-content: center;
        margin-top: 10px;
    }

    .profile-tabs-section .nav-tabs {
        border: none;
        gap: 0px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .profile-tabs-section .nav-tabs .nav-link {
        font-weight: 600;
        padding: 6px 10px;
        color: #0C4079;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        background: #f8f9fa;
        transition: all 0.2s;
        font-size: 13px;
        white-space: nowrap;
        margin: 0 1px;
    }
    .profile-tabs-section .nav-tabs .nav-link:hover {
        background: #e9ecef;
        border-color: #0C4079;
        transform: translateY(-2px);
    }

    .profile-tabs-section .nav-tabs .nav-link.active {
        background: #0C4079;
        color: white;
        border-color: #0C4079;
    }

    .nav-items-custom {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    padding: 0.30rem 0.30rem;
    margin-bottom: 0.3rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    color: #555;
    font-size: 0.95rem;
    position: relative;
    white-space: nowrap;
    }

    /* ---------- CONTENT AREA ---------- */
    .content-area {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        max-height: calc(130vh - 350px);
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
    .back-btn-blue {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #0C4079;
        color: #ffff;
        padding: 10px 10px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 15px;
        transition: all 0.3s;
        font-size: 12px;
    }

        .back-btn-blue-custom {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #0C4079;
        color: #ffff;
        padding: 10px 10px;
        border-radius: 50%;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 15px;
        transition: all 0.3s;
        font-size: 12px;
    }

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
        font-size: 12px;
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
        .profile-tabs-section .nav-tabs .nav-link {
            padding: 9px 12px;
            font-size: 12px;
        }
    }

    @media (max-width: 992px) {
        .profile-header-wrapper {
            padding: 50px 20px 25px 20px;
        }

        .back-btn-wrapper {
            position: static;
            margin-bottom: 0;
            width: 100%;
            justify-content: flex-end;
            padding-right: 10px;
        }

        .back-btn {
            padding: 7px 12px;
            font-size: 12px;
        }

        .profile-avatar-compact {
            width: 100px;
            height: 100px;
        }

        .profile-name {
            font-size: 20px;
        }
    }

    @media (max-width: 768px) {
        .profile-header-wrapper {
            padding: 50px 15px 20px 15px;
            position: relative;
        }

        .back-btn-wrapper {
            position: absolute;
            top: 15px;
            right: 15px;
            padding-right: 0;
            z-index: 10;
        }

        .back-btn {
            padding: 6px 10px;
            font-size: 11px;
            gap: 5px;
        }

        .back-btn i {
            font-size: 12px;
        }

        .profile-avatar-compact {
            width: 100px;
            height: 100px;
        }

        .profile-name {
            font-size: 18px;
        }

        .profile-tabs-section .nav-tabs {
            gap: 2px;
        }

        .profile-tabs-section .nav-tabs .nav-link {
            padding: 6px 9px;
            font-size: 11px;
        }
    }

    @media (max-width: 576px) {
        .profile-header-wrapper {
            padding: 15px 12px;
            position: relative;
            border-radius: 12px;
            overflow: visible;
        }

        .back-btn-wrapper {
            position: static;
            width: 100%;
            display: flex;
            justify-content: flex-end;
            margin-bottom: 15px;
            padding-right: 0;
            z-index: 10;
        }

        .back-btn {
            padding: 5px 8px;
            font-size: 10px;
            gap: 4px;
            background: #fff;
            border: 1px solid #e0e0e0;
        }

        .back-btn:hover {
            background: #f0f0f0;
        }

        .back-btn i {
            font-size: 11px;
        }

        .profile-center-section {
            margin-top: 0;
            width: 100%;
        }

        .profile-avatar-compact {
            width: 95px;
            height: 95px;
        }

        .profile-name {
            font-size: 16px;
        }

        .profile-spec {
            font-size: 12px;
        }

        .profile-tabs-section {
            margin-top: 8px;
            width: 100%;
        }

        .profile-tabs-section .nav-tabs {
            gap: 1px;
        }

        .profile-tabs-section .nav-tabs .nav-link {
            padding: 5px 8px;
            font-size: 10px;
            margin: 0 0.5px;
        }
    }
    .equal-columns th,
.equal-columns td {
    width: 33.33%;
    text-align: center;
}

.btn-view {
        background: #e3f2fd;
        color: #1976d2;
    }
  .btn-action {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }
س
      .btn-action:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')



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
        backdrop: false
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

<div class="profile-header-wrapper" dir="rtl">
    
    <div class="back-btn-wrapper">
        <a href="{{ route('engineers.index') }}" class="back-btn">
            <i class="fas fa-arrow-right"></i> العودة إلى القائمة
        </a>
    </div>
    @if(user_can('engineers.edit'))
    <div class="back-btn-wrapper-left">
         <a href="{{ route('engineers.edit', $engineer) }}" class="back-btn-blue-custom" title="تعديل">
                <i class="fas fa-edit"></i>
            </a>
    </div>
    @endif
    <div class="profile-center-section">
        <div class="profile-center-content">
            @if($engineer->personal_image)
                <img src="{{ asset('storage/' . $engineer->personal_image) }}" class="profile-avatar-compact" alt="صورة المهندس">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($engineer->full_name) }}&background=0C4079&color=fff&size=280" class="profile-avatar-compact" alt="صورة المهندس">
            @endif

            <div class="profile-info-compact">
                <h1 class="profile-name">{{ $engineer->full_name }}</h1>
                <div class="profile-spec">{{ $engineer->specialization ?? 'مهندس' }}</div>
            </div>

                @if(!$engineer->user)
                <a href="{{ route('engineers.createAccount', $engineer->id) }}"
                class="back-btn-blue mt-2"
                onclick="return confirm('هل تريد إنشاء حساب لهذا المهندس؟')">
                إنشاء حساب للمهندس على النظام
                </a>
                @else
                <span class="badge-custom badge-success">المهندس يملك حساب على النظام</span>
                @endif

        </div>

        <div class="profile-tabs-section">
            <ul class="nav nav-tabs" id="engineerTabs" role="tablist">
                <li class="nav-items-custom" role="presentation">
                    <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">البيانات الشخصية</button>
                </li>
                <li class="nav-items-custom" role="presentation">
                    <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab">عنوان السكن</button>
                </li>
                <li class="nav-items-custom" role="presentation">
                    <button class="nav-link" id="work-tab" data-bs-toggle="tab" data-bs-target="#work" type="button" role="tab">مكان العمل</button>
                </li>
                <li class="nav-items-custom" role="presentation">
                    <button class="nav-link" id="job-tab" data-bs-toggle="tab" data-bs-target="#job" type="button" role="tab">الوظيفة</button>
                </li>
                <li class="nav-items-custom" role="presentation">
                    <button class="nav-link" id="app-tab" data-bs-toggle="tab" data-bs-target="#app" type="button" role="tab">التطبيق</button>
                </li>
                <li class="nav-items-custom" role="presentation">
                    <button class="nav-link" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank" type="button" role="tab">البنك</button>
                </li>
                <li class="nav-items-custom" role="presentation">
                    <button class="nav-link" id="attachments-tab" data-bs-toggle="tab" data-bs-target="#attachments" type="button" role="tab">المرفقات</button>
                </li>
                <li class="nav-items-custom" role="presentation">
                    <button class="nav-link" id="employee-attendance-tab" data-bs-toggle="tab" data-bs-target="#employee-attendance" type="button" role="tab">الدوام</button>
                </li>
                <li class="nav-items-custom" role="presentation">
                    <button class="nav-link" id="employee-app-problems-tab" data-bs-toggle="tab" data-bs-target="#employee-app-problems" type="button" role="tab">المشاكل</button>
                </li>
            </ul>
        </div>
    </div>

</div>

<div class="tab-content content-area" id="engineerTabContent" dir="rtl">

    <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
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

    <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
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

    <div class="tab-pane fade" id="work" role="tabpanel" aria-labelledby="work-tab">
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
                <span class="badge-custom badge-success">
                    {{ $engineer->mainWorkAreaCode->name ?? 'غير محدد' }}
                </span>
                </div>
            </div>
            <div class="info-item" style="grid-column:1/-1;">
                <div class="info-label">مكان العمل بالتفصيل</div>
                <div class="info-value">{{ $engineer->work_address_details ?? 'غير محدد' }}</div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="job" role="tabpanel" aria-labelledby="job-tab">
        <div class="section-title"><i class="fas fa-briefcase"></i> معلومات الوظيفة</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">سنوات الخبرة</div>
                <div class="info-value"><span class="badge-custom badge-success">{{ $engineer->experience_years }} سنة</span></div>
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

    <div class="tab-pane fade" id="app" role="tabpanel" aria-labelledby="app-tab">
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

    <div class="tab-pane fade" id="bank" role="tabpanel" aria-labelledby="bank-tab">
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

    <div class="tab-pane fade" id="attachments" role="tabpanel" aria-labelledby="attachments-tab">
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

<div class="tab-pane fade" id="employee-attendance" role="tabpanel" aria-labelledby="employee-attendance-tab">
    <div class="section-title"><i class="fas fa-user-clock"></i> جدول دوام الموظف</div>

    <div class="table-responsive mt-3">
      <table class="table table-bordered table-striped text-center table-fixed equal-columns">
    <thead class="table-light">
        <tr>
            <th>التاريخ</th>
            <th>اليوم</th>
            <th>الحالة</th>
        </tr>
    </thead>

    <tbody>
        @forelse($engineer->attendances as $attendance)
        <tr>
            <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('Y-m-d') }}</td>
            <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->locale('ar')->dayName }}</td>
            <td>
                @php
                    $class = match($attendance->status) {
                        'present' => 'badge-success',
                        'absent' => 'badge-danger',
                        'leave' => 'badge-info',
                        'weekend' => 'badge-secondary',
                        default => 'badge-warning'
                    };
                    $label = match($attendance->status) {
                        'present' => 'حاضر',
                        'absent' => 'غائب',
                        'leave' => 'إجازة',
                        'weekend' => 'عطلة',
                        default => 'غير معروف'
                    };
                @endphp
                <span class="badge-custom {{ $class }}">{{ $label }}</span>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3">لا يوجد بيانات دوام</td>
        </tr>
        @endforelse
    </tbody>
</table>

    </div>
</div>


    <div class="tab-pane fade" id="employee-app-problems" role="tabpanel" aria-labelledby="employee-app-problems-tab">
        <div class="section-title"><i class="fas fa-exclamation-triangle"></i> مشاكل التطبيق</div>

        <div class="mb-3">
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProblemModal">
                <i class="fas fa-plus"></i> إضافة مشكلة جديدة
            </button>
        </div>

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
                                  <a href="{{ route('issues.show', $issue->id) }}" 
                                    class="btn-action btn-view">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted text-center">لا توجد مشاكل مسجلة</p>
        @endif
    </div>

</div>

<div class="modal fade" id="addProblemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('engineers.issues.store', $engineer->id) }}" method="POST">
                @csrf
                <input type="hidden" name="active_tab" value="employee-app-problems">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle text-primary"></i> إضافة مشكلة جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" dir="rtl">
                    <div class="mb-3">
                        <label class="form-label">نوع المشكلة <span class="text-danger">*</span></label>
                        <select name="problem_type_id" class="form-select" required>
                            <option value="">-- اختر نوع المشكلة --</option>
                            @foreach($problemTypes ?? [] as $problem)
                                <option value="{{ $problem->id }}">{{ $problem->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">وصف المشكلة <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="description" rows="4" placeholder="اكتب تفاصيل المشكلة بشكل واضح..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fas fa-times"></i> إلغاء</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

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