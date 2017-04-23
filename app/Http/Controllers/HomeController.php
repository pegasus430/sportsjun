<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\Infolist;
use App\User;
use Session;
use Auth;
use Carbon\Carbon;

class HomeController extends Controller {

    public static function shareResource(){
        $idNameCountry = \App\Repository\CountryRepository::idList('country_name');
        \View::share(compact('idNameCountry'));
    }

    public function index(){
        self::shareResource();
        if (\Auth::user() && Auth::user()->type!='1'){
            return redirect(url('/myschedule',[\Auth::user()->id]));
        }
        if (\Auth::user() && Auth::user()->type=='1'){
            $org = Auth::user()->organizations;
            if(count($org)) return redirect()->to('/organization/'.$org[0]['id']);

            return redirect(url('/organization/organizations',[\Auth::user()->id]));
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
            self::shareResource();
            return view('home.' . $page);
    }
    
    public function skip() {
        User::where(['id' => Auth::user()->id])->update(['profile_updated' => 1]);
        return redirect('/');
    }


}
