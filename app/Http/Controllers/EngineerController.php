<?php

namespace App\Http\Controllers;

use App\Models\Engineer;
use App\Models\Constant;
use App\Models\EngineerAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class EngineerController extends Controller
{
    public function index()
    {
        $engineers = Engineer::with([
            'gender',
            'maritalStatus',
            'homeGovernorate',
            'workGovernorate'
        ])->latest()->paginate(15);

        return view('engineers.index', compact('engineers'));
    }

       public function create()
    {
        $genders = Constant::childrenOfId(4)->get();
        $maritalStatuses = Constant::childrenOfId(7)->get();
        $governorates = Constant::childrenOfId(39)->get();
        $currencies = Constant::childrenOfId(49)->get();
        
        // جلب أنواع المرفقات من الثوابت - غير الرقم حسب جدول الثوابت عندك
        $attachmentTypes = Constant::childrenOfId(36)->get(); // ضع رقم الـ Parent ID لأنواع المرفقات

        return view('engineers.create', compact(
            'genders',
            'maritalStatuses',
            'governorates',
            'currencies',
            'attachmentTypes'
        ));
    }
    public function getCities($governorateId)
{
    $cities = Constant::where('parent', $governorateId)->get();

    return response()->json($cities);
}


    public function store(Request $request)
    {
       $validated = $request->validate([
    'personal_image' => 'nullable|string',
    'national_id' => 'required|digits:9|unique:engineers,national_id',
    'first_name' => 'required|string|max:255',
    'second_name' => 'required|string|max:255',
    'third_name' => 'required|string|max:255',
    'last_name' => 'required|string|max:255',
    'mobile_1' => 'required|digits:10',
    'mobile_2' => 'nullable|digits:10',
    'gender_id' => 'required|exists:constants,id',
    'marital_status_id' => 'required|exists:constants,id',
    'birth_date' => 'nullable|date',
    'home_governorate_id' => 'required|exists:constants,id',
    'home_city_id' => 'required|exists:constants,id',
    'home_address_details' => 'nullable|string',
    'work_governorate_id' => 'required|exists:constants,id',
    'work_city_id' => 'required|exists:constants,id',
    'work_address_details' => 'nullable|string',
    'experience_years' => 'required|integer|min:0',
    'specialization' => 'nullable|string|max:255',
    'salary_amount' => 'nullable|numeric|min:0',
    'salary_currency_id' => 'required|exists:constants,id',
    'work_start_date' => 'nullable|date',
    'work_end_date' => 'nullable|date|after:work_start_date',
    'app_username' => 'nullable|string|max:255|unique:engineers,app_username',
    'app_password' => 'nullable|string|min:6',
    'phone_type' => 'nullable|string|max:255',
    'phone_name' => 'nullable|string|max:255',
    'os_version' => 'nullable|string|max:255',
    'bank_name' => 'nullable|string|max:255',
    'bank_account_number' => 'nullable|string|max:255',
    'iban_number' => 'nullable|string|max:255',
    'account_owner_first' => 'nullable|string|max:255',
    'account_owner_second' => 'nullable|string|max:255',
    'account_owner_third' => 'nullable|string|max:255',
    'account_owner_last' => 'nullable|string|max:255',
    'account_owner_national_id' => 'nullable|digits:9',
    'account_owner_mobile' => 'nullable|digits:10',

    'attachments' => 'nullable|array',
    'attachments.*.type_id' => 'required|exists:constants,id',
    'attachments.*.details' => 'nullable|string',
    'attachments.*.file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',

], [

    'required' => 'حقل :attribute مطلوب.',
    'string' => 'حقل :attribute يجب أن يكون نصاً.',
    'digits' => 'حقل :attribute يجب أن يتكون من :digits أرقام.',
    'unique' => 'قيمة :attribute موجودة مسبقاً.',
    'max' => 'حقل :attribute يجب ألا يتجاوز :max حرفاً.',
    'min' => 'حقل :attribute يجب ألا يقل عن :min.',
    'date' => 'صيغة :attribute غير صحيحة.',
    'numeric' => 'حقل :attribute يجب أن يكون رقماً.',
    'exists' => 'القيمة المختارة في :attribute غير موجودة.',
    'after' => 'تاريخ :attribute يجب أن يكون بعد :date.',

    'national_id.required' => 'الرقم الهوية مطلوب.',
    'national_id.digits' => 'الرقم الوطني يجب أن يكون 9 أرقام.',
    'mobile_1.required' => 'رقم الجوال الأول مطلوب.',
    'mobile_1.digits' => 'رقم الجوال يجب أن يكون 10 أرقام.',
    'mobile_2.digits' => 'رقم الجوال الثاني يجب أن يكون 10 أرقام.',

    'account_owner_national_id.digits' => 'رقم هوية صاحب الحساب يجب أن يكون 9 أرقام.',
    'account_owner_mobile.digits' => 'رقم جوال صاحب الحساب يجب أن يكون 10 أرقام.',

    'attachments.*.type_id.required' => 'نوع المرفق مطلوب.',
    'attachments.*.file.required' => 'ملف المرفق مطلوب.',
    'attachments.*.file.mimes' => 'صيغة الملف غير مسموحة.',
    'attachments.*.file.max' => 'حجم الملف يجب ألا يتجاوز 5 ميجابايت.',
]);


        // Handle base64 image
        if ($request->filled('personal_image')) {
            $imageData = $request->personal_image;
            
            // Remove data:image/...;base64, prefix if exists
            if (strpos($imageData, 'data:image') !== false) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
            }
            
            $imageData = base64_decode($imageData);
            $imageName = 'engineer_' . time() . '_' . uniqid() . '.jpg';
            $path = 'engineers/' . $imageName;
            
            Storage::disk('public')->put($path, $imageData);
            $validated['personal_image'] = $path;
        }

        // Create engineer
        $engineer = Engineer::create($validated);

        // Handle attachments
        if ($request->has('attachments')) {
            foreach ($request->attachments as $attachment) {
                if (isset($attachment['file'])) {
                    $file = $attachment['file'];
                    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $filePath = $file->storeAs('engineer_attachments', $fileName, 'public');
                    
                    EngineerAttachment::create([
                        'engineer_id' => $engineer->id,
                        'attachment_type_id' => $attachment['type_id'],
                        'file_path' => $filePath,
                        'file_name' => $file->getClientOriginalName(),
                        'details' => $attachment['details'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('engineers.index')
            ->with('success', 'تم إضافة المهندس بنجاح');
    }



    public function show(Engineer $engineer)
    {
        $engineer->load([
            'gender',
            'maritalStatus',
            'homeGovernorate',
            'homeCity',
            'workGovernorate',
            'workCity',
            'salaryCurrency'
        ]);

        return view('engineers.show', compact('engineer'));
    }

    public function edit(Engineer $engineer)
    {
        $genders = Constant::where('type', 'gender')->where('is_active', true)->get();
        $maritalStatuses = Constant::where('type', 'marital_status')->where('is_active', true)->get();
        $governorates = Constant::where('type', 'governorate')->where('is_active', true)->get();
        $currencies = Constant::where('type', 'currency')->where('is_active', true)->get();
        
        // جلب المدن الخاصة بالمحافظات المختارة
        $homeCities = Constant::where('type', 'city')
            ->where('parent_id', $engineer->home_governorate_id)
            ->where('is_active', true)
            ->get();
            
        $workCities = Constant::where('type', 'city')
            ->where('parent_id', $engineer->work_governorate_id)
            ->where('is_active', true)
            ->get();

        return view('engineers.edit', compact(
            'engineer',
            'genders',
            'maritalStatuses',
            'governorates',
            'currencies',
            'homeCities',
            'workCities'
        ));
    }

    public function update(Request $request, Engineer $engineer)
    {
        $validated = $request->validate([
            'personal_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'national_id' => 'required|string|unique:engineers,national_id,' . $engineer->id,
            'first_name' => 'required|string|max:255',
            'second_name' => 'required|string|max:255',
            'third_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile_1' => 'required|string|max:20',
            'mobile_2' => 'nullable|string|max:20',
            'gender_id' => 'required|exists:constants,id',
            'marital_status_id' => 'required|exists:constants,id',
            'birth_date' => 'nullable|date',
            'home_governorate_id' => 'required|exists:constants,id',
            'home_city_id' => 'required|exists:constants,id',
            'home_address_details' => 'nullable|string',
            'work_governorate_id' => 'required|exists:constants,id',
            'work_city_id' => 'required|exists:constants,id',
            'work_address_details' => 'nullable|string',
            'experience_years' => 'required|integer|min:0',
            'specialization' => 'nullable|string|max:255',
            'salary_amount' => 'nullable|numeric|min:0',
            'salary_currency_id' => 'required|exists:constants,id',
            'work_start_date' => 'nullable|date',
            'work_end_date' => 'nullable|date|after:work_start_date',
            'app_username' => 'nullable|string|max:255|unique:engineers,app_username,' . $engineer->id,
            'app_password' => 'nullable|string|min:6',
            'phone_type' => 'nullable|string|max:255',
            'phone_name' => 'nullable|string|max:255',
            'os_version' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'iban_number' => 'nullable|string|max:255',
            'account_owner_first' => 'nullable|string|max:255',
            'account_owner_second' => 'nullable|string|max:255',
            'account_owner_third' => 'nullable|string|max:255',
            'account_owner_last' => 'nullable|string|max:255',
            'account_owner_national_id' => 'nullable|string|max:255',
            'account_owner_mobile' => 'nullable|string|max:20',
        ]);

        if ($request->hasFile('personal_image')) {
            if ($engineer->personal_image) {
                Storage::disk('public')->delete($engineer->personal_image);
            }
            $validated['personal_image'] = $request->file('personal_image')->store('engineers', 'public');
        }

        $engineer->update($validated);

        return redirect()->route('engineers.index')
            ->with('success', 'تم تحديث بيانات المهندس بنجاح');
    }

    public function destroy(Engineer $engineer)
    {
        // حذف الصورة الشخصية
        if ($engineer->personal_image) {
            Storage::disk('public')->delete($engineer->personal_image);
        }

        $engineer->delete();

        return redirect()->route('engineers.index')
            ->with('success', 'تم حذف المهندس بنجاح');
    }
}