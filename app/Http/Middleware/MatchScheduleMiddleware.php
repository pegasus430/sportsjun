<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\UserStatistic;
use Auth;

class MatchScheduleMiddleware
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

/*            $userStatistic = UserStatistic::where('user_id', Auth::user()->id)->first();
            if(count($userStatistic) && isset($request->teamId)) {
                $followingTeamsArray = explode(',', trim($userStatistic->following_teams, ','));
                if(in_array($request->teamId, $followingTeamsArray)) {
                  return $next($request);       
                }  
                $managingTeamsArray = explode(',', trim($userStatistic->managing_teams, ','));
                if(in_array($request->teamId, $managingTeamsArray)) {
                  return $next($request); 
                }  
                $joinedTeamsArray = explode(',', trim($userStatistic->joined_teams, ','));
                if(in_array($request->teamId, $joinedTeamsArray)) {
                    return $next($request); 
                }  
                
                return response()->view('errors.'.'503');
            }
            return response()->view('errors.'.'503');
            */
            $user_id = Auth::user()->id;
            $modelObj = new \App\Model\Team;
            $teamDetails = $modelObj->getTeamsByRole($user_id,1);

            $modelObj = new \App\Model\Followers;
            $follow_teamDetails = $modelObj->getFollowingList($user_id,'team'); 
                      
            if(!empty($follow_teamDetails[0]->following_list))
            {
                $followingTeamsArray = array_filter(explode(',',$follow_teamDetails[0]->following_list));
                if(in_array($request->teamId, $followingTeamsArray)) {
                  return $next($request);       
                }  
            }
            
            if(!empty($teamDetails[0]->managing_teams))
            {
                $managingTeamsArray =  array_filter(explode(',',$teamDetails[0]->managing_teams));
                if(in_array($request->teamId, $managingTeamsArray)) {
                  return $next($request); 
                }  
            }
            
            if(!empty($teamDetails[0]->joined_teams))
            {
                $joinedTeamsArray = array_filter(explode(',',$teamDetails[0]->joined_teams));
                if(in_array($request->teamId, $joinedTeamsArray)) {
                    return $next($request); 
                }  
            }

            return response()->view('errors.'.'503');
            
    }
}