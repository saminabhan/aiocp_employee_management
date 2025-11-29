<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserOtp;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SmsPasswordResetController extends Controller
{
    protected $codeValidityMinutes = 5;

    public function sendCodeAjax(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'phone' => ['required', 'regex:/^05[0-9]{8}$/'],
        ], [
            'phone.regex' => 'رقم الجوال يجب أن يبدأ بـ 05 ويحتوي على 10 أرقام.',
        ]);

        $user = User::where('username', $request->username)
                    ->where('phone', $request->phone)
                    ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'اسم المستخدم ورقم الجوال غير متطابقين أو غير مسجلين.'
            ]);
        }

        $lastOtp = UserOtp::where('user_id', $user->id)
                           ->latest()
                           ->first();

        if ($lastOtp && $lastOtp->created_at->diffInSeconds(now()) < 60) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى الانتظار قبل طلب رمز جديد.',
                'wait' => true
            ]);
        }

        $code = random_int(100000, 999999);

        UserOtp::create([
            'user_id' => $user->id,
            'otp_code' => $code,
            'expires_at' => now()->addMinutes($this->codeValidityMinutes)
        ]);

        // تعديل رقم الهاتف
        $mobile = $request->phone;
        if (substr($mobile, 0, 1) === '0') {
            $mobile = '972' . substr($mobile, 1);
        }

        $firstName = explode(' ', $user->name ?? $user->username)[0];
        $message = "عزيزي {$firstName} , رمز التحقق الخاص بك هو: {$code}.";

        Http::get('http://hotsms.ps/sendbulksms.php', [
            'api_token' => '66ef464c07d8f',
            'sender' => 'SAMI NET',
            'mobile' => $mobile,
            'type' => 0,
            'text' => $message,
        ]);

        Log::info('تم إرسال رمز SMS (DB)', [
            'user' => $user->id,
            'mobile' => $mobile,
            'code' => $code,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال رمز التحقق إلى جوالك.'
        ]);
    }

    public function verifyCodeAjax(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
            'username' => 'required|string',
            'phone' => ['required', 'regex:/^05[0-9]{8}$/']
        ]);

        $user = User::where('username', $request->username)
                    ->where('phone', $request->phone)
                    ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير موجود.'
            ]);
        }

        $otp = UserOtp::where('user_id', $user->id)
                      ->where('otp_code', $request->code)
                      ->where('used', false)
                      ->where('expires_at', '>', now())
                      ->latest()
                      ->first();

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'رمز التحقق غير صحيح أو منتهي.'
            ]);
        }

        $otp->update(['used' => true]);

        return response()->json([
            'success' => true,
            'message' => 'تم التحقق بنجاح.'
        ]);
    }

    public function resendCodeAjax(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'phone' => ['required', 'regex:/^05[0-9]{8}$/']
        ]);

        $user = User::where('username', $request->username)
                    ->where('phone', $request->phone)
                    ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير موجود.'
            ]);
        }

        $lastOtp = UserOtp::where('user_id', $user->id)->latest()->first();
        if ($lastOtp && $lastOtp->created_at->diffInSeconds(now()) < 60) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى الانتظار قبل طلب رمز جديد.',
                'wait' => true
            ]);
        }

        $code = random_int(100000, 999999);

        UserOtp::create([
            'user_id' => $user->id,
            'otp_code' => $code,
            'expires_at' => now()->addMinutes($this->codeValidityMinutes)
        ]);

        $mobile = $request->phone;
        if (substr($mobile, 0, 1) === '0') {
            $mobile = '972' . substr($mobile, 1);
        }

        $name = explode(' ', $user->name ?? $user->username);
        $firstName = $name[0];
        $lastName = end($name);

        $message = "عزيزي {$firstName} {$lastName}, رمز التحقق الخاص بك هو: {$code}.";

        Http::get('http://hotsms.ps/sendbulksms.php', [
            'api_token' => '68643d711e1f4',
            'sender' => 'Solar Bills',
            'mobile' => $mobile,
            'type' => 0,
            'text' => $message,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال رمز تحقق جديد إلى جوالك.'
        ]);
    }

    public function resetPasswordAjax(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'phone' => ['required', 'regex:/^05[0-9]{8}$/'],
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
        ]);

        $user = User::where('username', $request->username)
                    ->where('phone', $request->phone)
                    ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير موجود.'
            ]);
        }

        $otpVerified = UserOtp::where('user_id', $user->id)
                              ->where('used', true)
                              ->where('expires_at', '>', now()->subMinutes(10))
                              ->latest()
                              ->first();

        if (!$otpVerified) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى التحقق أولاً من رمز التحقق.'
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تغيير كلمة المرور بنجاح، يمكنك الآن تسجيل الدخول.'
        ]);
    }
}
