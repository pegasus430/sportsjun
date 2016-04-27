<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Model\Photo;
use App\Model\MatchSchedule;
use App\Model\Sport;
use App\Model\Team;
use App\User;
use Request;
use Carbon\Carbon;
use Response;
use Auth;
use App\Helpers\Helper;

class MyScheduleController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        //
    }

    /**
     * Display all the sports configured for the user.
     * User can follow sports. If already followed then the user will be redirected to edit function
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        
    }

    /**
     * Show the form for editing the sports profile.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

    /**
     * Function to show the landing page for my schedules along with filters
     */
    public function showMySchedules($userId) {
//        $currentMonth = Carbon::now()->month;
//        $currentYear = Carbon::now()->year;
//        $userId = Auth::user()->id;
        Helper::setMenuToSelect(3, 6);
        //for suggested teams 
        $managing_teams = Helper::getManagingTeams($userId, '');
        if (count($managing_teams)) {
            $managing_teams = $managing_teams->toArray();
        }
        $selectedSport = '';
        $request = Request::all();
        $sports = Helper::getDevelopedSport(1,1);
        if (count($request)) {
            $selectedSport = $request['sportsId'];
        }

        return view('myschedules.showmyschedules', ['selectedSport' => $selectedSport, 'sportArray' => ['' => 'All'] + $sports, 'userId' => $userId, 'managing_teams' => $managing_teams]);
    }

    /**
     * 
     * Function to build the calendar for logged in user with the schedules 
     * based on sports and schedule type
     */
    public function getMyEvents($userId) {
        $fromDate = Request::get('datefrom');
        $toDate = Request::get('dateto');
        $sportsId = Request::get('sportsId');
        
        $searchArray = ['sportsId'=>$sportsId,'fromDate'=>$fromDate, 'toDate'=>$toDate, 'userId'=>$userId];

//        $matchSchedules = $this->buildMyMatchScheduleData($sportsId, $fromDate, $toDate, '', '');
        $matchSchedules = Helper::buildMyMatchScheduleData($searchArray);
        $events = [];
        if (count($matchSchedules)) {
            foreach ($matchSchedules as $schedule) {
                $title = $this->buildMyEventTitle($schedule);
                $color = '#3a87ad';
                if ($schedule['match_invite_status'] == 'pending') {
                    $color = '#FF6600';
                }
                $events[] = [
                    "title" => $title,
                    "start" => $schedule['match_start_date'] . ' ' . $schedule['match_start_time'],
                    "end" => $schedule['match_end_date'] . ' ' . $schedule['match_end_time'],
                    "id" => $schedule['id'],
                    "color" => $color
                ];
            }
        }
        return Response::json(!empty($events) ? $events : []);
    }

    /**
     * Function to display the tooltip for each schedule
     * 
     */
    public function getMyTooltipContent($userId) {
        $scheduleId = Request::get('id');
        $isOwner = Request::get('isOwner');
//        $userId = Auth::user()->id;
        $matchScheduleType = MatchSchedule::where('id', $scheduleId)->first(['schedule_type']);
        if ($matchScheduleType['schedule_type'] == 'team') {
            $scheduleTypeOne = 'scheduleteamone';
            $scheduleTypeTwo = 'scheduleteamtwo';
        } else {
            $scheduleTypeOne = 'scheduleuserone';
            $scheduleTypeTwo = 'scheduleusertwo';
        }
        $isOwner = 0;
        $scoreOwner = 0;
        $matchScheduleData = MatchSchedule::with(array($scheduleTypeOne => function($q1) {
                $q1->select('id', 'name');
            },
                    $scheduleTypeTwo => function($q2) {
                $q2->select('id', 'name');
            },
                    'sport' => function($q3) {
                $q3->select('id', 'sports_name', 'sports_type');
            },
                    $scheduleTypeOne . '.photos', $scheduleTypeTwo . '.photos'))
                ->where('id', $scheduleId)
				->whereNotNull('match_start_date')
                ->first(['id', 'match_start_date', 'match_start_time', 'match_end_date',
            'match_end_time', 'winner_id', 'a_id', 'b_id', 'sports_id', 'facility_name', 'match_category',
            'match_type', 'match_status', 'match_invite_status', 'schedule_type','scoring_status','score_added_by','created_by']);

        if (count($matchScheduleData)) {
            $matchScheduleData = $matchScheduleData->toArray();
            $managingTeams = Helper::getManagingTeamIds($userId);
            $managingTeamsArray = explode(',', (trim($managingTeams, ',')));
            if ($matchScheduleData['schedule_type'] == 'team') {
//                if (in_array($matchScheduleData['a_id'], $managingTeamsArray) || in_array($matchScheduleData['b_id'], $managingTeamsArray)) {
                    $isOwner = Helper::checkIfOwnerOrManger($matchScheduleData);
            } else {
//                if ($matchScheduleData['a_id'] == $userId || $matchScheduleData['b_id'] == $userId) {
                if ($matchScheduleData['created_by'] == $userId) {
                    $isOwner = 1;
                }
            }

            if (count($matchScheduleData[$scheduleTypeOne]['photos'])) {
                $teamOneUrl = array_collapse($matchScheduleData[$scheduleTypeOne]['photos']);
                $matchScheduleData[$scheduleTypeOne]['url'] = $teamOneUrl['url'];
            } else {
                $matchScheduleData[$scheduleTypeOne]['url'] = '';
            }

            if (count($matchScheduleData[$scheduleTypeTwo]['photos'])) {
                $teamTwoUrl = array_collapse($matchScheduleData[$scheduleTypeTwo]['photos']);
                $matchScheduleData[$scheduleTypeTwo]['url'] = $teamTwoUrl['url'];
            } else {
                $matchScheduleData[$scheduleTypeTwo]['url'] = '';
            }

            $matchStartDate = Carbon::createFromFormat('Y-m-d', $matchScheduleData['match_start_date']);

//            if (!empty($matchScheduleData['winner_id']) && $matchScheduleData['match_status']=='completed') {
            if ($matchScheduleData['match_status']=='completed') {
                if($matchScheduleData['scoring_status']=='approval_pending') {
                    $matchScheduleData['winner_text'] = trans('message.schedule.scorecardapproval');
                }else{
                    $matchScheduleData['winner_text'] = trans('message.schedule.matchstats');
                }
            } else if (Carbon::now()->gte($matchStartDate)) {
                $match_details = MatchSchedule::where('id',$matchScheduleData['id'])->get();
                $scoreOwner = Helper::isValidUserForScoreEnter($match_details->toArray());
                if ($scoreOwner) {
                    if($matchScheduleData['match_invite_status'] == 'accepted') {
//                        $matchScheduleData['winner_text'] = trans('message.schedule.addscore');
                        $matchScheduleData['winner_text'] = Helper::getCurrentScoringStatus($matchScheduleData);
                    }    
                    else if($matchScheduleData['match_invite_status'] == 'pending') {
                        $matchScheduleData['winner_text'] = trans('message.schedule.pending');
                    }    
                    else {
                        $matchScheduleData['winner_text'] = trans('message.schedule.rejected');
                    }    
                }
            } else {
                if ($isOwner) {
                    $matchScheduleData['winner_text'] = trans('message.schedule.edit');
                }
            }
//            $matchScheduleData['match_start_date'] = $matchStartDate->toDayDateTimeString();
            $matchScheduleData['match_start_date'] = Helper::getFormattedTimeStamp($matchScheduleData);
        }
//        dd($matchScheduleData);
        return view('myschedules.tooltip', ['matchScheduleData' => $matchScheduleData, 'scheduleId' => $scheduleId,
            'scheduleTypeOne' => !empty($scheduleTypeOne) ? $scheduleTypeOne : '', 'scheduleTypeTwo' => !empty($scheduleTypeTwo) ? $scheduleTypeTwo : '',]);
    }

    /*
     * Function to display list view schedules for logged in user
     */

    public function getMyListViewEvents($userId) {
        $sportsId = Request::get('sportsId');
        $limit = config('constants.LIMIT');
//        $fromDate = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->subDays(30))->format('Y-m-d');
//        $toDate = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->format('Y-m-d');
        $fromDate = '';
        $toDate = '';
//        $limit = 1;
        $offset = 0;
//        $userId = Auth::user()->id;
        $searchArray = ['sportsId'=>$sportsId,'fromDate'=>$fromDate, 'toDate'=>$toDate, 'limit'=>$limit, 'offset'=>$offset, 'userId'=>$userId];
//        $matchScheduleData = $this->buildMyMatchScheduleData($sportsId, $fromDate, $toDate, $limit, $offset);
        $matchScheduleData = Helper::buildMyMatchScheduleData($searchArray);

        $scheduleDataTotalCount = MatchSchedule::where(function($query) use ($userId) {
                    $query->where('player_a_ids', 'LIKE', '%' . $userId . '%')->orWhere('player_b_ids', 'LIKE', '%' . $userId . '%');
                })->whereNotNull('match_start_date');
        if (!empty($sportsId)) {
            $scheduleDataTotalCount->where('sports_id', $sportsId);
        }
        $matchScheduleDataTotalCount = $scheduleDataTotalCount->count();

        if (!empty($matchScheduleData)) {
            $matchScheduleData = Helper::buildMyListViewData($matchScheduleData, $userId);
        }

        return view('myschedules.listview', ['matchScheduleData' => $matchScheduleData,
            'matchScheduleDataTotalCount' => $matchScheduleDataTotalCount, 'sportsId' => $sportsId, 'userId'=>$userId,
            'limit' => $limit, 'offset' => $limit + $offset]);
    }

    /**
     * Function to display the list view pagination
     * 
     */
    public function viewMoreMyList($userId) {
        $sportsId = Request::get('sportsId');
        $limit = Request::get('limit');
        $offset = Request::get('offset');
        $fromDate = '';
        $toDate = '';
//        $userId = Auth::user()->id;
        $searchArray = ['sportsId'=>$sportsId,'fromDate'=>$fromDate, 'toDate'=>$toDate, 'limit'=>$limit, 'offset'=>$offset, 'userId'=>$userId];
//        $matchScheduleData = $this->buildMyMatchScheduleData($sportsId, $fromDate, $toDate, $limit, $offset);
        $matchScheduleData = Helper::buildMyMatchScheduleData($searchArray);

        if (!empty($matchScheduleData)) {
            $matchScheduleData = Helper::buildMyListViewData($matchScheduleData, $userId);
        }

        return view('myschedules.listmoreview', ['matchScheduleData' => $matchScheduleData,'userId'=>$userId,
            'sportsId' => $sportsId, 'limit' => $limit, 'offset' => $limit + $offset]);
    }
    
    function buildMyEventTitle($schedule) {

        $a_name = $schedule['a_name'];
        $b_name = $schedule['b_name'];
        if (isset($a_name) && isset($b_name))
            $title = $a_name . ' VS ' . $b_name;
        else if (isset($a_name))
            $title = $a_name;
        else if (isset($b_name))
            $title = $b_name;

        return $title;
    }

    /*function buildMyMatchScheduleData($sportsId, $fromDate, $toDate, $limit, $offset) {
        $teamIds = '';
        $playerIds = '';
        $teamLogoArray = [];
        $playerLogoArray = [];
        $teamNameArray = [];
        $playerNameArray = [];
        $userId = Auth::user()->id;
        $matchSchedules = MatchSchedule::with(array('sport' => function($q3) {
                $q3->select('id', 'sports_name', 'sports_type');
            }))->where(function($query) use ($userId) {
            $query->where('player_a_ids', 'LIKE', '%' . $userId . '%')->orWhere('player_b_ids', 'LIKE', '%' . $userId . '%');
        });
        if (!empty($fromDate) && !empty($toDate)) {
            $matchSchedules->whereBetween('match_start_date', [$fromDate, $toDate]);
        }
        if (!empty($sportsId)) {
            $matchSchedules->where('sports_id', $sportsId);
        }
        if (!empty($limit)) {
            $matchSchedules->orderby('match_start_date', 'desc');
            $matchSchedules->limit($limit)->offset($offset);
        }
        $matchScheduleData = $matchSchedules->get(['id', 'match_start_date', 'match_start_time', 'match_end_date',
            'match_end_time', 'winner_id', 'a_id', 'b_id', 'sports_id', 'facility_name', 'match_category',
            'match_type', 'match_status', 'match_invite_status', 'schedule_type']);


        if (count($matchScheduleData)) {
            $matchScheduleData = $matchScheduleData->toArray();
            foreach ($matchScheduleData as $key => $schedule) {
                $matchScheduleData[$key]['a_logo'] = '';
                $matchScheduleData[$key]['b_logo'] = '';
                $matchScheduleData[$key]['a_name'] = '';
                $matchScheduleData[$key]['b_name'] = '';
                if ($schedule['schedule_type'] == 'team') {
                    $teamIds.=$schedule['a_id'] . ',' . $schedule['b_id'] . ',';
                } else {
                    $playerIds.=$schedule['a_id'] . ',' . $schedule['b_id'] . ',';
                }
            }
        }

        if (!empty($teamIds)) {
            $teamIdArray = explode(',', rtrim($teamIds, ','));
            $teamLogos = Photo::whereIn('imageable_id', $teamIdArray)
                    ->where('imageable_type', config('constants.PHOTO.TEAM_PHOTO'))
                    ->where('is_album_cover', 1)
                    ->get(['url', 'imageable_id']);
            $teamNames = Team::whereIn('id', $teamIdArray)->get(['id', 'team_owner_id', 'sports_id', 'name']);

            if (count($teamLogos)) {
                foreach ($teamLogos->toArray() as $teamkey => $teamLogo) {
                    $teamLogoArray[$teamLogo['imageable_id']] = $teamLogo['url'];
                }
            }

            if (count($teamNames)) {
                foreach ($teamNames as $teamName) {
                    $teamNameArray[$teamName['id']] = $teamName['name'];
                }
            }
        }

        if (!empty($playerIds)) {
            $playerIdArray = explode(',', rtrim($playerIds, ','));
            $playerLogos = Photo::whereIn('user_id', $playerIdArray)
                    ->where('imageable_type', config('constants.PHOTO.USER_PHOTO'))
                    ->where('is_album_cover', 1)
                    ->get(['url', 'imageable_id', 'user_id']);

            $playerNames = User::whereIn('id', $playerIdArray)->get(['id', 'name']);

            if (count($playerLogos)) {
                foreach ($playerLogos->toArray() as $playerLogo) {
                    $playerLogoArray[$playerLogo['user_id']] = $playerLogo['url'];
                }
            }

            if (count($playerNames)) {
                foreach ($playerNames as $playerName) {
                    $playerNameArray[$playerName['id']] = $playerName['name'];
                }
            }
        }

        if (!empty($matchScheduleData)) {
            foreach ($matchScheduleData as $key => $schedule) {
                if ($schedule['schedule_type'] == 'team') {
                    if (!empty($teamLogoArray)) {
                        if (array_key_exists($matchScheduleData[$key]['a_id'], $teamLogoArray)) {
                            $matchScheduleData[$key]['a_logo'] = $teamLogoArray[$matchScheduleData[$key]['a_id']];
                        }
                        if (array_key_exists($matchScheduleData[$key]['b_id'], $teamLogoArray)) {
                            $matchScheduleData[$key]['b_logo'] = $teamLogoArray[$matchScheduleData[$key]['b_id']];
                        }
                    }
                    if (!empty($teamNameArray)) {
                        if (array_key_exists($matchScheduleData[$key]['a_id'], $teamNameArray)) {
                            $matchScheduleData[$key]['a_name'] = $teamNameArray[$matchScheduleData[$key]['a_id']];
                        }
                        if (array_key_exists($matchScheduleData[$key]['b_id'], $teamNameArray)) {
                            $matchScheduleData[$key]['b_name'] = $teamNameArray[$matchScheduleData[$key]['b_id']];
                        }
                    }
                } else {
                    if (!empty($playerLogoArray)) {
                        if (array_key_exists($matchScheduleData[$key]['a_id'], $playerLogoArray)) {
                            $matchScheduleData[$key]['a_logo'] = $playerLogoArray[$matchScheduleData[$key]['a_id']];
                        }
                        if (array_key_exists($matchScheduleData[$key]['b_id'], $teamLogoArray)) {
                            $matchScheduleData[$key]['b_logo'] = $playerLogoArray[$matchScheduleData[$key]['b_id']];
                        }
                    }
                    if (!empty($playerNameArray)) {
                        if (array_key_exists($matchScheduleData[$key]['a_id'], $playerNameArray)) {
                            $matchScheduleData[$key]['a_name'] = $playerNameArray[$matchScheduleData[$key]['a_id']];
                        }
                        if (array_key_exists($matchScheduleData[$key]['b_id'], $playerNameArray)) {
                            $matchScheduleData[$key]['b_name'] = $playerNameArray[$matchScheduleData[$key]['b_id']];
                        }
                    }
                }
            }
        }
//                  dd($matchScheduleData);
        return $matchScheduleData;

//        if(!empty($sportsId)) {
//            $sportDetails = Sport::where('id',$sportsId)->first(['sports_name','sports_type']);
//            if(count($sportDetails)) {
//                $sportName = $sportDetails->sports_name;
//                $sportType = $sportDetails->sports_type;
//                switch ($sportsId) {
//                    case config('constants.SPORT_ID.Cricket'):
//                        $constraint = function($query) use ($sportsId) {
//                            $query->where('schedule_type', 'team')
//                                  ->where('sports_id', $sportsId);
//                        };
//                        break;
//                    case config('constants.SPORT_ID.Tennis'):
//                        $constraint = function($query) use ($sportsId) {
//                            $query->where('sports_id', $sportsId);
//                        };
//                        break;
//                    case config('constants.SPORT_ID.Table Tennis'):
//                        $constraint = function($query) use ($sportsId) {
//                            $query->where('sports_id', $sportsId);
//                        };
//                        break;
//                    case config('constants.SPORT_ID.Soccer'):
//                        $constraint = function($query) use ($sportsId) {
//                            $query->where('schedule_type', 'team')
//                                  ->where('sports_id', $sportsId);
//                        };
//                        break;
//                    default:
//                        $constraint = function($query) use ($sportsId) {
//                            $query->where('sports_id', $sportsId);
//                        };
//                }  
//            }
//        }
//        if(is_null($sportType) || $sportType='team' || $sportType='both') {
//                if(empty($sportsId)) {
//                    $constraint = function($query){
//                            $query->where('schedule_type', 'team');
//                        };
//                }
//                $matchSchedulesTeam = MatchSchedule::with(array('scheduleteamone' => function($q1) {
//                            $q1->select('id', 'name');
//                        },
//                            'scheduleteamtwo' => function($q2) {
//                            $q2->select('id', 'name');
//                        },
//                            'sport' => function($q3) {
//                            $q3->select('id', 'sports_name','sports_type');
//                        },    
//                        'scheduleteamone.photos', 'scheduleteamtwo.photos' 
//                        ))
//                        ->where(function($query) use ($userId) {
//                            $query->where('player_a_ids','LIKE','%'.$userId.'%')->orWhere('player_b_ids', 'LIKE','%'.$userId.'%');
//                        })
//                        ->where($constraint)
//                        ->whereBetween('match_start_date', [$fromDate, $toDate])
//                        ->get(['id', 'match_start_date', 'match_start_time', 'match_end_date', 'match_end_time',
//                                'winner_id', 'a_id', 'b_id','sports_id','facility_name','match_category',
//                                'match_type','match_status','match_invite_status','schedule_type']);
//                if(count($matchSchedulesTeam)) {
//                    $matchSchedulesTeamArray = $matchSchedulesTeam->toArray();
//                }
//                        
//        } 
//        if(is_null($sportType) || $sportType='player' || $sportType='both') {
//                if(empty($sportsId)) {
//                        $constraint = function($query){
//                                $query->where('schedule_type', 'player');
//                            };
//                }
//                $matchSchedulesIndividual = MatchSchedule::with(array('scheduleuserone' => function($q1) {
//                            $q1->select('id', 'name');
//                        },
//                            'scheduleusertwo' => function($q2) {
//                            $q2->select('id', 'name');
//                        },
//                           'sport' => function($q3) {
//                            $q3->select('id', 'sports_name','sports_type');
//                        },
//                        'scheduleuserone.photos', 'scheduleusertwo.photos'))->where(function($query) use ($userId) {
//                            $query->where('a_id', $userId)->orWhere('b_id', $userId);
//                        })
//                        ->where($constraint)
//                        ->whereBetween('match_start_date', [$fromDate, $toDate])
//                        ->get(['id', 'match_start_date', 'match_start_time', 'match_end_date', 'match_end_time',
//                                'winner_id', 'a_id', 'b_id','sports_id','facility_name','match_category',
//                                'match_type','match_status','match_invite_status','schedule_type']);   
//                if(count($matchSchedulesIndividual)) {
//                    $matchSchedulesIndividualArray = $matchSchedulesIndividual->toArray();
//                }
//        }
//        $resultArray = array_merge($matchSchedulesTeamArray,$matchSchedulesIndividualArray);
//        echo '<pre>';
//        Helper::printQueries();
//        print_r($matchSchedulesTeamArray);
//        print_r($matchSchedulesIndividualArray);
//        print_r($resultArray);
    }*/


    /*function buildMyListViewData($matchScheduleData, $userId) {
        $managingTeams = Helper::getManagingTeamIds($userId);
        $managingTeamsArray = explode(',', (trim($managingTeams, ',')));
        $isOwner = 0;
        foreach ($matchScheduleData as $key => $schedule) {
            if ($schedule['schedule_type'] == 'team') {
                if (in_array($schedule['a_id'], $managingTeamsArray) || in_array($schedule['b_id'], $managingTeamsArray)) {
                    $isOwner = 1;
                }
            } else {
                if ($schedule['a_id'] == $userId || $schedule['b_id'] == $userId) {
                    $isOwner = 1;
                }
            }
            $matchStartDate = Carbon::createFromFormat('Y-m-d', $schedule['match_start_date']);

            if (!empty($schedule['winner_id'])) {
                $matchScheduleData[$key]['winner_text'] = trans('message.schedule.matchstats');
            } else if (Carbon::now()->gte($matchStartDate) && $schedule['match_invite_status'] == 'accepted') {
                if ($isOwner) {
                    $matchScheduleData[$key]['winner_text'] = trans('message.schedule.addscore');
                }
            } else {
                if ($isOwner) {
                    $matchScheduleData[$key]['winner_text'] = trans('message.schedule.edit');
                }
            }
            $matchScheduleData[$key]['match_start_date'] = $matchStartDate->toFormattedDateString();
            $isOwner = 0;
        }
        return $matchScheduleData;
    }*/

}
