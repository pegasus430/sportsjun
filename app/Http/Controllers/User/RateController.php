<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Model\Rating;

class RateController extends Controller
{

    public function setUserRate(){
        $user = \Auth::user();
        if ($user){
            $data = \Request::all();
            $validator = \Validator::make($data, [
                'to_id'=>'required|numeric',
                'type'=>'required|in:user,team,organization,tournament,ptournament',
                'rate'=>'required|between:1,5'
            ]);

           if (!$validator->fails()){
                switch ($data['type']){
                    case 'user': $type= Rating::$RATE_USER; break;
                    case 'organization': $type= Rating::$RATE_ORGANIZATION; break;
                    case 'team': $type= Rating::$RATE_TEAM; break;
                    case 'tournament': $type= Rating::$RATE_TOURNAMENT; break;
                    case 'ptournament': $type= Rating::$RATE_PARENT_TOURNAMENT; break;
                }


                $rating = Rating::where([
                    'user_id'=>$user->id,
                    'type'=>$type,
                    'to_id'=>$data['to_id']
                ])->first();

                if (!$rating){
                    $rating = Rating::create([
                        'user_id'=>$user->id,
                        'type'=>$type,
                        'to_id'=>$data['to_id'],
                        'rate' => $data['rate']
                    ]);

                    if (\Request::ajax()){
                        if (\Request::wantsJson()){
                            return ['message'=>trans('message.rate.success')];
                        } else {

                        }
                    } else {
                        \Session::flash('status', trans('message.rate.success'));
                    }
                }  else {
                    $rating->rate = $data['rate'];
                    $rating->save();
                    if (\Request::ajax()){
                        if (\Request::wantsJson()){
                            return ['message'=>trans('message.rate.success_update')];
                        } else {

                        }
                    } else {
                        \Session::flash('message', trans('message.rate.success_update'));
                    }
                }


            } else {
               if (\Request::ajax()){
                   if (\Request::wantsJson()){
                       return ['error'=>$validator->errors()->first()];
                   } else {

                   }
               } else {
                   \Session::flash('error', $validator->errors()->first());
               }
            }
        }
        return redirect()->back();
    }

}
