<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\UserProvider;
use Request;
use Session;
use Hash;
use Auth;
use Socialite;

class UserController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        dd(2);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

    /**
     * Function to display change password form
     */
    public function changepassword() {
        return view('auth.changepassword');
    }

    /*
     * Function to update new password.
     */

    public function updatepassword() {
        $request = Request::all();
        $rules = ['old_password' => 'required', 'password' => 'required|confirmed|min:6'];
        $v = Validator::make($request, $rules);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        }
        $user = User::find(Auth::user()->id);
        if (!Hash::check($request['old_password'], $user->password)) {
            return redirect()->back()->withErrors(["old_password" => [0 => "The old password does not match."]]);
        }

        $user->password = bcrypt($request['password']);
        $user->save();
        return redirect()->back()->with('status', 'Your password changed succesfully');
    }
    
    public function redirectToProvider($provider='')
    {
            return Socialite::driver($provider)->redirect();
    }
 
    /**
     * Obtain the user information from Facebook.
     *
     * @return Response
     */
    public function handleProviderCallback($provider='')
    {
//        dd($provider);
        
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            return redirect('auth/'.$provider);
        }
 
        $authUser = $this->findOrCreateUser($socialUser,$provider);
        Auth::login($authUser, true);
        return redirect('/');
    }
 
    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $facebookUser
     * @return User
     */
    private function findOrCreateUser($socialUser,$provider)
    {
        $authUser = User::where('email', $socialUser->email)->first();
        if ($authUser){
            $providerExist = UserProvider::where('provider_id', $socialUser->id)->first();
            if(!$providerExist){
                UserProvider::create([
                    'user_id'=>$authUser->id,
                    'provider'=>$provider,
                    'provider_id'=>!empty($socialUser->id)?$socialUser->id:'',
                    'provider_token'=>!empty($socialUser->token)?$socialUser->token:'',
                    'avatar' => !empty($socialUser->avatar)?$socialUser->avatar:'',
                ]);
            }    
            return $authUser;
        }
        $authUser = User::create([
            'firstname' => !empty($socialUser->user->first_name)?$socialUser->user->first_name:$socialUser->name,
            'name' => !empty($socialUser->name)?$socialUser->name:'',
            'email' => !empty($socialUser->email)?$socialUser->email:'',
        ]);
        $userProvider = [
            'user_id'=>$authUser->id,
            'provider'=>$provider,
            'provider_id'=>!empty($socialUser->id)?$socialUser->id:'',
            'provider_token'=>!empty($socialUser->token)?$socialUser->token:'',
            'avatar' => !empty($socialUser->avatar)?$socialUser->avatar:'',
        ]; 
        UserProvider::firstOrCreate($userProvider);
        
        return $authUser;
    }
    //function to get notificaitons
    public function getNotifications()
    {
        $notifications = AllRequests::getNotifications();
        return view('userprofile.notifications')->with(array('notifications'=>$notifications));
    }
}
