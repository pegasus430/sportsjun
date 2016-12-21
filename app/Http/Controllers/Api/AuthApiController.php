<?php

namespace App\Http\Controllers\Api;

use App\Events\UserRegistered;
use App\Services\MSG91;
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
                return self::ApiResponse(['error' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return self::ApiResponse(['error' => 'Internal server error'], 500);
        }
        return self::ApiResponse(compact('token'));
    }

    public function register(Request $request)
    {
        $data = $request->all();

        $validator = \Validator::make($data, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'mobile' => 'numeric|digits_between:3,20',
            'email' => 'required|unique:users,email|email|max:255',
            'password' => 'required|min:6',
        ]);

        if (!$validator->fails()) {
            $user = User::create([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'name' => $data['firstname'] . ' ' . $data['lastname'],
                'email' => $data['email'],
                'contact_number' => array_get($data, 'mobile'),
                'password' => bcrypt($data['password']),
                'newsletter' => !empty($data['newsletter']) ? 1 : 0,
                'verification_key' => md5($data['email']) //TODO:: these thing should be changed across all site
            ]);
            if ($user) {
                \Event::fire(new UserRegistered($user));
                $token = JWTAuth::fromUser($user);
                return self::ApiResponse(compact('token'));
            } else {
                $error = 'Failed to create user';
            }
        } else {
            $error = $validator->errors()->getMessages();
        }

        return self::ApiResponse(['error' => $error], 500);
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
        $data = $request->all();
        $validator = \Validator::make($data, [
            'token' => 'required'
        ]);
        if (!$validator->fails()) {
            JWTAuth::invalidate($request->input('token'));
            return self::ApiResponse(['message' => 'Logout'], 200);
        } else {
            return self::ApiResponse(['error' => $validator->errors()->first()], 500);
        }
    }

    public function generateOTP(Request $request)
    {
        $data = $request->all();
        $validator = \Validator::make($data, [
            'mobileNumber' => 'required',
            'timeToken' => 'required'
        ]);
        if (!$validator->fails()) {
            $user_id = \Auth::user()->id;
            $result = MSG91::generateOTP($data['mobileNumber'], $user_id, $data['timeToken']);
            $resp = [];
            if (!$result || $result['success'] == false) {
                $resp['error'] = array_get($result, 'response.code');
                return self::ApiResponse($resp, 500);
            } else {
                $resp['message'] = "OTP SENT SUCCESSFULLY";
                return self::ApiResponse($resp);
            }
        } else {
            return self::ApiResponse(['error' => $validator->errors()->first()], 500);
        }


    }

    public function verifyOTP(Request $request)
    {
        $data = $request->all();
        $validator = \Validator::make($data, [
            'mobileNumber' => 'required',
            'timeToken' => 'required',
            'otp' => 'required'
        ]);
        if (!$validator->fails()) {
            $user_id = \Auth::user()->id;
            $mobileNumber = preg_replace('/\D/', '', $data['mobileNumber']);
            $result = MSG91::verifyOTP($user_id, $data['otp'], $mobileNumber, $data['timeToken']);

            $resp = [];
            if ($result) {
                $resp['message'] = "NUMBER VERIFIED SUCCESSFULLY";
                return self::ApiResponse($resp);
            } else {
                $resp['message'] = "OTP INVALID";
                return self::ApiResponse($resp, 500);
            }
        } else {
            return self::ApiResponse(['error' => $validator->errors()->first()], 500);
        }
    }

    //check is OTP sent to mobileNumber
    public function isOtpSent(Request $request)
    {
        $data = $request->all();
        $validator = \Validator::make($data, [
            'mobileNumber' => 'required',
            'timeToken' => 'required',
        ]);
        if (!$validator->fails()) {
            $mobileNumber = preg_replace('/\D/', '', $data['mobileNumber']);
            if (MSG91::isOtpSent($mobileNumber, $data['timeToken'])) {
                return self::ApiResponse(['message' => 'OTP is sent']);
            }
            return self::ApiResponse(['message' => 'OTP is not sent'], 500);
        } else {
            return self::ApiResponse(['error' => $validator->errors()->first()], 500);
        }
    }


}

