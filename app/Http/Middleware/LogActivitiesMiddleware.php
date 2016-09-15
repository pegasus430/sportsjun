<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\UserActivities;
use Auth;
use Response;
use Route;
use Request;
use App\Helpers\Helper;

class LogActivitiesMiddleware
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
//return $next($request);
        //dd(Auth::user()->id);
        //dd($request->header('User-Agent'));
        //dd(Route::getCurrentRoute());
        $route = Route::getRoutes()->match($request);
        //$a = Helper::getcurrentroute();
        //dd($route);
        //dd($route->getActionName());
		//Written by suresh	
		$routeName = $route->getActionName();
		if (strpos($routeName,'@') === false) {
			return $next($request);
		}
		//end
        list($controller, $method) = explode('@', $route->getActionName());
        if($method == 'authCheck')
            return $next($request);    
        $controller = preg_replace('/.*\\\/', '', $controller); 
        $activity = array();
        if(Auth::guest())
            $activity['user_id'] = '';
        else
            $activity['user_id'] = Auth::user()->id;

        $activity['session_id'] = $request->cookie('laravel_session');
        $activity['controller_name'] = $controller;
        $activity['method_name'] = $method;
        $activity['url'] = Request::url();
        $activity['params'] = implode(',', $route->parameters());
        $activity['user_agent'] = $request->header('User-Agent');
        $activity['ip_address'] = Request::ip();
        UserActivities::create($activity);
        return $next($request);
    }
}