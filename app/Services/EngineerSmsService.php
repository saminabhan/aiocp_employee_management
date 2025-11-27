<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\SmsMessage;

class EngineerSmsService
{
    private $apiUrl = 'http://hotsms.ps/sendbulksms.php';
    private $apiToken = '66ef464c07d8f';
    private $sender = 'SAMI NET';

    public function send($phone, $messageContent, $engineerId = null, $userId = null)
    {
        $sms = SmsMessage::create([
            'phone' => $phone,
            'message' => $messageContent,
            'status' => 'pending',
            'engineer_id' => $engineerId,
            'user_id' => $userId,
        ]);

        try {
            $response = Http::get($this->apiUrl, [
                'api_token' => $this->apiToken,
                'sender'    => $this->sender,
                'mobile'    => $phone,
                'type'      => '0',
                'text'      => $messageContent,
            ]);

            $apiResponse = $response->body();

            $sms->update([
                'status' => $response->successful() ? 'sent' : 'failed',
                'api_response' => $apiResponse
            ]);

            return true;

        } catch (\Exception $e) {
            $sms->update([
                'status' => 'failed',
                'api_response' => $e->getMessage()
            ]);

            return false;
        }
    }
}
