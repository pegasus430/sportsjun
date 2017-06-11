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
use App\Model\Photo;
use App\Http\Controllers\User\InvitePlayerController;
use App\Helpers\Helper;
use App\Helpers\SendMail;
use App\Model\coaching_player;  
use App\User;
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
        $coaching->organization_id = $id;
        $coaching->user_id = Auth::user()->id;

        $coaching->save();

        $i=0; 

        $payment_method = $request->payment_method; 
        foreach(Helper::get_subscription_methods($request->payment_method) as $option){

            if($request->{$request->payment_method.'_choose_amount_'.$option->id}=='on'){
                $c_option = new coaching_option;
                $c_option->price = $request->{$payment_method.'_amount_'.$option->id}; 
                 if($request->{$request->payment_method.'_choose_discount_'.$option->id}=='on'){
                    $c_option->discount= $request->{$payment_method.'_discount_'.$option->id};
                 }

                $c_option->coaching_id = $coaching->id;
                $c_option->organization_id = $id; 
                $c_option->payment_method = $request->payment_method;
                $c_option->subscription_id = $option->id; 
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
                $coaching->image_url = asset('/uploads/'.config('constants.PHOTO_PATH.ORGANIZATION_COACHING').'/'.$l['url']);
                $coaching->save();
            
            }

        }

        return redirect()->back()->with('message', 'Coaching Session Added');

    }


    public function edit_session($id, $coaching_id){
        $coaching = coaching::find($coaching_id); 
        $types = config('constants.ENUM.SUBSCRIPTION_TYPE');
        return view('organization_2.coaching.edit', compact('coaching', 'types'));
    }

    public function delete_session($id, $coaching_id){
        $coaching = coaching::find($coaching_id);
        $coaching->delete();
        return redirect()->back()->with('message', 'Coaching Deleted!');
    }

    public function update_session($id, $coaching_id, Request $request){


        $coaching = coaching::find($coaching_id);

        $coaching->title = $request->title;
        $coaching->start_date = $request->start_date;
        $coaching->end_date   = $request->end_date;
        $coaching->number_of_players = $request->number_of_players;
        $coaching->staff_id = $request->coach_id; 
        $coaching->sports_id = $request->category_id; 
        $coaching->payment_method = $request->payment_method;
        $coaching->organization_id = $id;
        $coaching->user_id = Auth::user()->id;

        $coaching->save();

        $i=0; 

        $payment_method = $request->payment_method;

        foreach(Helper::get_subscription_methods($request->payment_method) as $option){

            if($request->{$request->payment_method.'_choose_amount_'.$option->id}=='on'){

                $c_option = $coaching->payment_option($option->id);
                if(!$c_option) $c_option =  new coaching_option; 

                $c_option->price = $request->{$payment_method.'_amount_'.$option->id}; 
                 if($request->{$request->payment_method.'_choose_discount_'.$option->id}=='on'){
                    $c_option->discount= $request->{$payment_method.'_discount_'.$option->id};
                 }

                $c_option->coaching_id = $coaching->id;
                $c_option->organization_id = $id; 
                $c_option->payment_method = $request->payment_method;
                $c_option->subscription_id = $option->id; 
                $c_option->save();
            }
           
        }

       

        foreach($coaching->payment_options as $op){
            if(!$request->{$request->payment_method.'_choose_amount_'.$op->subscription_id}) $op->delete(); 
        }

            return redirect()->back()->with('message', 'Coaching Session Updated! ');


    }

    public function show_session($id, $coaching_id){
        $coaching = coaching::find($coaching_id); 

        return view('organization_2.coaching.session', compact('coaching')); 
    }

    public function add_player_to_session($id, $coaching_id, request $request){
        $coaching = coaching::find($coaching_id);
       $user = User::where(['email'=>$request->email])->first();
        if(!$user){
             $generatedPassword= str_random(6);
                $password=  bcrypt( $generatedPassword);
            $user = new user;
            $user->email = $request->email;
            $user->name = $request->name;
            if($request->country_id){
                    $user->country_id = $request->country_id;
                    $user->state_id = $request->state_id;
                    $user->city_id     = $request->city_id;
            }
        
            $user->password = $request->password;

            $user->save();

                 $to_user_id = $user->id;
                $to_email_id=  $user->email;
                $user_name = $user->name;
                $subject =  trans('message.inviteplayer.subject');
                $view_data = array('email'=>$to_email_id,'password'=>$generatedPassword ,'user_name'=>$user_name,'team_name'=>$coaching->title);
                $view = 'emails.invitePlayers';
                $data = array('view'=>$view,'subject'=>$subject,'to_email_id'=>$to_email_id,'view_data'=>$view_data,'to_user_id'=>  $to_user_id,'flag'=>'user','send_flag'=>1);
                SendMail::sendmail($data);

        }

        $player = coaching_player::where(['coaching_id'=>$coaching_id,'user_id'=>$user->id])->first();

            if (!$player) {
                $player = new coaching_player([
                    'coaching_id'=>$coaching_id,
                    'user_id'=>$user->id
                ]);
            }
            $player->save();

        if($request->parent_email){

        $parent = User::where(['email'=>$request->parent_email])->first();
        if(!$parent){
             $generatedPassword= str_random(6);
              $password=  bcrypt( $generatedPassword);

            $parent = new user; 
            $parent->email = $request->parent_email;
            $parent->name = $request->name;
            $parent->country_id = $request->country_id;
            $parent->state_id = $request->state_id;
            $parent->city_id     = $request->city_id;
            $parent->password = $password;
            $parent->save();

                 $to_user_id = $parent->id;
                $to_email_id=  $parent->email;
                $user_name = $parent->name;
                $subject =  trans('message.inviteplayer.subject');
                $view_data = array('email'=>$to_email_id,'password'=>$generatedPassword ,'user_name'=>$user_name,'team_name'=>$teamname[0]['name']);
                $view = 'emails.invitePlayers';
                $data = array('view'=>$view,'subject'=>$subject,'to_email_id'=>$to_email_id,'view_data'=>$view_data,'to_user_id'=>  $to_user_id,'flag'=>'user','send_flag'=>1);
                SendMail::sendmail($data);
        }

        $child = new child; 
        $child->child_id   = $user->id;
        $child->parent_id = $parent->id; 
        $child->organization_id = $request->organization_id;
        $child->save();

    }

        return redirect()->back()->with('message', 'Player Added');

    }
}
