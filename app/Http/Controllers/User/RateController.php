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
                'to_id'=>'required|number',
                'type'=>'required|number',
                'rate'=>'required|in:1..5'
            ]);

           if (!$validator->fails()){
                $rating = Rating::where([
                    'user_id'=>$user->id,
                    'type'=>$data['type'],
                    'to_id'=>$data['to_id']
                ]);

                if (!$rating){
                    $rating = Rating::create([
                        'user_id'=>$user->id,
                        'type'=>$data['type'],
                        'to_id'=>$data['to_id'],
                        'rate' => $data['rate']
                    ]);

                    if (\Request::ajax()){
                        if (\Request::wantsJson()){
                            return ['success'=>''];
                        } else {

                        }
                    } else {
                        \Session::flash('status', trans('message.rate.success'));
                    }
                }  else {
                    if (\Request::ajax()){

                    } else {
                        \Session::flash('error', trans('message.rate.already_rated'));
                    }
                }


            } else {
               if (\Request::ajax()){
                   if (\Request::wantsJson()){
                       return ['error'=>''];
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
