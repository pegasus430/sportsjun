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
        $testimonials = Infolist::testimonials()->active()->get();
        $our_clients = Infolist::clients()->active()->get();


        return view('home',
            compact('testimonials','our_clients'));
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
