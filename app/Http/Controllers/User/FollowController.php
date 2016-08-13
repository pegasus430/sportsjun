<?php

namespace App\Http\Controllers\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Model\Followers;
use App\Model\MatchSchedule;
use App\Model\Photo;
use App\Model\Sport;
use App\Model\Team;
use App\Model\TournamentGroups;
use App\Model\TournamentGroupTeams;
use App\Model\Tournaments;
use App\Model\Organization;
use App\User;
use Auth;
use DB;
use PDO;
use Response;

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
                $self_user_id = Auth::user()->id;
                if ($id == '')
                        $user_id = $self_user_id;
                else
                        $user_id = $id;
                
                
                $selfProfile = ($user_id != $self_user_id) ? false : true;
                        

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
                    $followingTournamentDetails =
                        Tournaments::with('photos', 'tournamentParent')
                                   ->whereIn('id', $following_team_array)
                                   ->get([
                                       'id',
                                       'name',
                                       'created_by',
                                       'sports_id',
                                       'type',
                                       'tournament_parent_id',
                                       'final_stage_teams',
                                       'description',
                                       'end_date',
                                       'schedule_type',
                                   ]);
                }
                
                $existing_tournament_ids = [];
                if (count($followingTournamentDetails))
                {
                        foreach ($followingTournamentDetails->toArray() as $followKey => $followedTournament)
                        {
                                $followingTournamentDetails[$followKey]['sports_id'] = $followedTournament['sports_id'];
                                $followingTournamentDetails[$followKey]['end_date'] = $followedTournament['end_date'];
                                $followingTournamentDetails[$followKey]['schedule_type'] = $followedTournament['schedule_type'];
                                
                                $sportsName = Sport::where('id', $followedTournament['sports_id'])->first(['sports_name']);
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
                                    $photoUrl        = $followingTournamentDetails[$followKey];
                                    $followingTournamentDetails[$followKey]['url'] = $photoUrl['url'];
                                }
                                else
                                {
                                        //                    $matchScheduleData[$key]['scheduleteamone']['url'] = '';
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
                        
                        $tournament_ids = implode(',',array_column($followingTournamentsArray, 'id'));
                        
                        if (!empty($tournament_ids))
                        {
                                DB::setFetchMode(PDO::FETCH_ASSOC);
                                $existing_tournament_ids = DB::select("SELECT DISTINCT t.id as item
                                FROM `tournaments` t
                                INNER JOIN `tournament_final_teams` f ON f.tournament_id = t.id AND f.team_id = $self_user_id
                                WHERE t.schedule_type = 'individual' AND t.id IN ($tournament_ids) AND t.type != 'league'  
                                UNION ALL
                                SELECT DISTINCT t.id 
                                FROM `tournaments` t
                                INNER JOIN `tournament_group_teams` g ON g.tournament_id = t.id AND g.team_id = $self_user_id
                                WHERE t.schedule_type = 'individual' AND t.id IN ($tournament_ids) AND t.type != 'knockout' ");
                                DB::setFetchMode(PDO::FETCH_CLASS);
                                $existing_tournament_ids = array_column($existing_tournament_ids, 'team');
                        }
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
                $team_ids = array_column($followingTeamsArray, 'id');
                
                // check if user already exists in team
                $existing_team_ids = [];
                $player_available_in_teams = [];
                
                $team_ids_csv = implode(',', $team_ids);
                if (!empty($team_ids_csv))
                {
                        DB::setFetchMode(PDO::FETCH_ASSOC);
                        $existing_team_ids = DB::select("SELECT DISTINCT tp.team_id
                                        FROM `team_players` tp  
                                        WHERE tp.user_id = $self_user_id "
                                        . "AND tp.team_id IN ($team_ids_csv) "
                                        . "AND `status` != 'rejected' "
                                        . "AND tp.deleted_at IS NULL ");
                        DB::setFetchMode(PDO::FETCH_CLASS);
                }
                foreach ($followingTeamsArray as $row)
                {
                        $player_available_in_teams[$row['id']] = $row['player_available'];
                }
                
                // get following players
                $follow_playerDetails = $modelObj->getFollowingList($user_id, 'player');
                $following_player_array     = [];
                if (isset($follow_playerDetails) && count($follow_playerDetails) > 0)
                {
                        $following_player_array = array_filter(explode(',', $follow_playerDetails[0]->following_list));
                }

                $sports_array = $follow_array = $sports = [];
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
                                ->where('users.id', '!=' , $self_user_id)
                                ->whereIn('users.id', $following_player_array)
                                ->whereNull('users.deleted_at')
                                ->get();

                        $sports = Sport::get();
                        foreach($sports as $sport){
                                $sports_array[$sport->id] = $sport->sports_name;
                        }
                        
                        // data for performing checks of user following
                        $checkArray = "";
                        foreach($followingPlayersArray as $player){
                                        $checkArray.= $player->user_id.",";
                        }
                        $checkArray = trim($checkArray,",");
                        if (!empty($checkArray))
                        {
                                DB::setFetchMode(PDO::FETCH_ASSOC);
                                $follow_array = DB::select("SELECT DISTINCT tp.type_id as item
                                FROM `followers` tp  
                                WHERE tp.user_id = $user_id "
                                . "AND tp.type_id IN ($checkArray) "
                                . "AND `type` = 'player' AND tp.deleted_at IS NULL ");
                                DB::setFetchMode(PDO::FETCH_CLASS);
                        }
                        if (!empty($follow_array))
                        {
                                $follow_array = array_column($follow_array, 'item');
                        }
                        
                        //print_r($follow_array);exit;
                        //$followingPlayersArray->toArray();
                }

                //getorganization

                 $follow_organizationDetails = $modelObj->getFollowingList($user_id, 'organization');
                $following_organization_array     = [];
                if (isset($follow_organizationDetails) && count($follow_organizationDetails) > 0)
                {
                        $following_organization_array = array_filter(explode(',', $follow_organizationDetails[0]->following_list));
                }



                $sports_array = $follow_array = $sports = [];
                $followingOrganizationsArray=[];
                if (count($following_organization_array) > 0)
                {
                        /*
                         * $followingPlayerDetails = Tournaments::with('photos')
                                ->whereIn('id', $following_team_array)
                                ->get(['id', 'name', 'created_by', 'sports_id', 'type',
                                'final_stage_teams', 'description']);
                         * 
                         */
                        
                        $followingOrganizationsArray = Organization::
                                //where('organization.user_id', '!=' , $self_user_id)
                                whereIn('organization.id', $following_organization_array)
                                ->whereNull('organization.deleted_at')
                                ->get();
                        
                        // data for performing checks of user following
                        $checkArray = "";
                        foreach($followingOrganizationsArray as $player){
                                        $checkArray.= $player->user_id.",";
                        }
                        $checkArray = trim($checkArray,",");
                        if (!empty($checkArray))
                        {
                                DB::setFetchMode(PDO::FETCH_ASSOC);
                                $follow_array = DB::select("SELECT DISTINCT tp.type_id as item
                                FROM `followers` tp  
                                WHERE tp.user_id = $user_id "
                                . "AND tp.type_id IN ($checkArray) "
                                . "AND `type` = 'organization' AND tp.deleted_at IS NULL ");
                                DB::setFetchMode(PDO::FETCH_CLASS);
                        }
                        if (!empty($follow_array))
                        {
                                $follow_array = array_column($follow_array, 'item');
                        }
                        
                        //print_r($follow_array);exit;
                        //$followingPlayersArray->toArray();
                }


                return view('userprofile.following', array(
                        'followingTournaments' => $followingTournamentsArray,
                        'followingTeams'        => $followingTeamsArray,
                        'followingPlayers'      => $followingPlayersArray,
                        'followingOrganizations' => $followingOrganizationsArray,
                        'sports_array'          => $sports_array,
                        'userId'                => $user_id,
                        'follow_array'          => $follow_array,
                        'selfProfile'          => $selfProfile,
                        'self_user_id'         => $self_user_id,
                        'existing_team_ids'    => $existing_team_ids,
                        'player_available_in_teams' => $player_available_in_teams,
                        'existing_tournament_ids'    => $existing_tournament_ids
                ));
        }

}
