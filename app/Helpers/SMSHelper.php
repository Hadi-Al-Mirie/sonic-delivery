<?php
namespace App\Helpers;

class SMSHelper
{
    public static function generateCode()
    {
        return rand(100000, 999999);
    }

    public static function sendSMS($phoneNumber, $message)
    {
        try {
            $sid = env('TWILIO_SID');
            $token = env('TWILIO_AUTH_TOKEN');
            $from = env('TWILIO_PHONE_NUMBER');
            $twilio = new \Twilio\Rest\Client($sid, $token);
            $twilio->messages->create(
                $phoneNumber,
                [
                    'from' => $from,
                    'body' => $message,
                ]
            );
        } catch (\Exception $e) {
            \Log::error('SMS sending failed', ['error' => $e->getMessage()]);
            throw new \Exception('Unable to send SMS.');
        }
    }
}
