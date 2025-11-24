<?php

namespace App\Http\Controllers;

use App\Models\Engineer;
use App\Models\Constant;
use App\Models\EngineerAttachment;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class EngineerController extends Controller
{

    private function authorizeEngineer(Engineer $engineer)
{
    $user = auth()->user();

    if ($user->role->name === 'governorate_manager' &&
        $engineer->home_governorate_id != $user->governorate_id) 
    {
        abort(403, 'غير مصرح لك بالوصول إلى هذا المهندس.');
    }
}


public function index()
{
    $user = auth()->user();

    $query = Engineer::with([
        'gender',
        'maritalStatus',
        'homeGovernorate',
        'workGovernorate'
    ]);

    switch ($user->role->name) {

        // مدير المحافظة → يشوف المهندسين اللي نفس محافظته فقط
        case 'governorate_manager':
            $query->where('work_governorate_id', $user->governorate_id);
            break;

        // مشرف الحصر → يشوف فقط المهندسين اللي لهم نفس كود منطقة العمل
        case 'survey_supervisor':
            $query->where('main_work_area_code', $user->main_work_area_code);
            break;

        // أدمن النظام → يشوف الكل
        case 'system_admin':
        default:
            break;
    }

    $engineers = $query->latest()->paginate(15);

    return view('engineers.index', compact('engineers'));
}


       public function create()
    {
        $genders = Constant::childrenOfId(1)->get();
        $maritalStatuses = Constant::childrenOfId(4)->get();
        $governorates = Constant::childrenOfId(14)->get();
        $currencies = Constant::childrenOfId(36)->get();
        $specializations = Constant::childrenOfId(46)->get();
        $mainWorkAreaCode = Constant::childrenOfId(55)->get();

        
        $attachmentTypes = Constant::childrenOfId(9)->get();

        return view('engineers.create', compact(
            'genders',
            'maritalStatuses',
            'governorates',
            'currencies',
            'attachmentTypes',
            'specializations',
            'mainWorkAreaCode'
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
    'specialization_id' => 'nullable|exists:constants,id',
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

    'main_work_area_code' => 'nullable|exists:constants,id',
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


        if ($request->filled('personal_image')) {
            $imageData = $request->personal_image;
            
            if (strpos($imageData, 'data:image') !== false) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
            }
            
            $imageData = base64_decode($imageData);
            $imageName = 'engineer_' . time() . '_' . uniqid() . '.jpg';
            $path = 'engineers/' . $imageName;
            
            Storage::disk('public')->put($path, $imageData);
            $validated['personal_image'] = $path;
        }

        $engineer = Engineer::create($validated);

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
    $this->authorizeEngineer($engineer);

    $engineer->load([
        'gender',
        'maritalStatus',
        'homeGovernorate',
        'homeCity',
        'workGovernorate',
        'workCity',
        'salaryCurrency'
    ]);

    $problemTypes = Constant::childrenOfId(41)->get();

    return view('engineers.show', compact('engineer', 'problemTypes'));
}

public function edit(Engineer $engineer)
{
    $this->authorizeEngineer($engineer);

    $genders          = Constant::childrenOfId(1)->get();
    $maritalStatuses  = Constant::childrenOfId(4)->get();
    $governorates     = Constant::childrenOfId(14)->get();
    $currencies       = Constant::childrenOfId(36)->get();
    $specializations  = Constant::childrenOfId(46)->get();
    $mainWorkAreaCode = Constant::childrenOfId(55)->get();

    $homeCities = Constant::childrenOfId($engineer->home_governorate_id)->get();
    $workCities = Constant::childrenOfId($engineer->work_governorate_id)->get();

    $attachmentTypes = Constant::childrenOfId(9)->get();

    return view('engineers.edit', compact(
        'engineer',
        'genders',
        'maritalStatuses',
        'governorates',
        'currencies',
        'homeCities',
        'workCities',
        'attachmentTypes',
        'specializations',
        'mainWorkAreaCode'
    ));
}


public function update(Request $request, Engineer $engineer)
{
        $this->authorizeEngineer($engineer);

    $validated = $request->validate([
        'personal_image' => 'nullable|string',
        'national_id' => 'required|digits:9|unique:engineers,national_id,' . $engineer->id,
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
        'specialization_id' => 'nullable|exists:constants,id',
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
        'account_owner_national_id' => 'nullable|digits:9',
        'account_owner_mobile' => 'nullable|digits:10',

        'new_attachments' => 'nullable|array',
        'new_attachments.*.type_id' => 'required|exists:constants,id',
        'new_attachments.*.details' => 'nullable|string',
        'new_attachments.*.file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',   

        'main_work_area_code' => 'nullable|exists:constants,id',

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

        'new_attachments.*.type_id.required' => 'نوع المرفق مطلوب.',
        'new_attachments.*.file.required' => 'ملف المرفق مطلوب.',
        'new_attachments.*.file.mimes' => 'صيغة الملف غير مسموحة.',
        'new_attachments.*.file.max' => 'حجم الملف يجب ألا يتجاوز 5 ميجابايت.',
    ]);

    // Handle personal image update
// Handle personal image update
if ($request->filled('personal_image')) {

    $imageData = $request->personal_image;

    if (strpos($imageData, 'data:image') !== false) {
        $imageData = substr($imageData, strpos($imageData, ',') + 1);
    }

    $imageData = base64_decode($imageData);
    $imageName = 'engineer_' . time() . '_' . uniqid() . '.jpg';
    $path = 'engineers/' . $imageName;

    // Delete old image if exists
    if ($engineer->personal_image) {
        Storage::disk('public')->delete($engineer->personal_image);
    }

    Storage::disk('public')->put($path, $imageData);
    $validated['personal_image'] = $path;

} else {
    // مهم جداً — لا تحدث الصورة لو ما دخل صورة جديدة
    unset($validated['personal_image']);
}

// Don't update password if empty
if (empty($request->app_password)) {
    unset($validated['app_password']);
}

$engineer->update($validated);


    // Handle new attachments
    if ($request->has('new_attachments')) {
        foreach ($request->new_attachments as $attachment) {
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

return redirect()->back()->with('success', 'تم تحديث بيانات المهندس بنجاح');
}

public function deleteAttachment(EngineerAttachment $attachment)
{
    $engineer = $attachment->engineer;

    $this->authorizeEngineer($engineer);

    if ($attachment->file_path) {
        Storage::disk('public')->delete($attachment->file_path);
    }

    $attachment->delete();

    return response()->json([
        'success' => true,
        'message' => 'تم حذف المرفق بنجاح'
    ]);
}

public function destroy(Engineer $engineer)
{
    $this->authorizeEngineer($engineer);

    if ($engineer->personal_image) {
        Storage::disk('public')->delete($engineer->personal_image);
    }

    $engineer->delete();

    return redirect()->route('engineers.index')
        ->with('success', 'تم حذف المهندس بنجاح');
}

public function createEngineerAccount($engineerId)
{
    $engineer = Engineer::findOrFail($engineerId);

    $username = $engineer->national_id;

    $password = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

User::create([
    'username' => $engineer->national_id,
    'password' => bcrypt($password),
    'name' => $engineer->full_name,
    'phone' => $engineer->mobile_1,
        'role_id' => Role::where('name', 'field_engineer')->first()->id,
    'engineer_id' => $engineer->id,
]);



    $message = "مرحبا {$engineer->name}، تم إنشاء حسابك.\n";
    $message .= "اسم المستخدم: {$username}\n";
    $message .= "كلمة المرور: {$password}\n";
    $message .= "الدخول عبر الرابط: https://aiocp.infinet.ps";


app(\App\Services\EngineerSmsService::class)
    ->send($engineer->mobile_1, $message, $engineer->id, null);

    return back()->with('success', 'تم إنشاء الحساب وإرسال البيانات عبر SMS');
}

public function myProfile()
{
    $user = auth()->user();

    if (!$user->engineer_id) {
        abort(403, "لا يوجد بيانات مهندس مرتبطة بهذا الحساب");
    }

    $engineer = Engineer::with([
        'gender',
        'maritalStatus',
        'homeGovernorate',
        'homeCity',
        'workGovernorate',
        'workCity',
        'specialization'
    ])->findOrFail($user->engineer_id);

    return view('engineers.profile', compact('engineer'));
}


}