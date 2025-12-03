<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\SmsMessage;

class SmsService
{
    private $apiUrl;
    private $username;
    private $password;
    private $sender;

    public function __construct()
    {
        $this->apiUrl   = config('services.sms.url');
        $this->username = config('services.sms.username');
        $this->password = config('services.sms.password');
        $this->sender   = config('services.sms.sender');
    }

    public function send($phone, $messageContent, $relatedId = null, $relatedType = null)
    {
        $sms = SmsMessage::create([
            'phone'        => $phone,
            'message'      => $messageContent,
            'status'       => 'pending',
            'related_id'   => $relatedId,
            'related_type' => $relatedType,
        ]);

        try {

            $params = [
                'user_name' => $this->username,
                'user_pass' => $this->password,
                'sender'    => $this->sender,
                'mobile'    => $phone,
                'type'      => 0,
                'text'      => $messageContent,
            ];

            $response = Http::timeout(20)->get($this->apiUrl, $params);
            $apiResponse = $response->body();

            $sms->update([
                'status'       => $response->successful() ? 'sent' : 'failed',
                'api_response' => $apiResponse,
            ]);

            return $response->successful();

        } catch (\Exception $e) {

            $sms->update([
                'status'       => 'failed',
                'api_response' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
