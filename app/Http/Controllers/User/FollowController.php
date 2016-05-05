<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Model\Photo;
use App\Model\MatchSchedule;
use App\Model\Sport;
use App\Model\Team;
use App\Model\Tournaments;
use App\User;
use Request;
use Carbon\Carbon;
use Response;
use Auth;
use App\Helpers\Helper;
use App\Model\Followers;
use DB;
use App\Model\TournamentGroups;
use App\Model\TournamentGroupTeams;

class FollowController extends Controller
{

        /**
         * Display a listing of the resource.
         *
         * @return Response
         */
        public function index($id = "")
        {
                Helper::setMenuToSelect(3, 7);
                if ($id == '')
                        $user_id = Auth::user()->id;
                else
                        $user_id = $id;

                $followingPlayersArray     = $followingTournamentsArray = $followingTeamsArray       = [];

                // get following tournaments
                $modelObj                 = new Followers;
                $follow_tournamentDetails = $modelObj->getFollowingList($user_id, 'tournament');
                $following_team_array     = array();
                if (isset($follow_tournamentDetails) && count($follow_tournamentDetails) > 0)
                {
                        $following_team_array = array_filter(explode(',', $follow_tournamentDetails[0]->following_list));
                }

                $followingTournamentDetails = [];

                if (count($following_team_array) > 0)
                {
                        $followingTournamentDetails = Tournaments::with('photos')
                                ->whereIn('id', $following_team_array)
                                ->get(['id', 'name', 'created_by', 'sports_id', 'type',
                                'final_stage_teams', 'description']);
                }

                if (count($followingTournamentDetails))
                {
                        foreach ($followingTournamentDetails->toArray() as $followKey => $followedTournament)
                        {
                                $sportsName                                            = Sport::where('id', $followedTournament['sports_id'])->first(['sports_name']);
                                if (count($sportsName))
                                        $followingTournamentDetails[$followKey]['sports_name'] = $sportsName->sports_name;
                                else
                                        $followingTournamentDetails[$followKey]['sports_name'] = '';

                                $userName                                            = User::where('id', $followedTournament['created_by'])->first(['name']);
                                if (count($userName))
                                        $followingTournamentDetails[$followKey]['user_name'] = $userName->name;
                                else
                                        $followingTournamentDetails[$followKey]['user_name'] = '';
                                if (count($followedTournament['photos']))
                                {
                                        $photoUrl                                      = array_collapse($followedTournament['photos']);
                                        $followingTournamentDetails[$followKey]['url'] = $photoUrl['url'];
                                }
                                else
                                {
                                        //                    $matchScheduleData[$key]['scheduleteamone']['url'] = '';
                                        $photoUrl        = $followingTournamentDetails[$followKey];
                                        $photoUrl['url'] = '';
                                }

                                if ($followedTournament['type'] == 'knockout')
                                {
                                        if (!empty($followedTournament['final_stage_teams']))
                                        {
                                                $followingTournamentDetails[$followKey]['team_count'] = $followedTournament['final_stage_teams'];
                                        }
                                        else
                                        {
                                                $followingTournamentDetails[$followKey]['team_count'] = 0;
                                        }
                                }
                                else
                                {
                                        $followedTournamentGroups = TournamentGroups::where('tournament_id', $followedTournament['id'])->get(['id']);
                                        if (count($followedTournamentGroups))
                                        {
                                                $followedTournamentTeamCount = TournamentGroupTeams::whereIn('tournament_group_id', array_flatten($followedTournamentGroups->toArray()))->count();
                                                if ($followedTournamentTeamCount)
                                                {
                                                        $followingTournamentDetails[$followKey]['team_count'] = $followedTournamentTeamCount;
                                                }
                                                else
                                                {
                                                        $followingTournamentDetails[$followKey]['team_count'] = 0;
                                                }
                                        }
                                        else
                                        {
                                                $followingTournamentDetails[$followKey]['team_count'] = 0;
                                        }
                                }
                        }
                        $followingTournamentsArray = $followingTournamentDetails->toArray();
                }



                // get following teams

                $follow_teamDetails   = $modelObj->getFollowingList($user_id, 'team');
                $following_team_array = array();
                if (isset($follow_teamDetails) && count($follow_teamDetails) > 0)
                {
                        $following_team_array = array_filter(explode(',', $follow_teamDetails[0]->following_list));
                }

                if (count($following_team_array))
                {
                        $teamObj             = new \App\Http\Controllers\User\TeamController;
                        $followingTeamsArray = $teamObj->getteamdetails($following_team_array);
                }
                
                // get following players
                $follow_playerDetails = $modelObj->getFollowingList($user_id, 'player');
                $following_player_array     = array();
                if (isset($follow_playerDetails) && count($follow_playerDetails) > 0)
                {
                        $following_player_array = array_filter(explode(',', $follow_playerDetails[0]->following_list));
                }

                $follow_playerDetails = [];
                $sports = [];
                $sports_array = array();
                if (count($following_player_array) > 0)
                {
                        /*
                         * $followingPlayerDetails = Tournaments::with('photos')
                                ->whereIn('id', $following_team_array)
                                ->get(['id', 'name', 'created_by', 'sports_id', 'type',
                                'final_stage_teams', 'description']);
                         * 
                         */
                        
                        $followingPlayersArray = DB::table('users')
                                ->select('users.*','user_statistics.*')
                                ->join('user_statistics', 'users.id', '=', 'user_statistics.user_id')
                                ->whereIn('users.id', $following_player_array)
                                ->whereNull('users.deleted_at')
                                ->get();
                        
                        $sports = Sport::get();
                        foreach($sports as $sport){
                                $sports_array[$sport->id] = $sport->sports_name;
                        }
                        
                        //print_r($followingPlayersArray);exit;
                        //$followingPlayersArray->toArray();
                }
                

                return view('userprofile.following', array(
                        'followingTournaments' => $followingTournamentsArray,
                        'followingTeams'        => $followingTeamsArray,
                        'followingPlayers'      => $followingPlayersArray,
                        'sports_array'          => $sports_array,
                        'userId'                => $user_id
                ));
        }

}
