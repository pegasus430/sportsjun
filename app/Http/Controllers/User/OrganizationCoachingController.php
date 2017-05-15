<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\OrganizationController; 
use App\Model\subscription_method;
use App\Model\coaching;
use App\Model\coaching_pay_options as coaching_option;
use Auth;
use Session;
use Helper;
use App\Model\Photo;
class OrganizationCoachingController extends OrganizationController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   


    public function coaching_index($id){
        $coachings = coaching::where('organization_id', $id)->get();
        return view('organization_2.coaching.index', compact('coachings'));
    }

    public function create_session(){
    	$types = config('constants.ENUM.SUBSCRIPTION_TYPE');
    	return view('organization_2.coaching.create_session', compact('types'));
    }

    public function store_session($id, Request $request){
       // return $request->all();

        $coaching = new coaching;
        $coaching->title = $request->title;
        $coaching->start_date = $request->start_date;
        $coaching->end_date   = $request->end_date;
        $coaching->number_of_players = $request->number_of_players;
        $coaching->staff_id = $request->coach_id; 
        $coaching->sports_id = $request->category_id; 
        $coaching->payment_method = $request->payment_method;
        $coaching->organization_id = Session::get('organization_id');
        $coaching->user_id = Auth::user()->id;

        $coaching->save();

        $i=0; 

        foreach(Helper::get_subscription_methods($request->payment_method) as $option){

            if($request->{$request->payment_method.'_choose_amount_'.$option->id}=='on'){
                $c_option = new coaching_option;
                $c_option->price = $request->payment_method.'_amount_'.$option->id; 
                 if($request->{$request->payment_method.'_choose_discount_'.$option->id}=='on'){
                    $c_option->discount= $request->payment_method.'_discount_'.$option->id;
                 }

                $c_option->coaching_id = $coaching->id;
                $c_option->organization_id = Session::get('organization_id');
                $c_option->save();
            }
           
        }


        $user_id = Auth::user()->id;
        Helper::uploadPhotos($request['filelist_photos'], config('constants.PHOTO_PATH.ORGANIZATION_COACHING'), $id, 1,
            1, config('constants.PHOTO.ORGANIZATION_COACHING'), $user_id);
      
        $logo = Photo::select('url')->where('imageable_type',
            config('constants.PHOTO.ORGANIZATION_COACHING'))->where('imageable_id', $id)->where('user_id',
            Auth::user()->id)->where('is_album_cover', 1)->get()->toArray();
  
         if (!empty($logo)) {
            foreach ($logo as $l) {
                $coaching->image = $l['url'];
                $coaching->image_url = asset('/uploads/organization_news/'.$l['url']);
                $coaching->save();
            
            }

        }



        return redirect()->back()->with('message', 'Coaching Session Added');


    }
}
