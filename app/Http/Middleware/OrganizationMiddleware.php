<?php

namespace App\Http\Middleware;
use Route;
use Closure;
use App\Model\Organization;
use Auth;
use Response;
use App\User;
use Log;



class OrganizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
   
    public function handle($request, Closure $next)
     {
 		 try
		 {
            $route = Route::getRoutes()->match($request);
            //getting the route parameters
            $routeParams = $route->parameters();
		    $organizationid = (!empty($routeParams[ 'id']) && is_numeric($routeParams['id']))?$routeParams['id']:null;
				
            //get the action name ex: App\Http\Controllers\User\MarketplaceController@index
            $routeName = $route->getActionName();
			//Written by suresh			
			if (strpos($routeName,'@') === false) {
				return $next($request);
			}
			//end
            list($controller, $method) = explode('@', $routeName);
            //get the exact controller name ex: controller: MarketplaceController
            $controller = preg_replace('/.*\\\/', '', $controller);
            $userId = isset(Auth::user()->id)?Auth::user()->id:null;
		    $notAllowed = array('edit','deleteorganization');
		
			 if($method=='edit')
			 {
				  $organizationid  = (!empty($routeParams['organization']) && is_numeric($routeParams['organization']))?$routeParams['organization']:null;
			 }
			 $allowedUserIds = array();
			 $allowedUserIds = Organization::select('user_id')->where('id',   $organizationid )->get();   		
			 $allowedUserIdsResult = array();
            //if $allowedUserIds is not empty then make the multi dimensional array to single dimensional array
            if(count($allowedUserIds))
            {
                $allowedUserIdsResult = array_flatten($allowedUserIds->toarray());
            }
			
			 if(!empty($controller) && !empty($method))
                {
                if($controller == 'OrganizationController' && in_array($method, $notAllowed))
					{
					           
						 if( !in_array( $userId, $allowedUserIdsResult))
							{
							 return response()->view('errors.'.'503');
							}
					   
						   else{
							   return $next($request);    
						   }
					}
					else
					{
						  return $next($request);    
					}
              }
			  return $next($request);
		 }
		   catch(exception $e)
        {
            Log::error($e->getMessage());
            return response()->view('errors.'.'503');
        } 
		
	 }
}