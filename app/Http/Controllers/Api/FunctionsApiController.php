<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Response;

class FunctionsApiController extends Controller
{
    
        public function follow_unfollow($type, $id, $flag)
        {
                $id      = !empty($id) ? $id : 0;
                $type    = !empty($type) ? strtolower($type) : null;
                $flag    = !empty($flag) ? $flag : 0;
                $user_id = Auth::user()->id;
                $result  = 'fail';
                if (is_numeric($id) && !empty($type) && is_numeric($flag))
                {
                        $condition = empty($flag) ? " ,deleted_at=now() " : " ,deleted_at=NULL ";
                        $query     = "INSERT INTO followers (`user_id`,`type`,`type_id`) values ($user_id,'$type',$id)
                        ON DUPLICATE KEY UPDATE updated_at=now() $condition";
                        // DB::raw("$query");
                        DB::statement("$query");
                        $result    = 'success';
                }
                return Response::json(['status' => $result]);
        }

        

        public function sendFeedback(Request $request){

        }


}
