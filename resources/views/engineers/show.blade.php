@extends('layouts.app')

@section('title', 'تفاصيل المهندس')

@push('styles')
<style>
    .profile-body {
    padding: 20px;
}

/* ---------- TABS ---------- */
.nav-tabs .nav-link {
    font-weight: 700;
    padding: 10px 15px;
    color: #0C4079;
    border-radius: 6px 6px 0 0;
}

.nav-tabs .nav-link.active {
    background: #0C4079;
    color: #fff;
}

/* ---------- GRID SYSTEM ---------- */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 15px;
}

.info-item {
    background: #fff;
    padding: 15px;
    border-radius: 12px;
    border: 1px solid #e7e7e7;
}

.info-label {
    font-weight: 600;
    color: #0C4079;
    margin-bottom: 4px;
}

.info-value {
    font-size: 15px;
}

/* ---------- BADGES ---------- */
.badge-custom {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
}

.badge-primary {
    background: #0C4079;
    color: white;
}

/* ---------- ATTACHMENTS ---------- */
.attachment-card {
    border: 1px solid #e7e7e7;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 12px;
}

.attachment-card a {
    font-weight: bold;
    color: #0C4079;
}

/* ---------- RESPONSIVE ---------- */
@media (max-width: 768px) {
    .nav-tabs .nav-link {
        font-size: 14px;
        padding: 8px;
    }

    .profile-avatar-large {
        width: 120px !important;
        height: 120px !important;
    }
}
     .badge-custom {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
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

    .badge-warning {
        background: #fff3e0;
        color: #e65100;
    }

    .no-data {
        color: #999;
        font-style: italic;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f0f0f0;
        color: #333;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    .back-btn:hover {
        background: #e0e0e0;
        color: #333;
    }

    .engineer-profile {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .profile-header {
        background: linear-gradient(135deg, #0C4079 0%, #083058 100%);
        padding: 40px 30px;
        color: white;
        text-align: center;
        position: relative;
    }

    .profile-avatar-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 5px solid white;
        margin: 0 auto 20px;
        object-fit: cover;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f0f0f0;
        color: #333;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .info-item {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #0C4079;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e8e8e8;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Tabs design */
    .nav-tabs .nav-link {
        font-weight: 600;
        color: #0C4079;
        border-radius: 6px 6px 0 0;
        padding: 12px 18px;
    }
    .nav-tabs .nav-link.active {
        background: #0C4079;
        color: white;
    }

</style>
@endpush

@section('content')

<a href="{{ route('engineers.index') }}" class="back-btn">
    <i class="fas fa-arrow-right"></i>
    العودة إلى القائمة
</a>

<div class="engineer-profile">
    
    <!-- Header -->
    <div class="profile-header">

        @if($engineer->personal_image)
            <img src="{{ asset('storage/' . $engineer->personal_image) }}" class="profile-avatar-large">
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($engineer->full_name) }}&background=0C4079&color=fff&size=240" class="profile-avatar-large">
        @endif

        <h2 style="font-weight: 700; margin-bottom:6px;">{{ $engineer->full_name }}</h2>
        <div>{{ $engineer->specialization ?? 'مهندس' }}</div>
    </div>

    <!-- TABS HEADER -->
    <ul class="nav nav-tabs px-3 pt-3" id="engineerTabs" role="tablist">

        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#personal">البيانات الشخصية</a></li>

        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#home">عنوان السكن</a></li>

        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#work">مكان العمل</a></li>

        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#job">معلومات الوظيفة</a></li>

        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#app">بيانات التطبيق</a></li>

        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#bank">الحساب البنكي</a></li>

        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#attachments">المرفقات</a></li>

    </ul>

    <!-- TABS CONTENT -->
    <div class="tab-content p-4">

        <!-- PERSONAL -->
        <div class="tab-pane fade show active" id="personal">
            <div class="section-title"><i class="fas fa-id-card"></i> البيانات الشخصية</div>

            <div class="info-grid">
                <div class="info-item"><div class="info-label">رقم الهوية</div><div>{{ $engineer->national_id }}</div></div>
                <div class="info-item"><div class="info-label">الجنس</div><div>{{ $engineer->gender->name ?? 'غير محدد' }}</div></div>
                <div class="info-item"><div class="info-label">الحالة الاجتماعية</div><div>{{ $engineer->maritalStatus->name ?? 'غير محدد' }}</div></div>
                <div class="info-item"><div class="info-label">تاريخ الميلاد</div>
                    @if($engineer->birth_date)
                        {{ $engineer->birth_date->format('Y-m-d') }}
                            <span class="badge-custom badge-primary">{{ $engineer->age }} سنة</span>
                    @else
                        غير محدد
                    @endif
                </div>
                <div class="info-item"><div class="info-label">رقم الجوال</div><div>{{ $engineer->mobile_1 }}</div></div>
                <div class="info-item"><div class="info-label">جوال إضافي</div><div>{{ $engineer->mobile_2 ?? 'غير محدد' }}</div></div>
            </div>
        </div>

        <!-- HOME -->
        <div class="tab-pane fade" id="home">
            <div class="section-title"><i class="fas fa-home"></i> عنوان السكن</div>
            
            <div class="info-grid">
                <div class="info-item"><div class="info-label">المحافظة</div><div>{{ $engineer->homeGovernorate->name ?? 'غير محدد' }}</div></div>
                <div class="info-item"><div class="info-label">المدينة</div><div>{{ $engineer->homeCity->name ?? 'غير محدد' }}</div></div>
                <div class="info-item" style="grid-column:1/-1;"><div class="info-label">العنوان بالتفصيل</div>{{ $engineer->home_address_details ?? 'غير محدد' }}</div>
            </div>
        </div>

        <!-- WORK -->
        <div class="tab-pane fade" id="work">
            <div class="section-title"><i class="fas fa-building"></i> مكان العمل</div>
            
            <div class="info-grid">
                <div class="info-item"><div class="info-label">المحافظة</div>{{ $engineer->workGovernorate->name ?? 'غير محدد' }}</div>
                <div class="info-item"><div class="info-label">المدينة</div>{{ $engineer->workCity->name ?? 'غير محدد' }}</div>
                <div class="info-item" style="grid-column:1/-1;"><div class="info-label">مكان العمل بالتفصيل</div>{{ $engineer->work_address_details ?? 'غير محدد' }}</div>
            </div>
        </div>

        <!-- JOB -->
        <div class="tab-pane fade" id="job">
            <div class="section-title"><i class="fas fa-briefcase"></i> معلومات الوظيفة</div>
            
            <div class="info-grid">
                <div class="info-item"><div class="info-label">سنوات الخبرة</div><span class="badge-custom badge-success">{{ $engineer->experience_years }} سنة</span></div>
                <div class="info-item"><div class="info-label">التخصص</div>{{ $engineer->specialization ?? 'غير محدد' }}</div>
                <div class="info-item"><div class="info-label">الراتب</div>{{ $engineer->salary_amount ? number_format($engineer->salary_amount,2).' '.$engineer->salaryCurrency->name : 'غير محدد' }}</div>
                <div class="info-item"><div class="info-label">تاريخ بدء العمل</div>{{ $engineer->work_start_date ? $engineer->work_start_date->format('Y-m-d') : 'غير محدد' }}</div>
                <div class="info-item"><div class="info-label">تاريخ نهاية العمل</div>{{ $engineer->work_end_date ? $engineer->work_end_date->format('Y-m-d') : 'غير محدد' }}</div>
            </div>
        </div>

        <!-- APP INFO -->
        <div class="tab-pane fade" id="app">
            <div class="section-title"><i class="fas fa-mobile-alt"></i> بيانات التطبيق</div>

            <div class="info-grid">
                <div class="info-item"><div class="info-label">اسم المستخدم</div>{{ $engineer->app_username ?? 'غير محدد' }}</div>
                <div class="info-item"><div class="info-label">كلمة المرور</div>{{ $engineer->app_password ?? 'غير محدد' }}</div>
                <div class="info-item"><div class="info-label">نوع الهاتف</div>{{ $engineer->phone_type ?? 'غير محدد' }}</div>
                <div class="info-item"><div class="info-label">اسم الهاتف</div>{{ $engineer->phone_name ?? 'غير محدد' }}</div>
                <div class="info-item"><div class="info-label">إصدار النظام</div>{{ $engineer->os_version ?? 'غير محدد' }}</div>
            </div>
        </div>

        <!-- BANK -->
        <div class="tab-pane fade" id="bank">
            <div class="section-title"><i class="fas fa-university"></i> معلومات الحساب البنكي</div>

            <div class="info-grid">
                <div class="info-item"><div class="info-label">البنك</div>{{ $engineer->bank_name ?? 'غير محدد' }}</div>
                <div class="info-item"><div class="info-label">رقم الحساب</div>{{ $engineer->bank_account_number ?? 'غير محدد' }}</div>
                <div class="info-item"><div class="info-label">رقم الآيبان</div>{{ $engineer->iban_number ?? 'غير محدد' }}</div>

                <div class="info-item" style="grid-column:1/-1;">
                    <div class="info-label">اسم صاحب الحساب</div>
                    @php
                        $owner = array_filter([$engineer->account_owner_first, $engineer->account_owner_second, $engineer->account_owner_third, $engineer->account_owner_last]);
                    @endphp
                    {{ count($owner) ? implode(' ', $owner) : 'غير محدد' }}
                </div>

                <div class="info-item"><div class="info-label">هوية صاحب الحساب</div>{{ $engineer->account_owner_national_id ?? 'غير محدد' }}</div>
                <div class="info-item"><div class="info-label">جوال صاحب الحساب</div>{{ $engineer->account_owner_mobile ?? 'غير محدد' }}</div>
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
                                <div style="font-size:14px; color:#555;">{{ $att->details }}</div>
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
                <div class="text-muted">لا توجد مرفقات</div>
            @endif
        </div>

    </div>
</div>

@endsection
