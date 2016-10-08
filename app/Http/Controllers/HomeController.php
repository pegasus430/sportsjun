<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Session;
use Auth;
use Carbon\Carbon;

class HomeController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Home Controller
      |--------------------------------------------------------------------------
      |
      | This controller renders your application's "dashboard" for users that
      | are authenticated. Of course, you are free to change or remove the
      | controller as you wish. It is just here to get your app started!
      |
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index() {
        $userDetails = User::where('id', Auth::user()->id)->first();
        if (!empty($userDetails) && $userDetails->profile_updated == 0) {
            return redirect(route('user.edit', [Auth::user()->id]));
        }
        return redirect(url('/myschedule',[Auth::user()->id]));
    }
    
    public function skip() {
        User::where(['id' => Auth::user()->id])->update(['profile_updated' => 1]);
        return redirect('/');
    }

}
