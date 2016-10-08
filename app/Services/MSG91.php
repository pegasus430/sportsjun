<?php

namespace App\Http\Services;


use App\Model\Otp;

class MSG91
{
    public static function callGenerateAPI($mobileNumber)
    {
        $otpApiUrl = "https://sendotp.msg91.com/api";
        $mobileNumber = preg_replace('/\D/', '', $mobileNumber);
        $senderId = config('services.msg91.sender_id');
        $data = array(
            'sender' => $senderId,
            'route' => 4,
            'countryCode' => '91',
            'mobileNumber' => $mobileNumber,
            'getGeneratedOTP' => true
        );
        $data_string = json_encode($data);
        $ch = curl_init($otpApiUrl . '/generateOTP');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string),
            'application-Key:' . config('services.msg91.application_key')
        ));
        $result = curl_exec($ch);
        //dd($result);
        curl_close($ch);
        return $result;
    }

    public static  function generateOTP($mobileNumber, $user_id, $token)
    {
        $mobileNumber = preg_replace('/\D/', '', $mobileNumber);

        $response = self::callGenerateAPI($mobileNumber);
        $response = json_decode($response, true);

        if (!$response)
            return false;

        if ($response["status"] == "error") {
            return ['response'=>$response["response"],'success'=>false];
        }

        Otp::create(
            [
                'user_id' => $user_id,
                'token' => $token,
                'otp' => array_get($response, "response.oneTimePassword")
            ]);
        return ['response'=>$response["response"],'success'=>true];
    }


    public static  function verifyOTP($user_id, $otp, $mobileNumber, $token)
    {
        $mobileNumber = preg_replace('/\D/', '', $mobileNumber);
        $query = Otp::where(
            [
                'user_id' => $user_id,
                'otp' => $otp,
                'token' => $token,
                'contact_number' => $mobileNumber,
                'is_verified',
                0
            ]);
        $query->orderBy('id', 'desc')->limit(1);
        $results = $query->get();

        if (count($results) == 1) {
            Otp::where('id', $results[0]['id'])->update(['is_verified' => 1]);
            return true;
        } else {
            return false;
        }
    }

    public static  function isOtpSent($mobileNumber, $token)
    {
        $isOtpSent = Otp::where('contact_number', $mobileNumber)->where('token', $token)->where('is_verified',
            1)->count();
        return $isOtpSent > 0;
    }
}