@extends('layouts.app')

@section('title', 'تعديل بيانات المهندس')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<style>
    .wizard-container {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .wizard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        position: relative;
    }

    .wizard-header::before {
        content: '';
        position: absolute;
        top: 25px;
        right: 50px;
        left: 50px;
        height: 2px;
        background: #e8e8e8;
        z-index: 0;
    }

    .wizard-step {
        flex: 1;
        text-align: center;
        position: relative;
        z-index: 1;
        cursor: pointer;
        transition: all 0.3s;
    }

    .wizard-step:hover .step-circle {
        transform: scale(1.1);
    }

    .step-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #e8e8e8;
        color: #999;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 10px;
        transition: all 0.3s;
    }

    .wizard-step.active .step-circle {
        background: #0C4079;
        color: white;
    }

    .wizard-step.completed .step-circle {
        background: #10b981;
        color: white;
    }

    .step-title {
        font-size: 14px;
        font-weight: 600;
        color: #999;
        transition: all 0.3s;
    }

    .wizard-step.active .step-title {
        color: #0C4079;
    }

    .wizard-step.completed .step-title {
        color: #10b981;
    }

    .wizard-content {
        margin-top: 40px;
    }

    .step-content {
        display: none;
    }

    .step-content.active {
        display: block;
        animation: fadeIn 0.3s;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #0C4079;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e8e8e8;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .form-group label.required::after {
        content: ' *';
        color: #c62828;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e8e8e8;
        border-radius: 8px;
        outline: none;
        transition: all 0.3s;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: #0C4079;
        box-shadow: 0 0 0 3px rgba(12, 64, 121, 0.1);
    }

    .invalid-feedback {
        color: #c62828;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    .is-invalid {
        border-color: #c62828;
    }

    .wizard-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 1px solid #e8e8e8;
    }

    .btn-wizard {
        padding: 12px 30px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-prev {
        background: #f0f0f0;
        color: #666;
    }

    .btn-prev:hover {
        background: #e0e0e0;
    }

    .btn-next {
        background: #0C4079;
        color: white;
    }

    .btn-next:hover {
        background: #083058;
    }

    .btn-submit {
        background: #10b981;
        color: white;
    }

    .btn-submit:hover {
        background: #059669;
    }

    .btn-quick-save {
        position: fixed;
        bottom: 30px;
        left: 30px;
        background: #10b981;
        color: white;
        border: none;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        transition: all 0.3s;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-quick-save:hover {
        background: #059669;
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.5);
    }

    .btn-quick-save:active {
        transform: scale(0.95);
    }

    .image-upload-wrapper {
        text-align: center;
        margin-bottom: 30px;
    }

    .image-upload {
        text-align: center;
        padding: 30px;
        border: 2px dashed #e8e8e8;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .image-upload:hover {
        border-color: #0C4079;
        background: #f8f9fa;
    }

    .upload-icon {
        font-size: 48px;
        color: #0C4079;
        margin-bottom: 10px;
    }

    .image-preview-circle {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        margin: 0 auto 15px;
        object-fit: cover;
        display: none;
        border: 3px solid #0C4079;
    }

    .image-preview-circle.show {
        display: block;
    }

    .cropper-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.8);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .cropper-modal.active {
        display: flex;
    }

    .cropper-container-wrapper {
        background: white;
        padding: 20px;
        border-radius: 12px;
        max-width: 90%;
        max-height: 90%;
        overflow: auto;
    }

    .cropper-buttons {
        margin-top: 20px;
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    #cropImageContainer {
        max-width: 100%;
        max-height: 70vh;
    }

    .attachments-container {
        border: 1px solid #e8e8e8;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .attachment-item {
        background: #f8f9fa;
        border: 1px solid #e8e8e8;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        position: relative;
    }

    .attachment-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .btn-remove-attachment {
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .btn-remove-attachment:hover {
        background: #c82333;
    }

    .btn-add-attachment {
        background: #0C4079;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-add-attachment:hover {
        background: #083058;
    }

    .existing-attachment {
        background: #e8f5e9;
        border: 1px solid #4caf50;
    }

    .attachment-file-info {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: white;
        border-radius: 6px;
        margin-top: 10px;
    }

    .attachment-file-info i {
        color: #0C4079;
        font-size: 20px;
    }

    .btn-view-file {
        background: #0C4079;
        color: white;
        padding: 8px 15px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.3s;
    }

    .btn-view-file:hover {
        background: #083058;
    }

    .age-display {
        background: #f8f9fa;
        padding: 12px 15px;
        border-radius: 8px;
        font-weight: 600;
        color: #0C4079;
    }

    @media (max-width: 992px) {
        .wizard-header {
            flex-direction: column;
            gap: 20px;
            margin-bottom: 20px;
        }

        .wizard-header::before {
            display: none;
        }

        .wizard-step {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 15px;
            justify-content: flex-start;
            text-align: right;
        }

        .step-circle {
            margin-bottom: 0;
        }

        .wizard-container {
            padding: 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .wizard-actions {
            flex-direction: column-reverse;
            gap: 15px;
        }

        .btn-wizard {
            width: 100%;
            justify-content: center;
        }

        .btn-quick-save {
            bottom: 20px;
            left: 20px;
            width: 50px;
            height: 50px;
            font-size: 20px;
        }
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
        backdrop: false,
        customClass: {
            popup: 'medium-small-toast'
        }
    });
});
</script>
@endif

<div class="wizard-container">
    <div class="wizard-header">
        <div class="wizard-step active" data-step="1" onclick="goToStep(1)">
            <div class="step-circle">1</div>
            <div class="step-title">البيانات الشخصية</div>
        </div>
        <div class="wizard-step" data-step="2" onclick="goToStep(2)">
            <div class="step-circle">2</div>
            <div class="step-title">بيانات السكن والعمل</div>
        </div>
        <div class="wizard-step" data-step="3" onclick="goToStep(3)">
            <div class="step-circle">3</div>
            <div class="step-title">بيانات الوظيفة</div>
        </div>
        <div class="wizard-step" data-step="4" onclick="goToStep(4)">
            <div class="step-circle">4</div>
            <div class="step-title">بيانات التطبيق</div>
        </div>
        <div class="wizard-step" data-step="5" onclick="goToStep(5)">
            <div class="step-circle">5</div>
            <div class="step-title">الحساب البنكي</div>
        </div>
        <div class="wizard-step" data-step="6" onclick="goToStep(6)">
            <div class="step-circle">6</div>
            <div class="step-title">المرفقات</div>
        </div>
    </div>

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

    <form action="{{ route('engineers.update', $engineer) }}" method="POST" enctype="multipart/form-data" id="wizardForm">
        @csrf
        @method('PUT')

        <div class="wizard-content">
            <!-- Step 1: Personal Data -->
            <div class="step-content active" data-step="1">
                <h3 class="form-section-title">البيانات الشخصية الأساسية</h3>

                <div class="image-upload-wrapper">
                    @if($engineer->personal_image)
                        <img id="imagePreviewCircle" class="image-preview-circle show" src="{{ asset('storage/' . $engineer->personal_image) }}" alt="Preview">
                    @else
                        <img id="imagePreviewCircle" class="image-preview-circle" src="#" alt="Preview">
                    @endif
                    
                    <label class="image-upload" for="personal_image_input">
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div>انقر لتغيير الصورة الشخصية</div>
                        <small style="color: #999;">JPG, PNG (الحد الأقصى: 2MB)</small>
                    </label>
                    <input type="file" id="personal_image_input" accept="image/*" style="display: none;">
                    <input type="hidden" name="personal_image" id="personal_image_data">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="required">رقم الهوية</label>
                        <input type="text" name="national_id" class="form-control @error('national_id') is-invalid @enderror" value="{{ old('national_id', $engineer->national_id) }}" required>
                        @error('national_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="required">الاسم الأول</label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $engineer->first_name) }}" required>
                        @error('first_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="required">اسم الأب</label>
                        <input type="text" name="second_name" class="form-control @error('second_name') is-invalid @enderror" value="{{ old('second_name', $engineer->second_name) }}" required>
                        @error('second_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="required">اسم الجد</label>
                        <input type="text" name="third_name" class="form-control @error('third_name') is-invalid @enderror" value="{{ old('third_name', $engineer->third_name) }}" required>
                        @error('third_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="required">اسم العائلة</label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $engineer->last_name) }}" required>
                        @error('last_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="required">رقم الجوال الأساسي</label>
                        <input type="text" name="mobile_1" class="form-control @error('mobile_1') is-invalid @enderror" value="{{ old('mobile_1', $engineer->mobile_1) }}" required>
                        @error('mobile_1')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>رقم الجوال الفرعي</label>
                        <input type="text" name="mobile_2" class="form-control @error('mobile_2') is-invalid @enderror" value="{{ old('mobile_2', $engineer->mobile_2) }}">
                        @error('mobile_2')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="required">الجنس</label>
                        <select name="gender_id" class="form-control @error('gender_id') is-invalid @enderror" required>
                            <option value="">اختر الجنس</option>
                            @foreach($genders as $gender)
                                <option value="{{ $gender->id }}" {{ old('gender_id', $engineer->gender_id) == $gender->id ? 'selected' : '' }}>
                                    {{ $gender->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('gender_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="required">الحالة الاجتماعية</label>
                        <select name="marital_status_id" class="form-control @error('marital_status_id') is-invalid @enderror" required>
                            <option value="">اختر الحالة</option>
                            @foreach($maritalStatuses as $status)
                                <option value="{{ $status->id }}" {{ old('marital_status_id', $engineer->marital_status_id) == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('marital_status_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>تاريخ الميلاد</label>
                        <input type="date" 
                            name="birth_date" 
                            id="birth_date"
                            class="form-control @error('birth_date') is-invalid @enderror"
                            value="{{ old('birth_date', $engineer->birth_date ? $engineer->birth_date->format('Y-m-d') : '') }}">
                        @error('birth_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>العمر</label>
                        <div class="age-display" id="ageDisplay">
                            @if($engineer->birth_date)
                                {{ \Carbon\Carbon::parse($engineer->birth_date)->age }} سنة
                            @else
                                قم بإدخال تاريخ الميلاد
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Address -->
            <div class="step-content" data-step="2">
                <h3 class="form-section-title">عنوان السكن</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label class="required">المحافظة</label>
                        <select name="home_governorate_id" id="home_governorate_id" class="form-control @error('home_governorate_id') is-invalid @enderror" required>
                            <option value="">اختر المحافظة</option>
                            @foreach($governorates as $gov)
                                <option value="{{ $gov->id }}" {{ old('home_governorate_id', $engineer->home_governorate_id) == $gov->id ? 'selected' : '' }}>
                                    {{ $gov->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('home_governorate_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="required">المدينة</label>
                        <select name="home_city_id" id="home_city_id" class="form-control @error('home_city_id') is-invalid @enderror" required>
                            <option value="">اختر المدينة</option>
                            @foreach($homeCities as $city)
                                <option value="{{ $city->id }}" {{ old('home_city_id', $engineer->home_city_id) == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('home_city_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>العنوان بالتفصيل</label>
                    <textarea name="home_address_details" class="form-control @error('home_address_details') is-invalid @enderror" rows="3">{{ old('home_address_details', $engineer->home_address_details) }}</textarea>
                    @error('home_address_details')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <h3 class="form-section-title" style="margin-top: 40px;">مكان العمل</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label class="required">المحافظة</label>
                        <select name="work_governorate_id" id="work_governorate_id" class="form-control @error('work_governorate_id') is-invalid @enderror" required>
                            <option value="">اختر المحافظة</option>
                            @foreach($governorates as $gov)
                                <option value="{{ $gov->id }}" {{ old('work_governorate_id', $engineer->work_governorate_id) == $gov->id ? 'selected' : '' }}>
                                    {{ $gov->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('work_governorate_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="required">المدينة</label>
                        <select name="work_city_id" id="work_city_id" class="form-control @error('work_city_id') is-invalid @enderror" required>
                            <option value="">اختر المدينة</option>
                            @foreach($workCities as $city)
                                <option value="{{ $city->id }}" {{ old('work_city_id', $engineer->work_city_id) == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('work_city_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>مكان العمل بالتفصيل</label>
                    <textarea name="work_address_details" class="form-control @error('work_address_details') is-invalid @enderror" rows="3">{{ old('work_address_details', $engineer->work_address_details) }}</textarea>
                    @error('work_address_details')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>كود منطقة العمل</label>
                    <select name="main_work_area_code" id="main_work_area_code"
                            class="form-control @error('main_work_area_code') is-invalid @enderror">
                        <option value="">اختر كود المنطقة</option>
                    </select>
                    @error('main_work_area_code')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <!-- Step 3: Job Info -->
            <div class="step-content" data-step="3">
                <h3 class="form-section-title">معلومات الوظيفة</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label class="required">سنوات الخبرة</label>
                        <input type="number" name="experience_years" class="form-control @error('experience_years') is-invalid @enderror" value="{{ old('experience_years', $engineer->experience_years) }}" min="0" required>
                        @error('experience_years')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>التخصص</label>
                        <select name="specialization_id" class="form-control @error('specialization_id') is-invalid @enderror">
                            <option value="">اختر التخصص</option>
                            @foreach($specializations as $sp)
                                <option value="{{ $sp->id }}" 
                                    {{ old('specialization_id', $engineer->specialization_id) == $sp->id ? 'selected' : '' }}>
                                    {{ $sp->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('specialization_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>قيمة الراتب</label>
                        <input type="number" step="0.01" name="salary_amount" class="form-control @error('salary_amount') is-invalid @enderror" value="{{ old('salary_amount', $engineer->salary_amount) }}" min="0">
                        @error('salary_amount')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="required">العملة</label>
                        <select name="salary_currency_id" class="form-control @error('salary_currency_id') is-invalid @enderror" required>
                            <option value="">اختر العملة</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}" {{ old('salary_currency_id', $engineer->salary_currency_id) == $currency->id ? 'selected' : '' }}>
                                    {{ $currency->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('salary_currency_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>تاريخ بدء العمل</label>
                        <input type="date"
                            name="work_start_date"
                            class="form-control @error('work_start_date') is-invalid @enderror"
                            value="{{ old('work_start_date', optional($engineer->work_start_date)->format('Y-m-d')) }}">
                        @error('work_start_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>تاريخ نهاية العمل</label>
                        <input type="date"
                            name="work_end_date"
                            class="form-control @error('work_end_date') is-invalid @enderror"
                            value="{{ old('work_end_date', optional($engineer->work_end_date)->format('Y-m-d')) }}">
                        @error('work_end_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step 4: App Data -->
            <div class="step-content" data-step="4">
                <h3 class="form-section-title">بيانات تطبيق حصر الأضرار</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label>اسم المستخدم</label>
                        <input type="text" name="app_username" class="form-control @error('app_username') is-invalid @enderror" value="{{ old('app_username', $engineer->app_username) }}">
                        @error('app_username')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>كلمة المرور</label>
                        <input type="text" name="app_password" class="form-control @error('app_password') is-invalid @enderror" value="{{ old('app_password', $engineer->app_password) }}" placeholder="اتركه فارغاً إذا لم ترغب بالتغيير">
                        @error('app_password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <h3 class="form-section-title" style="margin-top: 40px;">معلومات الهاتف</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label>نوع الهاتف</label>
                        <input type="text" name="phone_type" class="form-control @error('phone_type') is-invalid @enderror" value="{{ old('phone_type', $engineer->phone_type) }}" placeholder="مثال: Android, iOS">
                        @error('phone_type')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>اسم الهاتف</label>
                        <input type="text" name="phone_name" class="form-control @error('phone_name') is-invalid @enderror" value="{{ old('phone_name', $engineer->phone_name) }}" placeholder="مثال: Samsung Galaxy">
                        @error('phone_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>إصدار نظام التشغيل</label>
                        <input type="text" name="os_version" class="form-control @error('os_version') is-invalid @enderror" value="{{ old('os_version', $engineer->os_version) }}" placeholder="مثال: Android 12">
                        @error('os_version')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step 5: Bank Info -->
            <div class="step-content" data-step="5">
                <h3 class="form-section-title">معلومات الحساب البنكي</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label>اسم البنك</label>
                        <input type="text" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name', $engineer->bank_name) }}">
                        @error('bank_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>رقم الحساب</label>
                        <input type="text" name="bank_account_number" class="form-control @error('bank_account_number') is-invalid @enderror" value="{{ old('bank_account_number', $engineer->bank_account_number) }}">
                        @error('bank_account_number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>رقم الآيبان</label>
                        <input type="text" name="iban_number" class="form-control @error('iban_number') is-invalid @enderror" value="{{ old('iban_number', $engineer->iban_number) }}">
                        @error('iban_number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <h3 class="form-section-title" style="margin-top: 40px;">معلومات صاحب الحساب</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label>الاسم الأول</label>
                        <input type="text" name="account_owner_first" class="form-control @error('account_owner_first') is-invalid @enderror" value="{{ old('account_owner_first', $engineer->account_owner_first) }}">
                        @error('account_owner_first')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>اسم الأب</label>
                        <input type="text" name="account_owner_second" class="form-control @error('account_owner_second') is-invalid @enderror" value="{{ old('account_owner_second', $engineer->account_owner_second) }}">
                        @error('account_owner_second')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>اسم الجد</label>
                        <input type="text" name="account_owner_third" class="form-control @error('account_owner_third') is-invalid @enderror" value="{{ old('account_owner_third', $engineer->account_owner_third) }}">
                        @error('account_owner_third')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>اسم العائلة</label>
                        <input type="text" name="account_owner_last" class="form-control @error('account_owner_last') is-invalid @enderror" value="{{ old('account_owner_last', $engineer->account_owner_last) }}">
                        @error('account_owner_last')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>رقم هوية صاحب الحساب</label>
                        <input type="text" name="account_owner_national_id" class="form-control @error('account_owner_national_id') is-invalid @enderror" value="{{ old('account_owner_national_id', $engineer->account_owner_national_id) }}">
                        @error('account_owner_national_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>رقم الجوال المربوط بالحساب</label>
                        <input type="text" name="account_owner_mobile" class="form-control @error('account_owner_mobile') is-invalid @enderror" value="{{ old('account_owner_mobile', $engineer->account_owner_mobile) }}">
                        @error('account_owner_mobile')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step 6: Attachments -->
            <div class="step-content" data-step="6">
                <h3 class="form-section-title">المرفقات</h3>

                <!-- Existing Attachments -->
                @if($engineer->attachments && $engineer->attachments->count() > 0)
                <div class="attachments-container">
                    <h4 style="color: #0C4079; margin-bottom: 15px;">المرفقات الموجودة</h4>
                    @foreach($engineer->attachments as $index => $attachment)
                    <div class="attachment-item existing-attachment">
                        <div class="attachment-item-header">
                            <h4 style="margin: 0; color: #2e7d32;">
                                <i class="fas fa-paperclip"></i>
                                {{ $attachment->attachmentType->name ?? 'مرفق' }}
                            </h4>
                        </div>
                        
                        <div class="attachment-file-info">
                            <i class="fas fa-file"></i>
                            <div style="flex: 1;">
                                <strong>{{ $attachment->file_name }}</strong>
                                @if($attachment->details)
                                <div style="font-size: 13px; color: #666; margin-top: 5px;">
                                    {{ $attachment->details }}
                                </div>
                                @endif
                            </div>
                            <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="btn-view-file">
                                <i class="fas fa-eye"></i> عرض
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- New Attachments -->
                <h4 style="color: #0C4079; margin: 30px 0 15px;">إضافة مرفقات جديدة</h4>
                <div class="attachments-container" id="attachmentsContainer">
                    <!-- New attachments will be added here -->
                </div>

                <button type="button" class="btn-add-attachment" id="addAttachmentBtn">
                    <i class="fas fa-plus"></i>
                    إضافة مرفق جديد
                </button>
            </div>
        </div>

        <div class="wizard-actions">
            <button type="button" class="btn-wizard btn-prev" id="prevBtn" style="display: none;">
                <i class="fas fa-arrow-right"></i>
                السابق
            </button>
            <div></div>
            <button type="button" class="btn-wizard btn-next" id="nextBtn">
                التالي
                <i class="fas fa-arrow-left"></i>
            </button>
            <button type="submit" class="btn-wizard btn-submit" id="submitBtn" style="display: none;">
                <i class="fas fa-check"></i>
                حفظ التعديلات
            </button>
        </div>
    </form>
</div>

<!-- Quick Save Button -->
<button type="button" class="btn-quick-save" id="quickSaveBtn" title="حفظ سريع">
    <i class="fas fa-save"></i>
</button>

<!-- Image Cropper Modal -->
<div class="cropper-modal" id="cropperModal">
    <div class="cropper-container-wrapper">
        <h3 style="margin-bottom: 20px; text-align: center;">قص الصورة</h3>
        <div style="max-width: 600px;">
            <img id="cropImageContainer" src="" alt="Crop">
        </div>
        <div class="cropper-buttons">
            <button type="button" class="btn-wizard btn-next" id="cropImageBtn">
                <i class="fas fa-check"></i>
                تأكيد
            </button>
            <button type="button" class="btn-wizard btn-prev" id="cancelCropBtn">
                <i class="fas fa-times"></i>
                إلغاء
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
let currentStep = 1;
const totalSteps = 6;
let cropper = null;
let attachmentIndex = 0;

// Image Upload and Cropper
document.getElementById('personal_image_input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        if (file.size > 2048000) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'حجم الصورة يجب أن يكون أقل من 2MB',
                confirmButtonText: 'حسناً',
                confirmButtonColor: '#0C4079'
            });
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const modal = document.getElementById('cropperModal');
            const image = document.getElementById('cropImageContainer');
            
            image.src = e.target.result;
            modal.classList.add('active');
            
            if (cropper) {
                cropper.destroy();
            }
            
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 2,
                dragMode: 'move',
                autoCropArea: 1,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('cropImageBtn').addEventListener('click', function() {
    if (cropper) {
        const canvas = cropper.getCroppedCanvas({
            width: 500,
            height: 500,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });
        
        canvas.toBlob(function(blob) {
            const reader = new FileReader();
            reader.onloadend = function() {
                const base64data = reader.result;
                document.getElementById('personal_image_data').value = base64data;
                document.getElementById('imagePreviewCircle').src = base64data;
                document.getElementById('imagePreviewCircle').classList.add('show');
                
                document.getElementById('cropperModal').classList.remove('active');
                cropper.destroy();
                cropper = null;
            };
            reader.readAsDataURL(blob);
        }, 'image/jpeg', 0.9);
    }
});

document.getElementById('cancelCropBtn').addEventListener('click', function() {
    document.getElementById('cropperModal').classList.remove('active');
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    document.getElementById('personal_image_input').value = '';
});

// Age Calculator
document.getElementById('birth_date').addEventListener('change', function() {
    const birthDate = new Date(this.value);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    
    document.getElementById('ageDisplay').textContent = age + ' سنة';
});

// Load cities on page load
window.addEventListener('load', function() {
    // Calculate age if birth date exists
    const birthDateInput = document.getElementById('birth_date');
    if (birthDateInput.value) {
        birthDateInput.dispatchEvent(new Event('change'));
    }
});

// Home Governorate Change
document.getElementById('home_governorate_id').addEventListener('change', function() {
    const governorateId = this.value;
    const citySelect = document.getElementById('home_city_id');
    
    citySelect.innerHTML = '<option value="">جاري التحميل...</option>';
    
    if (governorateId) {
        fetch(`/engineers/cities/${governorateId}`)
            .then(response => response.json())
            .then(data => {
                citySelect.innerHTML = '<option value="">اختر المدينة</option>';
                data.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    citySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                citySelect.innerHTML = '<option value="">خطأ في تحميل المدن</option>';
            });
    } else {
        citySelect.innerHTML = '<option value="">اختر المحافظة أولاً</option>';
    }
});

// Work Governorate Change
document.getElementById('work_governorate_id').addEventListener('change', function() {
    const governorateId = this.value;
    const citySelect = document.getElementById('work_city_id');
    
    citySelect.innerHTML = '<option value="">جاري التحميل...</option>';
    
    if (governorateId) {
        fetch(`/engineers/cities/${governorateId}`)
            .then(response => response.json())
            .then(data => {
                citySelect.innerHTML = '<option value="">اختر المدينة</option>';
                data.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    citySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                citySelect.innerHTML = '<option value="">خطأ في تحميل المدن</option>';
            });
    } else {
        citySelect.innerHTML = '<option value="">اختر المحافظة أولاً</option>';
    }
});

// Attachments Management
document.getElementById('addAttachmentBtn').addEventListener('click', function() {
    addAttachmentItem();
});

function addAttachmentItem() {
    attachmentIndex++;
    const container = document.getElementById('attachmentsContainer');
    
    const attachmentHTML = `
        <div class="attachment-item" data-index="${attachmentIndex}">
            <div class="attachment-item-header">
                <h4 style="margin: 0; color: #0C4079;">مرفق جديد ${attachmentIndex}</h4>
                <button type="button" class="btn-remove-attachment" onclick="removeAttachment(${attachmentIndex})">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="required">نوع المرفق</label>
                    <select name="new_attachments[${attachmentIndex}][type_id]" class="form-control" required>
                        <option value="">اختر نوع المرفق</option>
                        @foreach(\App\Models\Constant::childrenOfId(9)->get() as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>تفاصيل المرفق</label>
                <textarea name="new_attachments[${attachmentIndex}][details]" class="form-control" rows="3" placeholder="أدخل تفاصيل إضافية عن المرفق"></textarea>
            </div>
            
            <div class="form-group">
                <label class="required">رفع الملف</label>
                <input type="file" name="new_attachments[${attachmentIndex}][file]" class="form-control" required>
                <small style="color: #999;">الصيغ المدعومة: PDF, JPG, PNG, DOC, DOCX (الحد الأقصى: 5MB)</small>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', attachmentHTML);
}

function removeAttachment(index) {
    const item = document.querySelector(`.attachment-item[data-index="${index}"]`);
    if (item) {
        Swal.fire({
            icon: 'question',
            title: 'تأكيد الحذف',
            text: 'هل أنت متأكد من حذف هذا المرفق؟',
            showCancelButton: true,
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                item.remove();
            }
        });
    }
}

// Wizard Navigation
function goToStep(step) {
    currentStep = step;
    showStep(currentStep);
}

function showStep(step) {
    document.querySelectorAll('.step-content').forEach(content => {
        content.classList.remove('active');
    });
    
    document.querySelector(`.step-content[data-step="${step}"]`).classList.add('active');
    
    document.querySelectorAll('.wizard-step').forEach((stepEl, index) => {
        stepEl.classList.remove('active', 'completed');
        if (index + 1 < step) {
            stepEl.classList.add('completed');
        } else if (index + 1 === step) {
            stepEl.classList.add('active');
        }
    });
    
    document.getElementById('prevBtn').style.display = step === 1 ? 'none' : 'inline-flex';
    document.getElementById('nextBtn').style.display = step === totalSteps ? 'none' : 'inline-flex';
    document.getElementById('submitBtn').style.display = step === totalSteps ? 'inline-flex' : 'none';
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

document.getElementById('nextBtn').addEventListener('click', function() {
    if (validateStep(currentStep)) {
        currentStep++;
        showStep(currentStep);
    }
});

document.getElementById('prevBtn').addEventListener('click', function() {
    currentStep--;
    showStep(currentStep);
});

function validateStep(step) {
    const currentStepElement = document.querySelector(`.step-content[data-step="${step}"]`);
    const requiredInputs = currentStepElement.querySelectorAll('[required]');
    let isValid = true;
    
    requiredInputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
            
            if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('invalid-feedback')) {
                const errorMsg = document.createElement('span');
                errorMsg.classList.add('invalid-feedback');
                errorMsg.textContent = 'هذا الحقل مطلوب';
                input.parentNode.appendChild(errorMsg);
            }
        } else {
            input.classList.remove('is-invalid');
            const errorMsg = input.nextElementSibling;

            if (errorMsg instanceof HTMLElement &&
                errorMsg.classList.contains('invalid-feedback')) {
                errorMsg.remove();
            }
                    }
                });

    
    if (!isValid) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: 'يرجى ملء جميع الحقول المطلوبة',
            confirmButtonText: 'حسناً',
            confirmButtonColor: '#0C4079'
        });
    }
    
    return isValid;
}

document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('input', function() {
        this.classList.remove('is-invalid');
        const errorMsg = this.nextElementSibling;
if (errorMsg && errorMsg.classList.contains('invalid-feedback')) {
    errorMsg.remove();
}
    });
});

document.getElementById('wizardForm').addEventListener('submit', function(e) {
    if (!validateStep(currentStep)) {
        e.preventDefault();
    }
});

// Quick Save Button
document.getElementById('quickSaveBtn').addEventListener('click', function() {
    const form = document.getElementById('wizardForm');
    
    Swal.fire({
        icon: 'question',
        title: 'حفظ التعديلات',
        text: 'هل تريد حفظ التعديلات الحالية؟',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-save"></i> نعم، احفظ',
        cancelButtonText: 'إلغاء',
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'جاري الحفظ...',
                text: 'يرجى الانتظار',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            form.submit();
        }
    });
});
</script>
<script>
function loadWorkAreas(governorateId, selectedId = null) {
    let areaSelect = document.getElementById('main_work_area_code');

    areaSelect.innerHTML = '<option value="">تحميل...</option>';

    fetch('/get-work-areas/' + governorateId)
        .then(res => res.json())
        .then(data => {
            areaSelect.innerHTML = '<option value="">اختر كود المنطقة</option>';

            data.forEach(function(area) {
                areaSelect.innerHTML += `
                    <option value="${area.id}" ${selectedId == area.id ? 'selected' : ''}>
                        ${area.name}
                    </option>`;
            });
        });
}

document.getElementById('work_governorate_id').addEventListener('change', function () {
    loadWorkAreas(this.value);
});

window.onload = function() {
    let govId = document.getElementById('work_governorate_id').value;
    let selectedArea = "{{ $selectedWorkArea }}";

    if (govId) {
        loadWorkAreas(govId, selectedArea);
    }
}
</script>

@endpush