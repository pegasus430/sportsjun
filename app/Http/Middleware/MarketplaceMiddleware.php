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



class MarketplaceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle($request, Closure $next)
    // {
          // dd($request->id);
            // $photo = Photo::findOrFail($request->id);
            // $marketplace = MarketPlace::findOrFail($photo->imageable_id);
           // dd($marketplace->user_id.'-'.Auth::user()->id);
            // if ($marketplace->user_id != Auth::user()->id)
            // {
               // throw new \Exception("WE DON'T LIKE ODD REMOTE PORTS");
                // if ( $request->isXmlHttpRequest() ) {
                    // return Response::json( [
                        // 'error' => [
                           // 'exception' => class_basename( $e ) . ' in ' . basename( $e->getFile() ) . ' line ' . $e->getLine() . ': ' . $e->getMessage(),
                            // 'message' => 'You are not allowed!!!!',
                        // ]
                    // ], 500 );
                // }                
            // }
            // return $next($request);
    // }
    public function handle($request, Closure $next)
     {
 		 try
		 {
            $route = Route::getRoutes()->match($request);//echo "here";exit;
            //getting the route parameters
            $routeParams = $route->parameters();
			$marketplaceid = (!empty($routeParams['marketplace']) && is_numeric($routeParams['marketplace']))?$routeParams['marketplace']:null;
				
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
			  $notAllowed = array('edit','deletephoto');
			  $allowedids = array();
			 $list  = MarketPlace::with('photos') ->where('user_id',   $userId )->get()->toArray(); 
			 foreach($list as $l)
			 {
				  $allowedids[]= $l['id'];
			 }
			 if($method=='deletephoto')
			 {
				 	$marketplaceid = (!empty($routeParams['id']) && is_numeric($routeParams['id']))?$routeParams['id']:null;
			 }
			 if(!empty($controller) && !empty($method))
                {
                if($controller == 'MarketplaceController' && in_array($method, $notAllowed))
					{
					
		           
						 if( !in_array($marketplaceid,$allowedids))
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