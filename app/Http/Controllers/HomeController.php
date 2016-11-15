<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\Infolist;
use App\User;
use Session;
use Auth;
use Carbon\Carbon;

class HomeController extends Controller {

    public function index(){
        if (\Auth::user()){
            return redirect(url('/myschedule',[\Auth::user()->id]));
        }


        $banners = Infolist::banners()->active()->orderBy('weight','desc')->get();
        $testimonials = Infolist::testimonials()->active()->orderBy('weight','desc')->get();
        $our_clients = Infolist::clients()->active()->orderBy('weight','desc')->get();


        return view('home',

            compact('testimonials','our_clients','banners'));
    }

    public function page($page){
            if ($page == 'index') {
                return $this->index();
            }
            return view('home.' . $page);
    }
    
    public function skip() {
        User::where(['id' => Auth::user()->id])->update(['profile_updated' => 1]);
        return redirect('/');
    }


}
