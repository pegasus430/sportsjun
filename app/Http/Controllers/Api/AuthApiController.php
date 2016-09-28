<?php

namespace App\Http\Controllers\Api;

use App\Events\UserRegistered;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthApiController extends Controller
{
     /**
     * API Login, on success return JWT Auth token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }

    public function register(Request $request) {
        $data = $request->all();

        $validator = \Validator::make($data, [
                'name' => 'required|max:255',
                'firstname' => 'required|max:255',
                'lastname' => 'required|max:255',
                'email' => 'required|unique:users,email|email|max:255',
                'password' => 'required|min:6',
            ]);

        if (!$validator->fails()){
            $user = User::create([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'name' => $data['firstname'].' '.$data['lastname'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'newsletter'=> !empty($data['newsletter'])?1:0,
                'verification_key' => md5($data['email']) //TODO:: these thing should be changed across all site
            ]);
            if ($user){
                \Event::fire(new UserRegistered($user));
                $token = JWTAuth::fromUser($user);
                return compact('token');
            } else{
                $error = ['message'=>'Failed to create user'];
            }
        } else {
            $error = $validator->errors();
        }

        return response()->json(['error' => $error], 500);
    }


    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     * 
     * @param Request $request
     */
    public function logout(Request $request) {
        $this->validate($request, [
            'token' => 'required' 
        ]);
        
        JWTAuth::invalidate($request->input('token'));
    }
}
