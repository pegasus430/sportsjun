<?php

namespace App\Http\Middleware;
use Route;
use Log;
use App\User;
use App\Model\TeamPlayers;
use App\Model\Organization;
use Auth;
use Response;
use Closure;
use Helper;

class TeamMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
/*    protected $except = [
     '/admin/*',
    ];*/
    // public function __construct(){}
    public function handle($request, Closure $next)
    {

        try
        {
            //get the route
            $route = Route::getRoutes()->match($request);
            //getting the route parameters
            $routeParams = $route->parameters();
            if(!empty($routeParams['team_id']))
            {
                $teamId = (!empty($routeParams['team_id']) && is_numeric($routeParams['team_id']))?$routeParams['team_id']:null;    
            }
            elseif (!empty($routeParams['teamId'])) {
                $teamId = (!empty($routeParams['teamId']) && is_numeric($routeParams['teamId']))?$routeParams['teamId']:null;  
            }
            else
            {
                $teamId = null;
            }
            
            //get the action name ex: App\Http\Controllers\User\TeamController@teamslist
            $routeName = $route->getActionName();
            //break the string to extract controller and function name ex: controller: App\Http\Controllers\User\TeamController and function: teamslist
			
			//Written by suresh			
			if (strpos($routeName,'@') === false) {
				return $next($request);
			}
			//end
            list($controller, $method) = explode('@', $routeName);
            //get the exact controller name ex: controller: TeamController
            $controller = preg_replace('/.*\\\/', '', $controller);
            $userId = isset(Auth::user()->id)?Auth::user()->id:null;
            $allowedUserIds = array();
            $adminRoles = array('owner','manager');
            //notAllowedArray is the array which is having the methods not allowed for users other than admin roles
            $notAllowed = array('updateteam','editteam','deleteteam','makeasteammanager','removeplayerfromteam','makeasteamcaptain','makeasteamvicecaptain','removeasteammanager','sendinvitereminder','getorgteamdetails','organizationlist');
            $myTeamPlayerOptions = array('makeasteammanager','removeplayerfromteam','makeasteamcaptain','makeasteamvicecaptain','removeasteammanager','sendinvitereminder');        
            $role = null;
            $playerId = null;
            //if team id is not empty, get the user ids based on team id and roles
            if(!empty($teamId))
            {
                $allowedUserIds = TeamPlayers::whereIn('role',$adminRoles)->where('team_id',$teamId)->get(array('user_id')); 
            }		
			if($method=='organizationlist')
			{			
				$orgid = (!empty($routeParams['id']) && is_numeric($routeParams['id']))?$routeParams['id']:null;
				$allowedUserIds = Organization::select('user_id')->where('id', $orgid )->get();   		
			}
			if($method=='getorgteamdetails')
			{
				$orgid = (!empty($routeParams['id']) && is_numeric($routeParams['id']))?$routeParams['id']:null;
				$allowedUserIds = Organization::select('user_id')->where('id', $orgid )->get();   		
			}
			$allowedUserIdsResult = array();
            //if $allowedUserIds is not empty then make the multi dimensional array to single dimensional array
            if(count($allowedUserIds))
            {
                $allowedUserIdsResult = array_flatten($allowedUserIds->toarray());
            }
            if(!empty($controller) && !empty($method))
            {
                //if controller is team controller and the method is in notAllowedArray
                if(($controller == 'TeamController') && in_array($method, $notAllowed))
                {
                    //if the logged in userid is not in allowed user ids 
                    if(!in_array($userId, $allowedUserIdsResult))
                    {
                        //if the route method is in myTeamPlayerOptions array
                        if(in_array($method, $myTeamPlayerOptions))
                        {
                            //get the player id
                            $playerId = (!empty($routeParams['user_id']) && is_numeric($routeParams['user_id']))?$routeParams['user_id']:null;
                            //get player team id count
                            $playerTeamIds = TeamPlayers::where('user_id',$playerId)->where('team_id',$teamId)->count();
                            //get logged in user team ids
                            $userTeamIds = TeamPlayers::where('user_id',$userId)->get(array('team_id'));
                            // Helper::printQueries();
                            $userTeamIdsResult = array();
                            if(count($userTeamIds))
                            {
                                $userTeamIdsResult = array_flatten($userTeamIds->toarray());
                            }
                            //if player teamid count !=1 and team id not in user iteam ids, return false
                            if($playerTeamIds != 1 && !in_array($teamId, $userTeamIdsResult))
                            {
                                return response()->view('errors.'.'503');
                            }

                        }                        
                        //if ajax request show alert
                        if ($request->isXmlHttpRequest())
                        {
                            return Response::json( [
                                'error' => [
                                   'message' => 'You do not have access.',
                                ]
                            ], 500 );
                        } 
                        else //else redirect to error page
                        {
                            return response()->view('errors.'.'503');    
                        }
                    }
                    else
                    {   
                        return $next($request);    
                    }               
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
