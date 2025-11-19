@extends('layouts.app')

@section('title', 'تفاصيل المهندس')

@push('styles')
<style>
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

    .profile-name {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .profile-title {
        font-size: 16px;
        opacity: 0.9;
    }

    .profile-actions {
        position: absolute;
        top: 20px;
        left: 20px;
        display: flex;
        gap: 10px;
    }

    .btn-profile-action {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
    }

    .btn-profile-action:hover {
        background: rgba(255,255,255,0.3);
        color: white;
    }

    .profile-body {
        padding: 30px;
    }

    .info-section {
        margin-bottom: 35px;
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

    .info-label {
        font-size: 12px;
        color: #999;
        margin-bottom: 5px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .info-value {
        font-size: 15px;
        color: #333;
        font-weight: 600;
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
</style>
@endpush

@section('content')
<a href="{{ route('engineers.index') }}" class="back-btn">
    <i class="fas fa-arrow-right"></i>
    العودة إلى القائمة
</a>

<div class="engineer-profile">
    <div class="profile-header">
        <div class="profile-actions">
            <a href="{{ route('engineers.edit', $engineer) }}" class="btn-profile-action" title="تعديل">
                <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('engineers.destroy', $engineer) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المهندس؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-profile-action"style="
                        pointer-events: none;
                        opacity: 0.5;
                        cursor: not-allowed;" title="حذف">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>

        @if($engineer->personal_image)
            <img src="{{ asset('storage/' . $engineer->personal_image) }}" alt="{{ $engineer->full_name }}" class="profile-avatar-large">
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($engineer->full_name) }}&background=0C4079&color=fff&size=240" alt="{{ $engineer->full_name }}" class="profile-avatar-large">
        @endif

        <div class="profile-name">{{ $engineer->full_name }}</div>
        <div class="profile-title">{{ $engineer->specialization ?? 'مهندس' }}</div>
    </div>

    <div class="profile-body">
        <!-- البيانات الشخصية -->
        <div class="info-section">
            <div class="section-title">
                <i class="fas fa-id-card"></i>
                البيانات الشخصية
            </div>
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
                            <span class="no-data">غير محدد</span>
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">رقم الجوال الأساسي</div>
                    <div class="info-value">{{ $engineer->mobile_1 }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">رقم الجوال الفرعي</div>
                    <div class="info-value">{{ $engineer->mobile_2 ?? 'غير محدد' }}</div>
                </div>
            </div>
        </div>

        <!-- عنوان السكن -->
        <div class="info-section">
            <div class="section-title">
                <i class="fas fa-home"></i>
                عنوان السكن
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">المحافظة</div>
                    <div class="info-value">{{ $engineer->homeGovernorate->name ?? 'غير محدد' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">المدينة</div>
                    <div class="info-value">{{ $engineer->homeCity->name ?? 'غير محدد' }}</div>
                </div>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <div class="info-label">العنوان بالتفصيل</div>
                    <div class="info-value">{{ $engineer->home_address_details ?? 'غير محدد' }}</div>
                </div>
            </div>
        </div>

        <!-- مكان العمل -->
        <div class="info-section">
            <div class="section-title">
                <i class="fas fa-building"></i>
                مكان العمل
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">المحافظة</div>
                    <div class="info-value">{{ $engineer->workGovernorate->name ?? 'غير محدد' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">المدينة</div>
                    <div class="info-value">{{ $engineer->workCity->name ?? 'غير محدد' }}</div>
                </div>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <div class="info-label">مكان العمل بالتفصيل</div>
                    <div class="info-value">{{ $engineer->work_address_details ?? 'غير محدد' }}</div>
                </div>
            </div>
        </div>

        <!-- معلومات الوظيفة -->
        <div class="info-section">
            <div class="section-title">
                <i class="fas fa-briefcase"></i>
                معلومات الوظيفة
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">سنوات الخبرة</div>
                    <div class="info-value">
                        <span class="badge-custom badge-success">{{ $engineer->experience_years }} سنة</span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">التخصص</div>
                    <div class="info-value">{{ $engineer->specialization ?? 'غير محدد' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">الراتب</div>
                    <div class="info-value">
                        @if($engineer->salary_amount)
                            {{ number_format($engineer->salary_amount, 2) }} {{ $engineer->salaryCurrency->name ?? '' }}
                        @else
                            <span class="no-data">غير محدد</span>
                        @endif
                    </div>
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

        <!-- بيانات التطبيق -->
        <div class="info-section">
            <div class="section-title">
                <i class="fas fa-mobile-alt"></i>
                بيانات هاتف المهندس و حساب تطبيق حصر الأضرار
            </div>
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

        <!-- الحساب البنكي -->
        <div class="info-section">
            <div class="section-title">
                <i class="fas fa-university"></i>
                معلومات الحساب البنكي
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">اسم البنك</div>
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
                <div class="info-item" style="grid-column: 1 / -1;">
                    <div class="info-label">اسم صاحب الحساب</div>
                  <div class="info-value">
                        @php
                            $ownerName = array_filter([
                                $engineer->account_owner_first,
                                $engineer->account_owner_second,
                                $engineer->account_owner_third,
                                $engineer->account_owner_last,
                            ]);
                        @endphp

                        @if(count($ownerName))
                            {{ implode(' ', $ownerName) }}
                        @else
                            <span class="no-data">غير محدد</span>
                        @endif
                    </div>

                </div>
                <div class="info-item">
                    <div class="info-label">رقم هوية صاحب الحساب</div>
                    <div class="info-value">{{ $engineer->account_owner_national_id ?? 'غير محدد' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">رقم الجوال المربوط في حساب البنك</div>
                    <div class="info-value">{{ $engineer->account_owner_mobile ?? 'غير محدد' }}</div>
                </div>
            </div>
        </div>
<div class="info-section">
    <div class="section-title">
        <i class="fas fa-paperclip"></i>
        المرفقات
    </div>

    @if($engineer->attachments->count() > 0)
        <div class="info-grid">
            @foreach($engineer->attachments as $att)
                <div class="info-item" style="position: relative;">
                    <div class="info-label">
                        {{ $att->type->name ?? 'نوع غير محدد' }}
                    </div>

                    <div class="info-value" style="margin-bottom: 10px;">
                        {{ $att->file_name }}
                    </div>

                    @if($att->details)
                        <div style="font-size: 13px; color: #555; margin-bottom: 10px;">
                            تفاصيل المرفق: {{ $att->details }}
                        </div>
                    @endif

                    <div style="display: flex; gap: 10px; margin-top: 10px;">
                        <a href="{{ asset('storage/' . $att->file_path) }}" 
                           target="_blank" 
                           class="btn btn-sm"
                           style="padding: 6px 10px; background:#0C4079; color:white; border-radius:6px; text-decoration:none;">
                           <i class="fas fa-eye"></i> عرض
                        </a>

                        <a href="{{ asset('storage/' . $att->file_path) }}" 
                           download
                           class="btn btn-sm"
                           style="padding: 6px 10px; background:#444; color:white; border-radius:6px; text-decoration:none;">
                           <i class="fas fa-download"></i> تحميل
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

    @else
        <div class="no-data">لا توجد مرفقات</div>
    @endif
</div>

    </div>
</div>
@endsection