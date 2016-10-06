<?php

namespace App\Http\Controllers\Api;

use App\Events\UserRegistered;
use App\Http\Services\MSG91;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthApiController extends BaseApiController
{
    /**
     * API Login, on success return JWT Auth token
     *
     * @param Request $request
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->ApiResponse(['error'=>'Invalid credentials'],401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->ApiResponse(['error'=>'Internal server error'],500);
        }
        return $this->ApiResponse(compact('token'));
    }

    public function register(Request $request)
    {
        $data = $request->all();

        $validator = \Validator::make($data, [
            'name' => 'required|max:255',
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'mobile'=> 'max:20',
            'email' => 'required|unique:users,email|email|max:255',
            'password' => 'required|min:6',
        ]);

        if (!$validator->fails()) {
            $user = User::create([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'name' => $data['firstname'] . ' ' . $data['lastname'],
                'email' => $data['email'],
                'contact_number'=> array_get($data,'mobile'),
                'password' => bcrypt($data['password']),
                'newsletter' => !empty($data['newsletter']) ? 1 : 0,
                'verification_key' => md5($data['email']) //TODO:: these thing should be changed across all site
            ]);
            if ($user) {
                \Event::fire(new UserRegistered($user));
                $token = JWTAuth::fromUser($user);
                return $this->ApiResponse(compact('token'));
            } else {
                $error = ['message' => 'Failed to create user'];
            }
        } else {
            $error = $validator->errors();
        }

        return $this->ApiResponse(['error' => $error], 500);
    }


    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        JWTAuth::invalidate($request->input('token'));
        return $this->ApiResponse(['message'=>'Logout'], 200);
    }

    public function generateOTP(Request $request)
    {
        $mobileNumber = $request->mobileNumber;
        $user_id = \Auth::user()->id;
        $token = $request->timeToken;
        $result = MSG91::generateOTP($mobileNumber, $user_id, $token);

        $resp = [];

        if (!$resp || $resp['success'] == false) {
            $resp['error'] = 'Error';
            $resp['message'] = array_get($result, 'code');
            return $this->ApiResponse($resp,500);
        } else {
            $resp['message'] = "OTP SENT SUCCESSFULLY";
            return $this->ApiResponse($resp);
        }
    }

    public function verifyOTP(Request $request)
    {
        $user_id = \Auth::user()->id;
        $token = $request->timeToken;
        $otp = $request->otp;
        $mobileNumber = preg_replace('/\D/', '', $request->mobileNumber);

        $result = MSG91::verifyOTP($user_id, $otp, $mobileNumber, $token);

        $resp = [];
        if ($result) {
            $resp['message'] = "NUMBER VERIFIED SUCCESSFULLY";
            return $this->ApiResponse($resp);
        } else {
            $resp['message'] = "OTP INVALID";
            return $this->ApiResponse($resp,500);
        }
    }

    //check is OTP sent to mobileNumber
    public function isOtpSent(Request $request)
    {
        $mobileNumber = preg_replace('/\D/', '', $request->mobileNumber);
        $token = $request->timeToken;
        if (MSG91::isOtpSent($mobileNumber, $token)) {
            return $this->ApiResponse(['message'=>'OTP is sent']);
        }
        return $this->ApiResponse(['message'=>'OTP is not sent'],500);
    }


}
