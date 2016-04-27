<?php

namespace App\Http\Middleware;
use Route;
use Closure;
use App\Model\MarketPlace;
use App\Model\Photo;
use Auth;
use Response;
use App\User;
use Log;
use App\Helpers\Helper;


class PhotosMiddleware
{
    /**
     * Handle an incoming request.
     * Check permission for deleting a photo
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
     {
 		 /*try
		 {
            $route = Route::getRoutes()->match($request);
            //getting the route parameters
            $routeParams = $route->parameters();

            $userID = isset(Auth::user()->id)?Auth::user()->id:null; // Logged in userid
            $photoID = (!empty($routeParams['id']) && is_numeric($routeParams['id']))?$routeParams['id']:null;
            if(is_null($photoID)){
                //Throw 500 error
            }else{
                //Get photo details
                $photo = Photo::find($photoID);
                $imageType = $photo['imageable_type'];
                $imageID = $photo['imageable_id'];
                $imageUploadedBy = $photo['user_id'];
                $modelName = Helper::getImageModel(array('imageType'=>$imageType));
                $userId = isset(Auth::user()->id)?Auth::user()->id:null;

                if (class_exists($modelName)) {
                    if(is_null($userId)){
                        //Session Expired
                    }else{
                        $params = array();
                        $params['loggedin_user_id'] = $userId;
                        $params['owner_user_id'] = $imageUploadedBy;
                        $params['imageable_type'] = $imageType;
                        $params['imageable_id'] = $imageID;
                        $params['loggedin_user_role'] = Auth::user()->role;
                        $result = $modelName::checkPermission($params);
                        dd($result);
                        if($result){
                            return $next($request);
                        }else{
                            if ($this->auth->guest()) {
                                if ($request->ajax()) {
                                    return response('Unauthorized.', 401);
                                }else{
                                    return response()->view('errors.'.'503');
                                }
                            }
                        }
                    }
                
                }                
                //dd($photo);
            }

			return $next($request);
		 }
		   catch(exception $e)
        {
            Log::error($e->getMessage());
            return response()->view('errors.'.'503');
        } */
		return $next($request);
	 }
}