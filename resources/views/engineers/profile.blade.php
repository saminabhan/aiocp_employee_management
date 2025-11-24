@extends('layouts.app')

@section('title', 'الملف الشخصي')

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

    /* الصورة والاسم والتخصص في المنتصف */
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
        width: 120px;
        height: 120px;
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
        font-size: 24px;
        font-weight: 800;
        color: #0C4079;
        margin: 0;
        line-height: 1.2;
    }

    .profile-spec {
        color: #666;
        font-size: 16px;
        font-weight: 500;
    }

    /* التابات الأفقية تحت الاسم */
    .profile-tabs-section {
        width: 100%;
        display: flex;
        justify-content: center;
        margin-top: 15px;
    }

    .profile-tabs-section .nav-tabs {
        border: none;
        gap: 5px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .profile-tabs-section .nav-tabs .nav-link {
        font-weight: 600;
        padding: 10px 16px;
        color: #0C4079;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        background: #f8f9fa;
        transition: all 0.2s;
        font-size: 14px;
        white-space: nowrap;
        margin: 0 2px;
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
        padding: 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        max-height: calc(130vh - 350px);
        overflow-y: auto;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .info-item {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #e7e7e7;
        transition: all 0.2s;
    }

    .info-item:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }

    .info-label {
        font-weight: 600;
        color: #0C4079;
        margin-bottom: 8px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .info-label i {
        font-size: 14px;
    }

    .info-value {
        font-size: 15px;
        color: #333;
        font-weight: 500;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #0C4079;
        margin-bottom: 25px;
        padding-bottom: 12px;
        border-bottom: 3px solid #0C4079;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        font-size: 20px;
    }

    /* ---------- BADGES ---------- */
    .badge-custom {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
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
            padding: 9px 14px;
            font-size: 13px;
        }
    }

    @media (max-width: 992px) {
        .profile-header-wrapper {
            padding: 30px 20px;
        }

        .profile-avatar-compact {
            width: 110px;
            height: 110px;
        }

        .profile-name {
            font-size: 22px;
        }

        .profile-spec {
            font-size: 15px;
        }
    }

    @media (max-width: 768px) {
        .profile-header-wrapper {
            padding: 25px 15px;
        }

        .profile-avatar-compact {
            width: 100px;
            height: 100px;
        }

        .profile-name {
            font-size: 20px;
        }

        .profile-spec {
            font-size: 14px;
        }

        .profile-tabs-section .nav-tabs {
            gap: 3px;
        }

        .profile-tabs-section .nav-tabs .nav-link {
            padding: 8px 12px;
            font-size: 12px;
        }

        .info-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }
    }

    @media (max-width: 576px) {
        .profile-header-wrapper {
            padding: 20px 12px;
        }

        .profile-avatar-compact {
            width: 95px;
            height: 95px;
        }

        .profile-name {
            font-size: 18px;
        }

        .profile-spec {
            font-size: 13px;
        }

        .profile-tabs-section .nav-tabs {
            gap: 2px;
        }

        .profile-tabs-section .nav-tabs .nav-link {
            padding: 7px 10px;
            font-size: 11px;
            margin: 0 1px;
        }

        .content-area {
            padding: 20px 15px;
        }

        .section-title {
            font-size: 16px;
        }

        .info-item {
            padding: 12px;
        }
    }
</style>
@endpush

@section('content')

<!-- PROFILE HEADER - CENTERED -->
<div class="profile-header-wrapper" dir="rtl">
    
    <!-- Center: Avatar + Name + Spec + Tabs -->
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
        </div>

        <!-- Horizontal Tabs Under Name -->
        <div class="profile-tabs-section">
            <ul class="nav nav-tabs" id="engineerTabs" role="tablist">
                <li class="nav-items-custom" role="presentation">
                    <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
                        <i class="fas fa-id-card"></i> البيانات الشخصية
                    </button>
                </li>
                <li class="nav-items-custom" role="presentation">
                    <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab">
                        <i class="fas fa-home"></i> عنوان السكن
                    </button>
                </li>
                <li class="nav-items-custom" role="presentation">
                    <button class="nav-link" id="work-tab" data-bs-toggle="tab" data-bs-target="#work" type="button" role="tab">
                        <i class="fas fa-building"></i> مكان العمل
                    </button>
                </li>
                <li class="nav-items-custom" role="presentation">
                    <button class="nav-link" id="job-tab" data-bs-toggle="tab" data-bs-target="#job" type="button" role="tab">
                        <i class="fas fa-briefcase"></i> الوظيفة
                    </button>
                </li>
                <li class="nav-items-custom" role="presentation">
                    <button class="nav-link" id="app-tab" data-bs-toggle="tab" data-bs-target="#app" type="button" role="tab">
                        <i class="fas fa-mobile-alt"></i> التطبيق
                    </button>
                </li>
                <li class="nav-items-custom" role="presentation">
                    <button class="nav-link" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank" type="button" role="tab">
                        <i class="fas fa-university"></i> البنك
                    </button>
                </li>
                <li class="nav-items-custom" role="presentation">
                    <button class="nav-link" id="attachments-tab" data-bs-toggle="tab" data-bs-target="#attachments" type="button" role="tab">
                        <i class="fas fa-paperclip"></i> المرفقات
                    </button>
                </li>
            </ul>
        </div>
    </div>

</div>

<!-- TABS CONTENT -->
<div class="tab-content content-area" id="engineerTabContent" dir="rtl">

    <!-- PERSONAL -->
    <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
        <div class="section-title">
            <i class="fas fa-id-card"></i> البيانات الشخصية
        </div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-fingerprint"></i> رقم الهوية
                </div>
                <div class="info-value">{{ $engineer->national_id }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-venus-mars"></i> الجنس
                </div>
                <div class="info-value">{{ $engineer->gender->name ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-heart"></i> الحالة الاجتماعية
                </div>
                <div class="info-value">{{ $engineer->maritalStatus->name ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-calendar-alt"></i> تاريخ الميلاد
                </div>
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
                <div class="info-label">
                    <i class="fas fa-phone"></i> رقم الجوال
                </div>
                <div class="info-value">{{ $engineer->mobile_1 }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-mobile-alt"></i> جوال إضافي
                </div>
                <div class="info-value">{{ $engineer->mobile_2 ?? 'غير محدد' }}</div>
            </div>
        </div>
    </div>

    <!-- HOME -->
    <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="section-title">
            <i class="fas fa-home"></i> عنوان السكن
        </div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-map-marked-alt"></i> المحافظة
                </div>
                <div class="info-value">{{ $engineer->homeGovernorate->name ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-city"></i> المدينة
                </div>
                <div class="info-value">{{ $engineer->homeCity->name ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item" style="grid-column:1/-1;">
                <div class="info-label">
                    <i class="fas fa-map-marker-alt"></i> العنوان بالتفصيل
                </div>
                <div class="info-value">{{ $engineer->home_address_details ?? 'غير محدد' }}</div>
            </div>
        </div>
    </div>

    <!-- WORK -->
    <div class="tab-pane fade" id="work" role="tabpanel" aria-labelledby="work-tab">
        <div class="section-title">
            <i class="fas fa-building"></i> مكان العمل
        </div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-map-marked-alt"></i> المحافظة
                </div>
                <div class="info-value">{{ $engineer->workGovernorate->name ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-city"></i> المدينة
                </div>
                <div class="info-value">{{ $engineer->workCity->name ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-barcode"></i> كود منطقة العمل
                </div>
                <div class="info-value">
                    <span class="badge-custom badge-success">
                        {{ $engineer->mainWorkAreaCode->name ?? 'غير محدد' }}
                    </span>
                </div>
            </div>
            <div class="info-item" style="grid-column:1/-1;">
                <div class="info-label">
                    <i class="fas fa-map-marker-alt"></i> مكان العمل بالتفصيل
                </div>
                <div class="info-value">{{ $engineer->work_address_details ?? 'غير محدد' }}</div>
            </div>
        </div>
    </div>

    <!-- JOB -->
    <div class="tab-pane fade" id="job" role="tabpanel" aria-labelledby="job-tab">
        <div class="section-title">
            <i class="fas fa-briefcase"></i> معلومات الوظيفة
        </div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-award"></i> سنوات الخبرة
                </div>
                <div class="info-value">
                    <span class="badge-custom badge-success">{{ $engineer->experience_years }} سنة</span>
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-user-tie"></i> التخصص
                </div>
                <div class="info-value">{{ $engineer->engineer_specialization?->name ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-money-bill-wave"></i> الراتب
                </div>
                <div class="info-value">{{ $engineer->salary_amount ? number_format($engineer->salary_amount,2).' '.$engineer->salaryCurrency->name : 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-calendar-check"></i> تاريخ بدء العمل
                </div>
                <div class="info-value">{{ $engineer->work_start_date ? $engineer->work_start_date->format('Y-m-d') : 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-calendar-times"></i> تاريخ نهاية العمل
                </div>
                <div class="info-value">{{ $engineer->work_end_date ? $engineer->work_end_date->format('Y-m-d') : 'غير محدد' }}</div>
            </div>
        </div>
    </div>

    <!-- APP INFO -->
    <div class="tab-pane fade" id="app" role="tabpanel" aria-labelledby="app-tab">
        <div class="section-title">
            <i class="fas fa-mobile-alt"></i> بيانات التطبيق
        </div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-user"></i> اسم المستخدم
                </div>
                <div class="info-value">{{ $engineer->app_username ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-lock"></i> كلمة المرور
                </div>
                <div class="info-value">{{ $engineer->app_password ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-mobile"></i> نوع الهاتف
                </div>
                <div class="info-value">{{ $engineer->phone_type ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-phone-alt"></i> اسم الهاتف
                </div>
                <div class="info-value">{{ $engineer->phone_name ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-cog"></i> إصدار النظام
                </div>
                <div class="info-value">{{ $engineer->os_version ?? 'غير محدد' }}</div>
            </div>
        </div>
    </div>

    <!-- BANK -->
    <div class="tab-pane fade" id="bank" role="tabpanel" aria-labelledby="bank-tab">
        <div class="section-title">
            <i class="fas fa-university"></i> معلومات الحساب البنكي
        </div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-landmark"></i> البنك
                </div>
                <div class="info-value">{{ $engineer->bank_name ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-credit-card"></i> رقم الحساب
                </div>
                <div class="info-value">{{ $engineer->bank_account_number ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-barcode"></i> رقم الآيبان
                </div>
                <div class="info-value">{{ $engineer->iban_number ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item" style="grid-column:1/-1;">
                <div class="info-label">
                    <i class="fas fa-user-circle"></i> اسم صاحب الحساب
                </div>
                <div class="info-value">
                    @php
                        $owner = array_filter([$engineer->account_owner_first, $engineer->account_owner_second, $engineer->account_owner_third, $engineer->account_owner_last]);
                    @endphp
                    {{ count($owner) ? implode(' ', $owner) : 'غير محدد' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-id-card"></i> هوية صاحب الحساب
                </div>
                <div class="info-value">{{ $engineer->account_owner_national_id ?? 'غير محدد' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">
                    <i class="fas fa-phone"></i> جوال صاحب الحساب
                </div>
                <div class="info-value">{{ $engineer->account_owner_mobile ?? 'غير محدد' }}</div>
            </div>
        </div>
    </div>

    <!-- ATTACHMENTS -->
    <div class="tab-pane fade" id="attachments" role="tabpanel" aria-labelledby="attachments-tab">
        <div class="section-title">
            <i class="fas fa-paperclip"></i> المرفقات
        </div>
        @if($engineer->attachments->count())
            <div class="info-grid">
                @foreach($engineer->attachments as $att)
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-file"></i> {{ $att->type->name ?? 'نوع غير محدد' }}
                        </div>
                        <div class="info-value">{{ $att->file_name }}</div>
                        @if($att->details)
                            <div style="font-size:13px; color:#555; margin-top:8px;">
                                <i class="fas fa-info-circle"></i> {{ $att->details }}
                            </div>
                        @endif
                        <div class="mt-3 d-flex gap-2">
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
            <div class="text-center text-muted py-5">
                <i class="fas fa-folder-open" style="font-size: 48px; opacity: 0.3;"></i>
                <p class="mt-3">لا توجد مرفقات</p>
            </div>
        @endif
    </div>

</div>

@endsection