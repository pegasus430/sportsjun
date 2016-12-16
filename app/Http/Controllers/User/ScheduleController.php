<?php

 namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Model\MatchSchedule;
use App\Model\UserStatistic;
use App\Model\State;
use App\Model\City;
use App\Model\Team;
use App\Model\TeamPlayers;
use App\Model\Sport;
use App\Model\Facilityprofile;
use App\Model\Country;
use App\Model\TournamentGroupTeams;
use App\Model\Tournaments;
use App\Model\MatchScheduleRubber;
use App\User;
use DB;
use Request;
use Carbon\Carbon;
use Response;
use Auth;
use App\Helpers\Helper;
use DateTime;
use App\Helpers\AllRequests;
use PDO;

class ScheduleController extends Controller {

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

    public function showSchedules($teamId,$sportsId) {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $nextYears = Carbon::now()->addYears(2);
        $prevYears =  Carbon::now()->subYears(5);
        $request = Request::all();

        if (count($request)) {
            $currentMonth = $request['month'];
            $currentYear = $request['year'];
            $isOwner = $request['isOwner'];
        }
        if (!count($request)) {
            $isOwner = 0;
            if (Helper::isTeamOwnerorcaptain($teamId,Auth::user()->id)) {
                $isOwner = 1;
            }
        }
        // Helper::setMenuToSelect(2,5);
        Helper::leftMenuVariables($teamId);
        $teamdetails = Team::where('id', $teamId)->first();
        $countries = Country::orderBy('country_name')->lists('country_name', 'id')->all();
        $states = [];
        $match_types = array();
        $player_types = array();
        //get sport name
        $sport_name = Sport::where('id', $sportsId)->pluck('sports_name');
        if ($isOwner) {
            //building match types array
            $sport_name = !empty($sport_name) ? $sport_name : '';
            $match_types = Helper::getMatchTypes(strtoupper($sport_name));
            //building player types array
            foreach (config('constants.ENUM.SCHEDULE.PLAYER_TYPE') as $key => $val) {
                $player_types[$key] = $val;
            }
        }
        $sport_type = Sport::where('id', $sportsId)->pluck('sports_type');
        $team_name_level = (!empty($teamdetails['name']) && !empty($teamdetails['team_level'])) ? ($teamdetails['name'].' ('.$teamdetails['team_level'].')') : 'NA';

        return view('schedules.showschedules', ['currentMonth' => $currentMonth, 'currentYear' => $currentYear, 'sportsId' => $sportsId, 
                    'teamId' => $teamId, 'isOwner' => $isOwner, 'team_name' => $team_name_level, 
                    'sport_type' => $sport_type, 'nextyears'=>$nextYears, 'prevYears'=>$prevYears])
                        ->with('match_types', ['' => 'Select Match Type'] + $match_types)
                        ->with('player_types', ['' => 'Select Player Type'] + $player_types)
                        ->with('countries', ['' => 'Select Country'] + $countries)
                        ->with('states', ['' => 'Select State'] + $states)
                        ->with('cities', ['' => 'Select City'] + array());
    }

    public function getEvents() {
        $fromDate = Request::get('datefrom');
        $toDate = Request::get('dateto');
        $sportsId = Request::get('sportsId');
        $teamId = Request::get('teamId');

        $matchSchedules = $this->buildMatchScheduleData($sportsId, $teamId, $fromDate, $toDate);
        $events = [];
        if (count($matchSchedules)) {
            foreach ($matchSchedules->toArray() as $schedule) {
                $title = $this->buildEventTitle($schedule);
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

    public function getTooltipContent() {
        $scheduleId = Request::get('id');
        $isOwner = Request::get('isOwner');
        $scoreOwner = 0;
        $matchScheduleData = MatchSchedule::with(array('scheduleteamone' => function($q1) {
                $q1->select('id', 'name');
            },
                    'scheduleteamtwo' => function($q2) {
                $q2->select('id', 'name');
            }, 
                    'sport' => function($q3) {
                $q3->select('id','sports_name');
            },
                    'scheduleteamone.photos', 'scheduleteamtwo.photos'))
            ->where('id', $scheduleId)->whereNotNull('match_start_date')
			->first(['a_id', 'b_id', 'match_start_date', 'match_start_time', 'winner_id',
                                                'match_invite_status', 'match_status', 'scoring_status','score_added_by','sports_id','match_type']);

        if (count($matchScheduleData)) {
            $matchScheduleData = $matchScheduleData->toArray();
//            $teamOnePhoto = Photo::where(['imageable_id' => $matchScheduleData['scheduleteamone']['id'], 'is_album_cover' => 1, 'imageable_type' => config('constants.PHOTO.TEAM_PHOTO')])->get(['url']);
//            if (count($teamOnePhoto)) {
//                $teamOneUrl = array_collapse($teamOnePhoto->toArray());
//                $matchScheduleData['scheduleteamone']['url'] = $teamOneUrl['url'];
//            }
//            $teamTwoPhoto = Photo::where(['imageable_id' => $matchScheduleData['scheduleteamtwo']['id'], 'is_album_cover' => 1, 'imageable_type' => config('constants.PHOTO.TEAM_PHOTO')])->get(['url']);
//            if (count($teamTwoPhoto)) {
//                $teamTwoUrl = array_collapse($teamTwoPhoto->toArray());
//                $matchScheduleData['scheduleteamtwo']['url'] = $teamTwoUrl['url'];
//            }

            if (count($matchScheduleData['scheduleteamone']['photos'])) {
                $teamOneUrl = array_collapse($matchScheduleData['scheduleteamone']['photos']);
                $matchScheduleData['scheduleteamone']['url'] = $teamOneUrl['url'];
            } else {
                $matchScheduleData['scheduleteamone']['url'] = '';
            }

            if (count($matchScheduleData['scheduleteamtwo']['photos'])) {
                $teamTwoUrl = array_collapse($matchScheduleData['scheduleteamtwo']['photos']);
                $matchScheduleData['scheduleteamtwo']['url'] = $teamTwoUrl['url'];
            } else {
                $matchScheduleData['scheduleteamtwo']['url'] = '';
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
                $match_details = MatchSchedule::where('id',$scheduleId)->get();
                $scoreOwner = Helper::isValidUserForScoreEnter($match_details->toArray());
                if ($scoreOwner) {
                    if($matchScheduleData['match_invite_status'] == 'accepted') {
//                        $matchScheduleData['winner_text'] = trans('message.schedule.addscore');
                        $matchScheduleData['winner_text'] = Helper::getCurrentScoringStatus($matchScheduleData);
                    }    
                    else if ($matchScheduleData['match_invite_status'] == 'pending')
                        $matchScheduleData['winner_text'] = trans('message.schedule.pending');
                    else 
                        $matchScheduleData['winner_text'] = trans('message.schedule.rejected');
                }
            }
            // $matchScheduleData['match_start_date'] = $matchStartDate->toFormattedDateString();
            $matchScheduleData['match_start_date'] = Helper::getFormattedTimeStamp($matchScheduleData);
			
        }

        return view('schedules.tooltip', ['matchScheduleData' => $matchScheduleData, 'scheduleId' => $scheduleId, 'isOwner' => $isOwner]);
    }

    public function getListViewEvents() {
        $month = Request::get('month');
        $year = Request::get('year');
        $sportsId = Request::get('sportsId');
        $teamId = Request::get('teamId');
        $isOwner = Request::get('isOwner');
        $limit = config('constants.LIMIT');
//        $limit = 1;
        $offset = 0;
        $scoreOwner=0;

//        $dateObj   = \DateTime::createFromFormat('m', $month);
//        $monthName = $dateObj->format('F');
        //Converting the month integer to month name
        $monthName = Carbon::createFromFormat('m', $month)->format('F');
        //Getting the first and last day of the month
        $firstDay = new Carbon('first day of ' . $monthName . ' ' . $year . '');
        $lastDay = new Carbon('last day of ' . $monthName . ' ' . $year . '');
        // Converting the date to db format
        $fromDate = Carbon::createFromFormat('Y-m-d H:i:s', $firstDay)->format('Y-m-d');
        $toDate = Carbon::createFromFormat('Y-m-d H:i:s', $lastDay)->format('Y-m-d');

//        $matchScheduleData = $this->buildMatchScheduleData($sportsId, $teamId, $fromDate, $toDate);
        $matchScheduleData = MatchSchedule::with(array('scheduleteamone' => function($q1) {
                $q1->select('id', 'name');
            },
                    'scheduleteamtwo' => function($q2) {
                $q2->select('id', 'name');
            }, 
                 'sport' => function($q3) {
                $q3->select('id','sports_name');
            },   
                    'scheduleteamone.photos', 'scheduleteamtwo.photos'))->where(function($query) use ($teamId) {
                    $query->where('a_id', $teamId)->orWhere('b_id', $teamId);
                })->where('schedule_type', 'team')->where('sports_id', $sportsId)
                ->whereBetween('match_start_date', [$fromDate, $toDate])
				->whereNotNull('match_start_date')
                ->orderby('match_start_date', 'desc')
                ->orderby('match_start_time', 'desc')        
                ->limit($limit)->offset($offset)
                ->get(['id', 'match_start_date','match_start_time','winner_id', 'a_id', 'b_id', 'match_invite_status', 'match_status','scoring_status','score_added_by','match_type','sports_id']);
                
        $matchScheduleDataTotalCount = MatchSchedule::where(function($query) use ($teamId) {
                            $query->where('a_id', $teamId)->orWhere('b_id', $teamId);
                        })->where('schedule_type', 'team')->where('sports_id', $sportsId)
                        ->whereBetween('match_start_date', [$fromDate, $toDate])->whereNotNull('match_start_date')->count();

        if (count($matchScheduleData)) {
            foreach ($matchScheduleData->toArray() as $key => $schedule) {
//                $teamOnePhoto = Photo::where(['imageable_id' => $schedule['scheduleteamone']['id'], 'is_album_cover' => 1, 'imageable_type' => config('constants.PHOTO.TEAM_PHOTO')])->get(['url']);
//                if (count($teamOnePhoto)) {
//                    $teamOneUrl = array_collapse($teamOnePhoto->toArray());
//                    $matchScheduleData[$key]['scheduleteamone']['url'] = $teamOneUrl['url'];
//                }
//                $teamTwoPhoto = Photo::where(['imageable_id' => $schedule['scheduleteamtwo']['id'], 'is_album_cover' => 1, 'imageable_type' => config('constants.PHOTO.TEAM_PHOTO')])->get(['url']);
//                if (count($teamTwoPhoto)) {
//                    $teamTwoUrl = array_collapse($teamTwoPhoto->toArray());
//                    $matchScheduleData[$key]['scheduleteamtwo']['url'] = $teamTwoUrl['url'];
//                }
                if (count($schedule['scheduleteamone']['photos'])) {
                    $teamOneUrl = array_collapse($schedule['scheduleteamone']['photos']);
                    $matchScheduleData[$key]['scheduleteamone']['url'] = $teamOneUrl['url'];
                } else {
//                    $matchScheduleData[$key]['scheduleteamone']['url'] = '';
                    $teamOneUrl = $matchScheduleData[$key]['scheduleteamone'];
                    $teamOneUrl['url'] = '';
                }

                if (count($schedule['scheduleteamtwo']['photos'])) {
                    $teamTwoUrl = array_collapse($schedule['scheduleteamtwo']['photos']);
                    $matchScheduleData[$key]['scheduleteamtwo']['url'] = $teamTwoUrl['url'];
                } else {
//                $matchScheduleData[$key]['scheduleteamtwo']['url'] = '';
                    $teamTwoUrl = $matchScheduleData[$key]['scheduleteamtwo'];
                    $teamTwoUrl['url'] = '';
                }

                $matchStartDate = Carbon::createFromFormat('Y-m-d', $schedule['match_start_date']);

                if ($schedule['match_status']=='completed') {
                    if($schedule['scoring_status']=='approval_pending') {
                        $matchScheduleData[$key]['winner_text'] = trans('message.schedule.scorecardapproval');
                    }else{
                        $matchScheduleData[$key]['winner_text'] = trans('message.schedule.matchstats');
                    }    
                } else if (Carbon::now()->gte($matchStartDate)) {
                     $match_details = MatchSchedule::where('id',$schedule['id'])->get();
                     $scoreOwner = Helper::isValidUserForScoreEnter($match_details->toArray());
                    if ($scoreOwner) {
                        if($schedule['match_invite_status'] == 'accepted') {
//                            $matchScheduleData[$key]['winner_text'] = trans('message.schedule.addscore');
                            $matchScheduleData[$key]['winner_text'] = Helper::getCurrentScoringStatus($schedule);
                        }    
                        else if($schedule['match_invite_status'] == 'pending') {
                            $matchScheduleData[$key]['winner_text'] = trans('message.schedule.pending');
                        }    
                        else {
                            $matchScheduleData[$key]['winner_text'] = trans('message.schedule.rejected');
                        }    
                       
                    }
                }
                // $matchScheduleData[$key]['match_start_date'] = $matchStartDate->toFormattedDateString();
				$matchScheduleData[$key]['match_start_date'] = Helper::getFormattedTimeStamp($schedule);
//                $matchScheduleData[$key]['match_start_date'] = Carbon::createFromTimeStamp(strtotime($schedule['match_start_date']))->diffForHumans();
            }
        }
        Helper::leftMenuVariables($teamId);
        return view('schedules.listview', ['matchScheduleData' => $matchScheduleData,
            'matchScheduleDataTotalCount' => $matchScheduleDataTotalCount, 'teamId' => $teamId,
            'sportsId' => $sportsId, 'isOwner' => $isOwner,
            'limit' => $limit, 'offset' => $limit + $offset]);
    }

    public function viewMoreList() {
        $month = Request::get('month');
        $year = Request::get('year');
        $sportsId = Request::get('sportsId');
        $teamId = Request::get('teamId');
        $isOwner = Request::get('isOwner');
        $limit = Request::get('limit');
        $offset = Request::get('offset');
        $scoreOwner = 0;
        
        $monthName = Carbon::createFromFormat('m', $month)->format('F');
        //Getting the first and last day of the month
        $firstDay = new Carbon('first day of ' . $monthName . ' ' . $year . '');
        $lastDay = new Carbon('last day of ' . $monthName . ' ' . $year . '');
        // Converting the date to db format
        $fromDate = Carbon::createFromFormat('Y-m-d H:i:s', $firstDay)->format('Y-m-d');
        $toDate = Carbon::createFromFormat('Y-m-d H:i:s', $lastDay)->format('Y-m-d');

//        $matchScheduleData = $this->buildMatchScheduleData($sportsId, $teamId, $fromDate, $toDate);
        $matchScheduleData = MatchSchedule::with(array('scheduleteamone' => function($q1) {
                $q1->select('id', 'name');
            },
                    'scheduleteamtwo' => function($q2) {
                $q2->select('id', 'name');
            },
                    'sport' => function($q3) {
                $q3->select('id','sports_name');
            },'scheduleteamone.photos', 'scheduleteamtwo.photos'))->where(function($query) use ($teamId) {
                    $query->where('a_id', $teamId)->orWhere('b_id', $teamId);
                })->where('schedule_type', 'team')->where('sports_id', $sportsId)
                ->whereBetween('match_start_date', [$fromDate, $toDate])
				->whereNotNull('match_start_date')
                ->orderby('match_start_date', 'desc')
                ->orderby('match_start_time', 'desc')       
                ->limit($limit)->offset($offset)
                ->get(['id', 'match_start_date','match_start_time','winner_id', 'a_id', 'b_id', 'match_invite_status', 'match_status','scoring_status','score_added_by','match_type','sports_id']);

        if (count($matchScheduleData)) {
            foreach ($matchScheduleData->toArray() as $key => $schedule) {
                if (count($schedule['scheduleteamone']['photos'])) {
                    $teamOneUrl = array_collapse($schedule['scheduleteamone']['photos']);
                    $matchScheduleData[$key]['scheduleteamone']['url'] = $teamOneUrl['url'];
                } else {
                    $teamOneUrl = $matchScheduleData[$key]['scheduleteamone'];
                    $teamOneUrl['url'] = '';
                }

                if (count($schedule['scheduleteamtwo']['photos'])) {
                    $teamTwoUrl = array_collapse($schedule['scheduleteamtwo']['photos']);
                    $matchScheduleData[$key]['scheduleteamtwo']['url'] = $teamTwoUrl['url'];
                } else {
                    $teamTwoUrl = $matchScheduleData[$key]['scheduleteamtwo'];
                    $teamTwoUrl['url'] = '';
                }

                $matchStartDate = Carbon::createFromFormat('Y-m-d', $schedule['match_start_date']);

                if ($schedule['match_status']=='completed') {
                    if($schedule['scoring_status']=='approval_pending') {
                        $matchScheduleData[$key]['winner_text'] = trans('message.schedule.scorecardapproval');
                    }else{
                        $matchScheduleData[$key]['winner_text'] = trans('message.schedule.matchstats');
                    }    
                } else if (Carbon::now()->gte($matchStartDate)) {
                    $match_details = MatchSchedule::where('id',$schedule['id'])->get();
                    $scoreOwner = Helper::isValidUserForScoreEnter($match_details->toArray());
                    if ($scoreOwner) {
                        if($schedule['match_invite_status'] == 'accepted') {
//                            $matchScheduleData[$key]['winner_text'] = trans('message.schedule.addscore');
                            $matchScheduleData[$key]['winner_text'] = Helper::getCurrentScoringStatus($schedule);
                        }    
                        else if($schedule['match_invite_status'] == 'pending') {
                            $matchScheduleData[$key]['winner_text'] = trans('message.schedule.pending');
                        }    
                        else {
                            $matchScheduleData[$key]['winner_text'] = trans('message.schedule.rejected');
                        }    
                       
                    }
                }
                // $matchScheduleData[$key]['match_start_date'] = $matchStartDate->toFormattedDateString();
				$matchScheduleData[$key]['match_start_date'] = Helper::getFormattedTimeStamp($schedule);
            }
        }

        return view('schedules.listmoreview', ['matchScheduleData' => $matchScheduleData, 'teamId' => $teamId,
            'sportsId' => $sportsId, 'isOwner' => $isOwner,
            'limit' => $limit, 'offset' => $limit + $offset]);
    }
    
    /**
     * This function will display all the matches with score to add
     * @return type - html
     */
    public function showScores($teamId, $sportsId) {
        $fromDate = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->subDays(30))->format('Y-m-d');
        $toDate = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->format('Y-m-d');
        $request = Request::all();
        $limit = config('constants.LIMIT');
//        $limit = 1;
        $offset = 0;
        $isOwner = 0;
        $scoreOwner = 0;
        if (Helper::isTeamOwnerorcaptain($teamId,Auth::user()->id)) {
            $isOwner = 1;
        }else{
            return view('errors.'.'503');
        }
        $constraint = function($query) {
//            $query->where('match_start_date', '>', Carbon::now()->subDays(30));
            $query->whereBetween('match_start_date', [Carbon::now()->subDays(30), Carbon::now()]);
        };
        if (count($request)) {

            $fromDate = date('Y-m-d', strtotime(Request::get('fromDate')));
            $toDate = date('Y-m-d', strtotime(Request::get('toDate')));

            $constraint = function($query) use ($fromDate, $toDate) {
                $query->whereBetween('match_start_date', [$fromDate, $toDate]);
            };
        }

        $matchScheduleData = MatchSchedule::with(array('scheduleteamone' => function($q1) {
                    $q1->select('id', 'name');
                },
                        'scheduleteamtwo' => function($q2) {
                    $q2->select('id', 'name');
                },
                   'sport' => function($q3) {
                $q3->select('id','sports_name');
                },     
                    'scheduleteamone.photos', 'scheduleteamtwo.photos'))
                ->where(function($query) use ($teamId) {
                    $query->where('a_id', $teamId)->orWhere('b_id', $teamId);
                })
                ->where('schedule_type', 'team')
                ->where('sports_id', $sportsId)
                ->where('match_status', 'scheduled')
                ->where('match_invite_status', 'accepted')
				->whereNotNull('match_start_date')
				// ->where(function($q3){
                    // $q3->where('scoring_status', 'rejected')->orWhereNull('scoring_status');
                // })
                ->where($constraint)
                ->orderby('match_start_date', 'desc')
                ->orderby('match_start_time', 'desc')        
                ->limit($limit)->offset($offset)
                ->get(['id', 'match_start_date', 'match_start_time','winner_id', 'a_id', 'b_id', 'match_invite_status','scoring_status','score_added_by','match_type','sports_id']);

        $matchScheduleDataTotalCount = MatchSchedule::where(function($query) use ($teamId) {
                    $query->where('a_id', $teamId)->orWhere('b_id', $teamId);
                })
                ->where('schedule_type', 'team')
                ->where('sports_id', $sportsId)
                ->where('match_status', 'scheduled')
                ->where('match_invite_status', 'accepted')
				->whereNotNull('match_start_date')
				// ->where(function($q1){
                    // $q1->where('scoring_status', 'rejected')->orWhereNull('scoring_status');
                // })
				->where($constraint)
                ->count();
//        Helper::printQueries(); 
//        dd(count($matchScheduleData).'<>'.$matchScheduleDataTotalCount);

        if (count($matchScheduleData)) {
            foreach ($matchScheduleData->toArray() as $key => $schedule) {
                if (count($schedule['scheduleteamone']['photos'])) {
                    $teamOneUrl = array_collapse($schedule['scheduleteamone']['photos']);
                    $matchScheduleData[$key]['scheduleteamone']['url'] = $teamOneUrl['url'];
                } else {
                    $teamOneUrl = $matchScheduleData[$key]['scheduleteamone'];
                    $teamOneUrl['url'] = '';
                }

                if (count($schedule['scheduleteamtwo']['photos'])) {
                    $teamTwoUrl = array_collapse($schedule['scheduleteamtwo']['photos']);
                    $matchScheduleData[$key]['scheduleteamtwo']['url'] = $teamTwoUrl['url'];
                } else {
//                $matchScheduleData[$key]['scheduleteamtwo']['url'] = '';
                    $teamTwoUrl = $matchScheduleData[$key]['scheduleteamtwo'];
                    $teamTwoUrl['url'] = '';
                }

                $matchStartDate = Carbon::createFromFormat('Y-m-d', $schedule['match_start_date']);

                if (Carbon::now()->gte($matchStartDate)) {
                    $match_details = MatchSchedule::where('id',$schedule['id'])->get();
                    $scoreOwner = Helper::isValidUserForScoreEnter($match_details->toArray());
                    if ($scoreOwner) {
						/*if(!empty($schedule['score_added_by'])) {
							if($schedule['score_added_by']==Auth::user()->id) {
								$matchScheduleData[$key]['winner_text'] = trans('message.schedule.editscore');		
							}else {
								$matchScheduleData[$key]['winner_text'] = trans('message.schedule.viewscore');
							}    
						}else{
						$matchScheduleData[$key]['winner_text'] = trans('message.schedule.addscore');
						}*/
						$matchScheduleData[$key]['winner_text'] = Helper::getCurrentScoringStatus($schedule);
                            
                    }
                }
                // $matchScheduleData[$key]['match_start_date'] = $matchStartDate->toFormattedDateString();
				$matchScheduleData[$key]['match_start_date'] = Helper::getFormattedTimeStamp($schedule);
            }
        }

        Helper::leftMenuVariables($teamId);
        return view('schedules.scoreview', ['matchScheduleData' => $matchScheduleData, 'matchScheduleDataTotalCount' => $matchScheduleDataTotalCount,
            'sportsId' => $sportsId, 'teamId' => $teamId, 'fromDate' => $fromDate,
            'toDate' => $toDate, 'limit' => $limit, 'offset' => $limit + $offset]);
    }
    
    
    function viewMoreScores() {
        $limit = Request::get('limit');
        $offset = Request::get('offset');
        $fromDate = Request::get('fromDate');
        $toDate = Request::get('toDate');
        $teamId = Request::get('teamId');
        $sportsId = Request::get('sportsId');
        $isOwner = 0;
        $scoreOwner = 0;
        if (Helper::isTeamOwnerorcaptain($teamId,Auth::user()->id)) {
            $isOwner = 1;
        }else{
            return view('errors.'.'503');
        }
        // $fromDate = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->subDays(30))->format('Y-m-d');
        // $toDate = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->format('Y-m-d');
        // $constraint = function($query) {
        // $query->where('match_start_date', '>', Carbon::now()->subDays(30));
        // };
        if (count($fromDate) && count($toDate)) {

            $fromDate = date('Y-m-d', strtotime($fromDate));
            $toDate = date('Y-m-d', strtotime($toDate));

            $constraint = function($query) use ($fromDate, $toDate) {
                $query->whereBetween('match_start_date', [$fromDate, $toDate]);
            };
        }

        $matchScheduleData = MatchSchedule::with(array('scheduleteamone' => function($q1) {
                $q1->select('id', 'name');
            },
                    'scheduleteamtwo' => function($q2) {
                $q2->select('id', 'name');
            }, 
               'sport' => function($q3) {
                $q3->select('id','sports_name');
            },     
               'scheduleteamone.photos', 'scheduleteamtwo.photos'))->where(function($query) use ($teamId) {
                    $query->where('a_id', $teamId)->orWhere('b_id', $teamId);
                })
                ->where('schedule_type', 'team')
                ->where('sports_id', $sportsId)
                ->where('match_status', 'scheduled')
                ->where('match_invite_status', 'accepted')
				->whereNotNull('match_start_date')
				// ->where(function($q3){
                    // $q3->where('scoring_status', 'rejected')->orWhereNull('scoring_status');
                // })
                ->where($constraint)
                ->orderby('match_start_date', 'desc')
                ->orderby('match_start_time', 'desc')        
                ->limit($limit)->offset($offset)
                ->get(['id', 'match_start_date', 'match_start_time','winner_id', 'a_id', 'b_id', 'match_invite_status','scoring_status','score_added_by','match_type','sports_id']);

        if (count($matchScheduleData)) {
            foreach ($matchScheduleData->toArray() as $key => $schedule) {
                if (count($schedule['scheduleteamone']['photos'])) {
                    $teamOneUrl = array_collapse($schedule['scheduleteamone']['photos']);
                    $matchScheduleData[$key]['scheduleteamone']['url'] = $teamOneUrl['url'];
                } else {
                    $teamOneUrl = $matchScheduleData[$key]['scheduleteamone'];
                    $teamOneUrl['url'] = '';
                }

                if (count($schedule['scheduleteamtwo']['photos'])) {
                    $teamTwoUrl = array_collapse($schedule['scheduleteamtwo']['photos']);
                    $matchScheduleData[$key]['scheduleteamtwo']['url'] = $teamTwoUrl['url'];
                } else {
//                $matchScheduleData[$key]['scheduleteamtwo']['url'] = '';
                    $teamTwoUrl = $matchScheduleData[$key]['scheduleteamtwo'];
                    $teamTwoUrl['url'] = '';
                }

               $matchStartDate = Carbon::createFromFormat('Y-m-d', $schedule['match_start_date']);

                if (Carbon::now()->gte($matchStartDate)) {
                    $match_details = MatchSchedule::where('id',$schedule['id'])->get();
                    $scoreOwner = Helper::isValidUserForScoreEnter($match_details->toArray());
                    if ($scoreOwner) {
                        /*if(!empty($schedule['score_added_by'])) {
                            if($schedule['score_added_by']==Auth::user()->id) {
                                $matchScheduleData[$key]['winner_text'] = trans('message.schedule.editscore');		
                            }else {
                                $matchScheduleData[$key]['winner_text'] = trans('message.schedule.viewscore');
                            }		
                        }else{
                            $matchScheduleData[$key]['winner_text'] = trans('message.schedule.addscore');
                        }*/
						$matchScheduleData[$key]['winner_text'] = Helper::getCurrentScoringStatus($schedule);
                    }
                }
                // $matchScheduleData[$key]['match_start_date'] = $matchStartDate->toFormattedDateString();
				$matchScheduleData[$key]['match_start_date'] = Helper::getFormattedTimeStamp($schedule);
            }
        }
        return view('schedules.scoremoreview', ['matchScheduleData' => $matchScheduleData, 'sportsId' => $sportsId,
            'teamId' => $teamId, 'limit' => $limit, 'offset' => $limit + $offset,
        ]);
    }

    /**
     * This function will display all the matches with winner
     * @return type - html
     */
    public function showStats($teamId, $sportsId) {

        $fromDate = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->subDays(30))->format('Y-m-d');
        $toDate = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->format('Y-m-d');
        $request = Request::all();
        $limit = config('constants.LIMIT');
//        $limit = 1;
        $offset = 0;
        $constraint = function($query) {
//            $query->where('match_start_date', '>', Carbon::now()->subDays(30));
            $query->whereBetween('match_start_date', [Carbon::now()->subDays(30), Carbon::now()]);
        };
        if (count($request)) {

            $fromDate = date('Y-m-d', strtotime(Request::get('fromDate')));
            $toDate = date('Y-m-d', strtotime(Request::get('toDate')));

            $constraint = function($query) use ($fromDate, $toDate) {
                $query->whereBetween('match_start_date', [$fromDate, $toDate]);
            };
        }

        $matchScheduleData = MatchSchedule::with(array('scheduleteamone' => function($q1) {
                    $q1->select('id', 'name');
                },
                    'scheduleteamtwo' => function($q2) {
                    $q2->select('id', 'name');
                }, 
                    'sport' => function($q3) {
                    $q3->select('id','sports_name');
                },    
                        'scheduleteamone.photos', 'scheduleteamtwo.photos'))
                ->where(function($query) use ($teamId) {
                    $query->where('a_id', $teamId)->orWhere('b_id', $teamId);
                })
                ->where('schedule_type', 'team')
                ->where('sports_id', $sportsId)
//                        ->whereNotNull('winner_id')
                ->where('match_status', 'completed')
                ->where($constraint)
                ->orderby('match_start_date', 'desc')
                ->orderby('match_start_time', 'desc')
                ->limit($limit)->offset($offset)
                ->get(['id', 'match_start_date', 'match_start_time','winner_id', 'a_id', 'b_id','match_type','sports_id', 'match_status']);

        $matchScheduleDataTotalCount = MatchSchedule::where(function($query) use ($teamId) {
                    $query->where('a_id', $teamId)->orWhere('b_id', $teamId);
                })
                ->where('schedule_type', 'team')
                ->where('sports_id', $sportsId)
                ->where('match_status', 'completed')
                ->where($constraint)
                ->count();
//        Helper::printQueries(); 
//        dd(count($matchScheduleData).'<>'.$matchScheduleDataTotalCount);

        if (count($matchScheduleData)) {
            foreach ($matchScheduleData->toArray() as $key => $schedule) {
                if (count($schedule['scheduleteamone']['photos'])) {
                    $teamOneUrl = array_collapse($schedule['scheduleteamone']['photos']);
                    $matchScheduleData[$key]['scheduleteamone']['url'] = $teamOneUrl['url'];
                } else {
                    $teamOneUrl = $matchScheduleData[$key]['scheduleteamone'];
                    $teamOneUrl['url'] = '';
                }

                if (count($schedule['scheduleteamtwo']['photos'])) {
                    $teamTwoUrl = array_collapse($schedule['scheduleteamtwo']['photos']);
                    $matchScheduleData[$key]['scheduleteamtwo']['url'] = $teamTwoUrl['url'];
                } else {
//                $matchScheduleData[$key]['scheduleteamtwo']['url'] = '';
                    $teamTwoUrl = $matchScheduleData[$key]['scheduleteamtwo'];
                    $teamTwoUrl['url'] = '';
                }

                if (!empty($schedule['winner_id'])) {
                    if ($schedule['scheduleteamone']['id'] == $schedule['winner_id']) {
                        $matchScheduleData[$key]['scheduleteamone']['result'] = trans('message.team.stats.won');
                        if (count($schedule['scheduleteamtwo']['photos'])) {
                            $matchScheduleData[$key]['scheduleteamtwo']['result'] = trans('message.team.stats.lost');
                        }
                    } else {
                        $matchScheduleData[$key]['scheduleteamone']['result'] = trans('message.team.stats.lost');
                        if (count($schedule['scheduleteamtwo']['photos'])) {
                            $matchScheduleData[$key]['scheduleteamtwo']['result'] = trans('message.team.stats.won');
                        }
                    }
                } else {
                    $matchScheduleData[$key]['scheduleteamone']['result'] = trans('message.team.stats.tied');
                    if (count($schedule['scheduleteamtwo']['photos'])) {
                        $matchScheduleData[$key]['scheduleteamtwo']['result'] = trans('message.team.stats.tied');
                    }
                }

                $matchStartDate = Carbon::createFromFormat('Y-m-d', $schedule['match_start_date']);
                $matchScheduleData[$key]['winner_text'] = trans('message.schedule.scorecard');
                // $matchScheduleData[$key]['match_start_date'] = $matchStartDate->toFormattedDateString();
				$matchScheduleData[$key]['match_start_date'] = Helper::getFormattedTimeStamp($schedule);
            }
        }


        // Team Stats
        $teamStats = MatchSchedule::where(function($query) use ($teamId) {
                            $query->where('a_id', $teamId)->orWhere('b_id', $teamId);
                        })                       
                        ->where('match_status', 'completed')
                        ->get(['id','winner_id', 'looser_id', 'is_tied','match_type']);

        $rubberStats = MatchScheduleRubber::where(function($query) use ($teamId) {
                            $query->where('a_id', $teamId)->orWhere('b_id', $teamId);
                        })                       
                        ->where('match_status', 'completed')
                        ->get(['id','winner_id', 'looser_id', 'is_tied','match_type']);
        if(count($teamStats)) {    
            switch ($sportsId) {

                case config('constants.SPORT_ID.Cricket'):
                    $statsArray = Helper::getCricketStats($teamStats,$teamId);
                    break;
                case config('constants.SPORT_ID.Tennis'):
                    $statsArray = Helper::getTennisTableTennisStats($teamStats,$teamId);
                    break;
                case config('constants.SPORT_ID.Table Tennis'):
                    $statsArray = Helper::getTennisTableTennisStats($teamStats,$teamId);
                    break;
                case config('constants.SPORT_ID.Soccer'):
                    $statsArray = Helper::getSoccerStats($teamStats,$teamId);
                    break;
                case config('constants.SPORT_ID.Hockey'):
                    $statsArray = Helper::getHockeyStats($teamStats,$teamId);
                    break;
                case config('constants.SPORT_ID.Badminton'):
                    $statsArray = Helper::getTennisTableTennisStats($teamStats,$teamId);
                    break;
                 case config('constants.SPORT_ID.Squash'):
                    $statsArray = Helper::getTennisTableTennisStats($teamStats,$teamId);
                    break;
                 
                default:
                    $statsArray = Helper::getHockeyStats($teamStats,$teamId);
            }  

        }

        if(count($rubberStats)) {      
   
            $rubberStats = Helper::getTennisTableTennisStats($rubberStats,$teamId);
        
        }

        $statsview = 'schedules.'.preg_replace('/\s+/', '',strtolower(config('constants.SPORT_NAME.'.$sportsId))).'statsview';

        Helper::leftMenuVariables($teamId);
        return view('schedules.statsview', ['matchScheduleData' => $matchScheduleData, 'matchScheduleDataTotalCount' => $matchScheduleDataTotalCount,
            'sportsId' => $sportsId, 'teamId' => $teamId, 'fromDate' => $fromDate,
            'toDate' => $toDate, 'statsArray' => !empty($statsArray)?$statsArray:[], 'limit' => $limit, 'offset' => $limit + $offset, 'statsview'=>$statsview, 'rubberStats'=>$rubberStats]);
    }

    function viewMoreStats() {

        $limit = Request::get('limit');
        $offset = Request::get('offset');
        $fromDate = Request::get('fromDate');
        $toDate = Request::get('toDate');
        $teamId = Request::get('teamId');
        $sportsId = Request::get('sportsId');

        // $fromDate = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->subDays(30))->format('Y-m-d');
        // $toDate = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->format('Y-m-d');
        // $constraint = function($query) {
        // $query->where('match_start_date', '>', Carbon::now()->subDays(30));
        // };
        if (count($fromDate) && count($toDate)) {

            $fromDate = date('Y-m-d', strtotime($fromDate));
            $toDate = date('Y-m-d', strtotime($toDate));

            $constraint = function($query) use ($fromDate, $toDate) {
                $query->whereBetween('match_start_date', [$fromDate, $toDate]);
            };
        }

        $matchScheduleData = MatchSchedule::with(array('scheduleteamone' => function($q1) {
                $q1->select('id', 'name');
            },
                    'scheduleteamtwo' => function($q2) {
                $q2->select('id', 'name');
            }, 
               'sport' => function($q3) {
                $q3->select('id','sports_name');
            },
              'scheduleteamone.photos', 'scheduleteamtwo.photos'))->where(function($query) use ($teamId) {
                    $query->where('a_id', $teamId)->orWhere('b_id', $teamId);
                })
                ->where('schedule_type', 'team')
                ->where('sports_id', $sportsId)
                ->where('match_status', 'completed')
                ->where($constraint)
                ->orderby('match_start_date', 'desc')
                ->orderby('match_start_time', 'desc')        
                ->limit($limit)->offset($offset)
                ->get(['id', 'match_start_date', 'match_start_time','winner_id', 'a_id', 'b_id','match_type','sports_id', 'match_status']);

        if (count($matchScheduleData)) {
            foreach ($matchScheduleData->toArray() as $key => $schedule) {
                if (count($schedule['scheduleteamone']['photos'])) {
                    $teamOneUrl = array_collapse($schedule['scheduleteamone']['photos']);
                    $matchScheduleData[$key]['scheduleteamone']['url'] = $teamOneUrl['url'];
                } else {
                    $teamOneUrl = $matchScheduleData[$key]['scheduleteamone'];
                    $teamOneUrl['url'] = '';
                }

                if (count($schedule['scheduleteamtwo']['photos'])) {
                    $teamTwoUrl = array_collapse($schedule['scheduleteamtwo']['photos']);
                    $matchScheduleData[$key]['scheduleteamtwo']['url'] = $teamTwoUrl['url'];
                } else {
//                $matchScheduleData[$key]['scheduleteamtwo']['url'] = '';
                    $teamTwoUrl = $matchScheduleData[$key]['scheduleteamtwo'];
                    $teamTwoUrl['url'] = '';
                }

                if (!empty($schedule['winner_id'])) {
                    if ($schedule['scheduleteamone']['id'] == $schedule['winner_id']) {
                        $matchScheduleData[$key]['scheduleteamone']['result'] = trans('message.team.stats.won');
                        if (count($schedule['scheduleteamtwo']['photos'])) {
                            $matchScheduleData[$key]['scheduleteamtwo']['result'] = trans('message.team.stats.lost');
                        }
                    } else {
                        $matchScheduleData[$key]['scheduleteamone']['result'] = trans('message.team.stats.lost');
                        if (count($schedule['scheduleteamtwo']['photos'])) {
                            $matchScheduleData[$key]['scheduleteamtwo']['result'] = trans('message.team.stats.won');
                        }
                    }
                } else {
                    $matchScheduleData[$key]['scheduleteamone']['result'] = trans('message.team.stats.tied');
                    if (count($schedule['scheduleteamtwo']['photos'])) {
                        $matchScheduleData[$key]['scheduleteamtwo']['result'] = trans('message.team.stats.tied');
                    }
                }

                $matchStartDate = Carbon::createFromFormat('Y-m-d', $schedule['match_start_date']);
                $matchScheduleData[$key]['winner_text'] = trans('message.schedule.scorecard');
                // $matchScheduleData[$key]['match_start_date'] = $matchStartDate->toFormattedDateString();
				$matchScheduleData[$key]['match_start_date'] = Helper::getFormattedTimeStamp($schedule);
            }
        }
        return view('schedules.statsmoreview', ['matchScheduleData' => $matchScheduleData, 'sportsId' => $sportsId,
            'teamId' => $teamId, 'limit' => $limit, 'offset' => $limit + $offset,
        ]);
    }

    function buildMatchScheduleData($sportsId, $teamId, $fromDate, $toDate) {
        $matchSchedules = MatchSchedule::with(array('scheduleteamone' => function($q1) {
                        $q1->select('id', 'name');
                    },
                            'scheduleteamtwo' => function($q2) {
                        $q2->select('id', 'name');
                    }, 'scheduleteamone.photos', 'scheduleteamtwo.photos'))->where(function($query) use ($teamId) {
                            $query->where('a_id', $teamId)->orWhere('b_id', $teamId);
                        })->where('schedule_type', 'team')->where('sports_id', $sportsId)
                        ->whereBetween('match_start_date', [$fromDate, $toDate])->get();
        return $matchSchedules;
    }

    function buildEventTitle($schedule) {
        $team_a_name = $schedule['scheduleteamone']['name'];
        $team_b_name = $schedule['scheduleteamtwo']['name'];
        if (isset($team_a_name) && isset($team_b_name))
            $title = $team_a_name . ' VS ' . $team_b_name;
        else if (isset($team_a_name))
            $title = $team_a_name;
        else if (isset($team_b_name))
            $title = $team_b_name;
        return $title;
    }

    //function to get my team details
    public function getmyteamdetails() {
        $request = Request::all();
        $team_id = Request::get('team_id');
        $sport_id = Request::get('sport_id');
        $search_team = Request::get('term');
        $scheduled_type = Request::get('scheduled_type');
        $results = array();
        $teams = array();
        $sport_name = Sport::where('id', $sport_id)->pluck('sports_name');
        $user_id = Auth::user()->id;
        $managing_teams = trim(Helper::getManagingTeamIds($user_id),',');
        $managing_teams = explode(',',$managing_teams);
        //if team is selected, get the teams with same sport
        if (!empty($sport_name)) {
            if ($scheduled_type == 'team') {
                if(!empty($team_id))
                {
                  $teams = DB::table('teams')
                        ->join('sports', 'sports.id', '=', 'teams.sports_id')
                        ->select('teams.id', 'teams.name')
                        ->where('teams.id', [$team_id])
                        ->where('sports.sports_name', 'LIKE', '%' . $sport_name . '%')
                        ->where('teams.name', 'LIKE', '%' . $search_team . '%')
                        ->orderBy('teams.name')
                        ->get();                  
                }
                else
                {
                   $teams = DB::table('teams')
                        ->join('sports', 'sports.id', '=', 'teams.sports_id')
                        ->select('teams.id', 'teams.name')
                        ->whereIn('teams.id', $managing_teams)
                        ->where('sports.sports_name', 'LIKE', '%' . $sport_name . '%')
                        ->where('teams.name', 'LIKE', '%' . $search_team . '%')
                        ->orderBy('teams.name')
                        ->get();
                }

            } elseif ($scheduled_type == 'player') {//if player is selected, get the players from all the teams with same sport
                $teams = DB::table('teams')
                        ->join('team_players', 'teams.id', '=', 'team_players.team_id')
                        ->join('users', 'users.id', '=', 'team_players.user_id')
                        ->join('sports', 'sports.id', '=', 'teams.sports_id')
                        ->select('users.id', 'users.name')
                        ->where('teams.id', [$team_id])
                        ->where('sports.sports_name', 'LIKE', '%' . $sport_name . '%')
                        ->where('users.name', 'LIKE', '%' . $search_team . '%')
                        ->get();
            }
            foreach ($teams as $query) {
                $results[] = ['id' => $query->id, 'value' => $query->name];
            }
        }
        return Response::json($results);
    }

    //function to get opposite team details
    public function getoppositeteamdetails() {
        $request = Request::all();
        $team_id = Request::get('team_id');
        $sport_id = Request::get('sport_id');
        $search_team = Request::get('term');
        $scheduled_type = Request::get('scheduled_type');
        $results = array();
        $teams = array();
        $sport_name = Sport::where('id', $sport_id)->pluck('sports_name');
        DB::setFetchMode(PDO::FETCH_CLASS);
        //if team is selected, get the teams with same sport
        if (!empty($sport_name)) {
            if ($scheduled_type == 'team') {
                $teams = DB::table('teams')
                        ->join('sports', 'sports.id', '=', 'teams.sports_id')
                        ->select('teams.id', 'teams.name','teams.team_level')
                        ->where('sports.sports_name', 'LIKE', '%' . $sport_name . '%')
                        ->where('teams.name', 'LIKE', '%' . $search_team . '%')
                        ->where('teams.team_available', '=', 1)
                        ->where('teams.isactive', '=', 1)
                        ->where('teams.sports_id', '=', $sport_id)
                        ->whereNull('teams.deleted_at')
                        ->whereNull('sports.deleted_at')
                        ->orderBy('teams.name')
                        ->whereNotIn('teams.id', [$team_id])
                        ->get();
            } elseif ($scheduled_type == 'player') {//if player is selected, get the players from all the teams with same sport
                $teams = DB::table('teams')
                        ->join('team_players', 'teams.id', '=', 'team_players.team_id')
                        ->join('users', 'users.id', '=', 'team_players.user_id')
                        ->join('user_statistics', 'users.id', '=', 'user_statistics.user_id')
                        ->distinct()
                        ->select('users.id', 'users.name')
                        ->where(function($query) use ($sport_id) {
                            $query->where('user_statistics.allowed_player_matches', 'LIKE', '%' . ','.$sport_id.',' . '%')
                                  ->orWhere('teams.sports_id','=',$sport_id);
                            })
                        ->where('users.name', 'LIKE', '%' . $search_team . '%')
                        ->whereNotIn('users.id', [$team_id])
                        ->whereNull('teams.deleted_at')
                        ->whereNull('team_players.deleted_at')                        
                        ->whereNull('users.deleted_at')                         
                        ->get();
            }
            foreach ($teams as $query) {
                $team_level = !empty($query->team_level)?' ('.$query->team_level.')':'';
                $results[] = ['id' => $query->id, 'value' => ($query->name.$team_level)];
            }
        }
        return Response::json($results);
    }

    //	function to get the facilities
    public function getfacilities() {
        $search_facility = Request::get('term');
        $facilities = Facilityprofile::where('name', 'LIKE', '%' . $search_facility . '%')->orderBy('name')->get(array('id', 'name'));
        $results = array();
        if (count($facilities)) {
            foreach ($facilities as $query) {
                $results[] = ['id' => $query->id, 'value' => $query->name];
            }
        }
        return Response::json($results);
    }

    //function to save schedule
    public function saveschedule(Requests\AddSchedulesRequest $request) {

        $request = Request::all();
        //reading all the get varaibles to local variables
        $bye = Request::get('bye');
        $schedule_type = Request::get('scheduletype');
        $a_id = Request::get('my_team_id');
        $b_id = Request::get('opp_team_id');
        /*        $match_start_date = $this->getdatetime(date(config("constants.DATE_FORMAT.PHP_DATE_FORMAT"), strtotime(Request::get('match_start_date'))), 'd');*/
        $match_start_date = Helper::storeDate(Request::get('match_start_date'),'date');
        $match_start_time = !empty(Request::get('match_start_time'))?$this->getdatetime(date(config("constants.DATE_FORMAT.PHP_TIME_FORMAT"), strtotime(Request::get('match_start_time'))), 't'):'00:00:00';   
        $game_type = 'normal';
        $number_of_rubber =  null;


       
        $facility_name = Request::get('venue');
        $facility_id = Request::get('facility_id');
        $address = Request::get('address');
        $city_id = !empty(Request::get('city_id')) ? Request::get('city_id') : NULL;
        $city = Request::get('city');
        $state_id = !empty(Request::get('state_id')) ? Request::get('state_id') : NULL;
        $state = Request::get('state');
        $city = !empty(Request::get('city_id')) ? City::where('id', Request::get('city_id'))->first()->city_name : NULL;
        $state = !empty(Request::get('state_id')) ? State::where('id', Request::get('state_id'))->first()->state_name : NULL;
        $zip = Request::get('zip');
        $player_type = Request::get('player_type');
        $match_type = Request::get('match_type');
        $sports_id = Request::get('sports_id');
        $is_edit = Request::get('is_edit');
        $tournament_id = !empty(Request::get('tournament_id')) ? Request::get('tournament_id') : NULL;
        $tournament_group_id = !empty(Request::get('tournament_group_id')) ? Request::get('tournament_group_id') : NULL;
        $tournament_round_number = !empty(Request::get('tournament_round_number')) ? Request::get('tournament_round_number') : NULL;
        $tournament_match_number = !empty(Request::get('tournament_match_number')) ? Request::get('tournament_match_number') : NULL;
        $is_tournament = !empty(Request::get('is_tournament')) ? Request::get('is_tournament') : NULL;
        $country_id = !empty(Request::get('country_id')) ? Request::get('country_id') : NULL;
        $country = !empty(Request::get('country_id')) ? Country::where('id', Request::get('country_id'))->first()->country_name : NULL;
        $match_location = rtrim($country . ', ' . $state . ', ' . $city, ', ');
        $player_a_ids = $a_id;
        $player_b_ids = !empty($b_id)?$b_id:'';
        $scheduleId = Request::get('schedule_id');
        //if schedule type is team then get the team players comma seperated
        if ($schedule_type == 'team') {
            $player_a_ids = TeamPlayers::select(DB::raw('GROUP_CONCAT(DISTINCT user_id) AS player_a_ids'))->where('team_id', $a_id)->pluck('player_a_ids');
            if(!empty($b_id)) {
                $player_b_ids = TeamPlayers::select(DB::raw('GROUP_CONCAT(DISTINCT user_id) AS player_b_ids'))->where('team_id', $b_id)->pluck('player_b_ids');
            }    
            
        }
		if ($schedule_type == 'individual') {
			$schedule_type = 'player';
		}
		
         $player_a_ids = !empty($player_a_ids)?(','.trim($player_a_ids).','):NULL;
         $player_b_ids = !empty($player_b_ids)?(','.trim($player_b_ids).','):NULL;
		 
		 $match_invite_status = 'pending';
         
         if(!empty($tournament_id)) {
             $tournamentDetails = Tournaments::where('id',$tournament_id)->first(['match_type','player_type','sports_id','game_type', 'number_of_rubber']);
             if(count($tournamentDetails)) {
        $player_type = $tournamentDetails->player_type=='any'?Request::get('player_type'):$tournamentDetails->player_type;
        $match_type = $tournamentDetails->match_type=='any'?Request::get('match_type'):$tournamentDetails->match_type;
                $sports_id = $tournamentDetails->sports_id;
                $game_type = $tournamentDetails->game_type;
                $number_of_rubber =  $tournamentDetails->number_of_rubber;
             }
			$match_invite_status = 'accepted';

   

			 
         }
        //prepare an array to insert
        $schedule_data = array(
            'tournament_id' => $tournament_id,
            'tournament_group_id' => $tournament_group_id,
            'tournament_round_number' => $tournament_round_number,
            'tournament_match_number' => $tournament_match_number,
            'sports_id' => $sports_id,
            'facility_id' => $facility_id,
            'facility_name' => $facility_name,
            'created_by' => Auth::user()->id,
            'match_category' => $player_type,
            'schedule_type' => $schedule_type,
            'match_type' => $match_type,
            'match_start_date' => $match_start_date,
            'match_start_time' => $match_start_time,
            'match_end_date' => !empty($match_end_date) ? $match_end_date : $match_start_date,
            'match_end_time' => !empty($match_end_time) ? $match_end_time : $match_start_time,
            'match_location' => $match_location,
            'address' => $address,
            'city_id' => $city_id,
            'city' => $city,
            'state_id' => $state_id,
            'state' => $state,
            'country_id' => $country_id,
            'country' => $country,
            'zip' => $zip,
            'match_status' => 'scheduled',
            'a_id' => $a_id,
            'b_id' => !empty($b_id)?$b_id:NULL,
            'player_a_ids' => $player_a_ids,
            'player_b_ids' => $player_b_ids,
			'match_invite_status'=>$match_invite_status,
            'game_type'     => $game_type,
            'number_of_rubber' => $number_of_rubber

        );
        if(!empty($bye) && $bye==2) {
                $schedule_data['winner_id']=$a_id;
                $schedule_data['match_status']='completed';
        }
        $results = array();
        if (is_numeric($scheduleId)) {
            if(!empty($tournament_id)) {
                unset($schedule_data['tournament_id']);
                unset($schedule_data['tournament_group_id']);
                unset($schedule_data['tournament_round_number']);
                unset($schedule_data['tournament_match_number']);
            }
            if (MatchSchedule::where('id', $scheduleId)->update($schedule_data)) {
                if(!empty($bye) && $bye==2) {
                    $this->insertByeTeamDetails($scheduleId);
                }
                if(!empty($tournament_id)) {
                      AllRequests::sendMatchNotifications($tournament_id,$schedule_type,$a_id,$b_id,$match_start_date);
                }
                $results['success'] = 'Match schedule updated successfully.';
            } else {
                $results['failure'] = 'Failed to update the match schedule.';
            }
        } elseif ($scheduleId == NULL) {
            $match_schedule_result = MatchSchedule::create($schedule_data);
            $match_schedule_id = $match_schedule_result['id'];
            if(!empty($bye) && $bye==2) {
                $this->insertByeTeamDetails($match_schedule_id);
            }    
            if (isset($match_schedule_id) && $match_schedule_id > 0 && !empty($b_id)) {
                /*                $scheduleDetails = MatchSchedule::create($schedule_data);
                  $matchId = !empty($scheduleDetails['id'])?$scheduleDetails['id']:NULL;
                  //if request is from tournaments, update match id in tournament_group_teams table
                  if($is_tournament == 1 && is_numeric($matchId) && $tournament_group_id)
                  {
                  $teamsGroupsId = array($a_id,$b_id);
                  TournamentGroupTeams::where('tournament_group_id',$tournament_group_id)->whereIn('team_id',$teamsGroupsId)->update(['match_id'=>$matchId,'updated_at'=>Carbon::now()]);
                  } */
                
                  //insert into request and notifications table
                  if(!empty($tournament_id)) {
                      AllRequests::sendMatchNotifications($tournament_id,$schedule_type,$a_id,$b_id,$match_start_date);

                      if($game_type=='rubber' && (is_numeric($number_of_rubber) && $number_of_rubber>0)){
                            //$this->insertGroupRubber($match_schedule_id);
                      }


                  }else {
                    $request_array = array('flag'=>'TEAM_TO_TEAM','player_tournament_id'=>$a_id,'team_ids'=>array($b_id),'match_schedule_id'=>$match_schedule_id);
                    AllRequests::saverequest($request_array);
                  }
                $results['success'] = 'Match scheduled successfully.';
            } else {
                $results['success'] = 'Match scheduled successfully.';
            }
        } else {
            $results['failure'] = 'Failed to schedule the match.';
        }
        return Response::json($results);
    }

    //function to save schedule
    public function main_saveschedule(Requests\AddMainSchedulesRequest $request) 
    {
        $request = Request::all();
        //reading all the get varaibles to local variables
        $schedule_type = Request::get('main_scheduletype');
        $a_id = Request::get('main_my_team_id');
        $b_id = Request::get('main_opp_team_id');
        // $match_start_date = $this->getdatetime(date(config("constants.DATE_FORMAT.PHP_DATE_FORMAT"), strtotime(Request::get('main_match_start_date'))), 'd');
        $match_start_date = Helper::storeDate(Request::get('main_match_start_date'),'date');
        $match_start_time = !empty(Request::get('main_match_start_time'))?$this->getdatetime(date(config("constants.DATE_FORMAT.PHP_TIME_FORMAT"), strtotime(Request::get('main_match_start_time'))), 't'):'00:00:00';
        $facility_name = Request::get('main_venue');
        $facility_id = Request::get('main_facility_id');
        $address = Request::get('address');
        $city_id = !empty(Request::get('city_id')) ? Request::get('city_id') : NULL;
        $city = Request::get('city');
        $state_id = !empty(Request::get('state_id')) ? Request::get('state_id') : NULL;
        $state = Request::get('state');
        $city = !empty(Request::get('city_id')) ? City::where('id', Request::get('city_id'))->first()->city_name : NULL;
        $state = !empty(Request::get('state_id')) ? State::where('id', Request::get('state_id'))->first()->state_name : NULL;
        $zip = Request::get('zip');
        $player_type = Request::get('main_player_type');
        $match_type = Request::get('main_match_type');
        $sports_id = Request::get('main_sports_id');
        $is_edit = Request::get('main_is_edit');
        $tournament_id = !empty(Request::get('main_tournament_id')) ? Request::get('main_tournament_id') : NULL;
        $tournament_group_id = !empty(Request::get('main_tournament_group_id')) ? Request::get('main_tournament_group_id') : NULL;
        $tournament_round_number = !empty(Request::get('main_tournament_round_number')) ? Request::get('main_tournament_round_number') : NULL;
        $tournament_match_number = !empty(Request::get('main_tournament_match_number')) ? Request::get('main_tournament_match_number') : NULL;
        $is_tournament = !empty(Request::get('main_is_tournament')) ? Request::get('main_is_tournament') : NULL;
        $country_id = config('constants.COUNTRY_INDIA');
        $country = Country::where('id', config('constants.COUNTRY_INDIA'))->first()->country_name;
        $match_location = rtrim($country . ', ' . $state . ', ' . $city, ', ');

       $game_type = 'normal';
        $number_of_rubber =  null;
        
        $player_a_ids = $a_id;
        $player_b_ids = $b_id;
        $scheduleId = Request::get('main_schedule_id');
        //if schedule type is team then get the team players comma seperated
        if ($schedule_type == 'team') {
            $player_a_ids = TeamPlayers::select(DB::raw('GROUP_CONCAT(DISTINCT user_id) AS player_a_ids'))->where('team_id', $a_id)->pluck('player_a_ids');
           
            $player_b_ids = TeamPlayers::select(DB::raw('GROUP_CONCAT(DISTINCT user_id) AS player_b_ids'))->where('team_id', $b_id)->pluck('player_b_ids');
            
        }
        $request_type = ($schedule_type == 'team')?'TEAM_TO_TEAM':'PLAYER_TO_PLAYER';
         $player_a_ids = !empty($player_a_ids)?(','.trim($player_a_ids).','):NULL;
         $player_b_ids = !empty($player_b_ids)?(','.trim($player_b_ids).','):NULL;

         if(!empty($tournament_id)){
                $tournamentDetails=Tournaments::find($tournament_id);
                if(count($tournamentDetails)){
                    $game_type = $tournamentDetails->game_type;
                    $number_of_rubber = $tournamentDetails->number_of_rubber;
                }
         }
        //prepare an array to insert
        $schedule_data = array(
            'tournament_id' => $tournament_id,
            'tournament_group_id' => $tournament_group_id,
            'tournament_round_number' => $tournament_round_number,
            'tournament_match_number' => $tournament_match_number,
            'sports_id' => $sports_id,
            'facility_id' => $facility_id,
            'facility_name' => $facility_name,
            'created_by' => Auth::user()->id,
            'match_category' => $player_type,
            'schedule_type' => $schedule_type,
            'match_type' => $match_type,
            'match_start_date' => $match_start_date,
            'match_start_time' => $match_start_time,
            'match_end_date' => !empty($match_end_date) ? $match_end_date : $match_start_date,
            'match_end_time' => !empty($match_end_time) ? $match_end_time : $match_start_time,
            'match_location' => $match_location,
            'address' => $address,
            'city_id' => $city_id,
            'city' => $city,
            'state_id' => $state_id,
            'state' => $state,
            'country_id' => $country_id,
            'country' => $country,
            'zip' => $zip,
            'match_status' => 'scheduled',
            'a_id' => $a_id,
            'b_id' => $b_id,
            'player_a_ids' => $player_a_ids,
            'player_b_ids' => $player_b_ids,
            'game_type'     => $game_type,
            'number_of_rubber' => $number_of_rubber
        );

        $results = array();
        if (is_numeric($scheduleId)) {
            if (MatchSchedule::where('id', $scheduleId)->update($schedule_data)) {
                $results['success'] = 'Match schedule updated successfully.';
            } else {
                $results['failure'] = 'Failed to update the match schedule.';
            }
        } elseif ($scheduleId == NULL) {
            $match_schedule_result = MatchSchedule::create($schedule_data);
            $match_schedule_id = $match_schedule_result['id'];
            if (isset($match_schedule_id) && $match_schedule_id > 0) {
                  //insert into request and notifications table
                  $request_array = array('flag'=>$request_type,'player_tournament_id'=>$a_id,'team_ids'=>array($b_id),'match_schedule_id'=>$match_schedule_id,'sports_id'=>$sports_id);
                  AllRequests::saverequest($request_array);
                $results['success'] = 'Match scheduled successfully.';
            } else {
                $results['failure'] = 'Failed to schedule the match.';
            }
        } else {
            $results['failure'] = 'Failed to schedule the match.';
        }
        return Response::json($results);
    }

    //function to extract date and time
    public function getdatetime($date_time, $flag) {
        $dt = new DateTime($date_time);
        //flag d is for date
        if ($flag == 'd') {
            return $dt->format('Y-m-d');
        } else {//flag t is for time
            return $dt->format('H:i:s');
        }
    }

    //function to edit schedule
    public function editschedule() { 
        $request = Request::all();
        $scheduleId = Request::get('scheduleId');
        $isOwner = Request::get('isOwner');
        $roundNumber = Request::get('roundNumber');
        $scheduleData = array();
        $matchTypes = array();
        $playerTypes = array();
        $sportType = '';
        $team_a_name = '';
        $team_b_name = '';
        $sport_name = '';
        $teamCount = '';
        $states = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();
        $cities = array();
        if (!empty($isOwner) && is_numeric($scheduleId) && $isOwner) {
            $scheduleData = MatchSchedule::where('id', $scheduleId)->first();
            if (count($scheduleData)) {
                $scheduleData = $scheduleData->toArray();
                $sport_name = Sport::where('id',$scheduleData['sports_id'])->pluck('sports_name');
                 $scheduleData['sport_name'] = $sport_name;
                if (!empty($scheduleData['match_start_date'])) {
                    $scheduleData['match_start_date'] = date(config('constants.DATE_FORMAT.VALIDATION_DATE_FORMAT'), strtotime($scheduleData['match_start_date']));
                }    
                if (!empty($scheduleData['match_start_time']) && $scheduleData['match_start_time']!='00:00:00') {
                    $scheduleData['match_start_time'] = date(config('constants.DATE_FORMAT.VALIDATION_TIME_FORMAT'), strtotime($scheduleData['match_start_time']));
                }
                
                if(!empty($roundNumber)) {
                    
                    if(!empty($scheduleData['tournament_id'])) {
                        
                        if(!empty($scheduleData['a_id'])){
                            $teamCount = 1;
                        }
                        if(!empty($scheduleData['a_id'])){
                            $teamCount = $teamCount+1;
                        }
                    /*  $currentRoundnumber = $scheduleData['tournament_round_number'];
                        $tournamentMatchNumber = $scheduleData['tournament_match_number'];
                        $resultSet = $currentRoundnumber*$tournamentMatchNumber; 
                        $prevMatchOne = intval(floor($resultSet / 2));
                        $prevMatchTwo = $prevMatchOne+1;
                        $searchTeamIdsCollection = MatchSchedule::where('tournament_id',$scheduleData['tournament_id'])->whereNull('tournament_group_id')
                                ->where('tournament_round_number',($currentRoundnumber-1))
                                ->whereIN('tournament_match_number',[$prevMatchOne,$prevMatchTwo])
                                ->orderBy('id')
                                ->get(['winner_id']);
//                        Helper::printQueries();
//                        dd($searchTeamIdsCollection);
                        if(count($searchTeamIdsCollection)) {
                            $i=1;
                            foreach($searchTeamIdsCollection as $collection) {
                                if(isset($collection['winner_id']) && $i==1) {
                                    $scheduleData['a_id'] = $collection['winner_id'];
                                }
                                if(isset($collection['winner_id']) && $i>1) {
                                    $scheduleData['b_id'] = $collection['winner_id'];
                                }
                                $i++;
                            }
                            $teamCount = count($searchTeamIdsCollection);
                        }*/
                    }
                }
                //get sport type
                /*$sportType = Sport::where('id', $scheduleData['sports_id'])->pluck('sports_type');
                if ($sportType == 'team') {
                    $team_a_rec = Team::where('id', $scheduleData['a_id'])->first();
                    $team_a_name = $team_a_rec['name'].' ('.$team_a_rec['team_level'].')';
                    $team_b_rec = Team::where('id', $scheduleData['b_id'])->first();
                    $team_b_name = $team_b_rec['name'].' ('.$team_b_rec['team_level'].')';
                } elseif ($sportType == 'player') {
                    $team_a_name = User::where('id', $scheduleData['player_a_ids'])->pluck('name');
                    $team_b_name = User::where('id', $scheduleData['player_b_ids'])->pluck('name');
                } else {*/
                    if ($scheduleData['schedule_type'] == 'team') {
                        $team_a_rec = Team::where('id', $scheduleData['a_id'])->first();
                        $team_a_name = $team_a_rec['name'].' ('.$team_a_rec['team_level'].')';
                        $team_b_rec = Team::where('id', $scheduleData['b_id'])->first();
                        $team_b_name = $team_b_rec['name'].' ('.$team_b_rec['team_level'].')';
                    } else {
                        $team_a_name = User::where('id', trim($scheduleData['player_a_ids'],','))->pluck('name');
                        $team_b_name = User::where('id', trim($scheduleData['player_b_ids'],','))->pluck('name');
                    }
//                }

                //building match types array
                foreach (config('constants.ENUM.SCHEDULE.MATCH_TYPE') as $key => $val) {
                    $matchTypes[$key] = $val;
                }
                //building player types array
                foreach (config('constants.ENUM.SCHEDULE.PLAYER_TYPE') as $key => $val) {
                    $playerTypes[$key] = $val;
                }

                //get cities
                $cities = ['' => 'Select City'];
                if ($scheduleData['state_id']) {
                    $cities = City::where('state_id', $scheduleData['state_id'])->orderBy('city_name')->lists('city_name', 'id')->all();
                }
            }
        }

        /*      return view('schedules.editschedule', ['scheduleData' => $scheduleData,'match_types'=>$matchTypes,'player_types'=>$playerTypes,'scheduleId'=>$scheduleId,'isOwner'=>$isOwner,'sport_type'=>$sportType,'states'=>$states,'team_a_name'=>$team_a_name,'team_b_name'=>$team_b_name])
          ->with('states', ['' => 'Select State'] + $states)
          ->with('cities', ['' => 'Select City'] + $cities); */
//        dd($scheduleData);
        $managingTeams = Helper::getManagingTeams(Auth::user()->id, $scheduleData['sports_id']);

        $managingTeams = Helper::getManagingTeamsWithTeamLevel(Auth::user()->id,$scheduleData['sports_id']);

        $results = array('scheduleData' => $scheduleData, 'match_types' => $matchTypes, 'player_types' => $playerTypes, 'scheduleId' => $scheduleId, 
                        'isOwner' => $isOwner, 'sport_type' => $sportType, 'states' => $states, 'team_a_name' => $team_a_name, 'team_b_name' => $team_b_name, 
                        'cities' => $cities,'managing_teams'=>$managingTeams,'team_count'=>$teamCount);
        return Response::json($results);
    }
    //fucntion for match types
    public function getmatchandplayertypes()
    {
        $request = Request::all();
        $sportName =  Request::get('sport_name');
        //building match types array

        if(!is_null(Request::get('from_tournament'))){  //if request is from tournaments
            $from_tournament=true;
            $tour='TOURNAMENT_';
        }
        else {
            $from_tournament=false;    //
            $tour='';
        }



        $matchTypes = Helper::getMatchTypes(strtoupper($sportName),$from_tournament);
        $playerTypes = array();
        //building player types array
        foreach (config('constants.ENUM.'.$tour.'SCHEDULE.PLAYER_TYPE') as $key => $val) {
            $playerTypes[$key] = $val;
        }
        return Response::json(['matchTypes'=>$matchTypes,'playerTypes'=>$playerTypes]);
    }
    //fucntion for match types
    public function getstates()
    {
        $states = State::where('country_id', config('constants.COUNTRY_INDIA'))->get();
        // print_r($states->toArray());
        return Response::json(['states'=>$states]);
    }

    public function getcountries()
    {
        $countries = Country::get();
        return Response::json(['countries'=>$countries]);
    }
    //function to get managing teams
    public function getmymanagingteams()
    {
        $request = Request::all();
        $sportId =  Request::get('sport_id');        
        $managingteams = Helper::getManagingTeamsWithTeamLevel(Auth::user()->id,$sportId);
        return Response::json(['managingteams'=>$managingteams]);
    }
    // function to insert record for next round if the result is selected as bye
    public function insertByeTeamDetails($scheduleId) {
            $matchScheduleDetails = MatchSchedule::where('id', $scheduleId)->first();
            $roundNumber = $matchScheduleDetails['tournament_round_number'];
            $matchNumber = $matchScheduleDetails['tournament_match_number'];
            $matchNumberToCheck = ceil($matchNumber / 2);

            $tournamentDetails=Tournaments::find($matchScheduleDetails['tournament_id']);
           
                       //check if corresponding player is there.
$matchScheduleData = MatchSchedule::where('tournament_id',$matchScheduleDetails['tournament_id'])
            ->where('tournament_round_number',$roundNumber+1)
            ->where('tournament_match_number',$matchNumberToCheck)
            ->first();

                       
            if ($matchScheduleDetails['schedule_type'] == 'team') {
                $player_a_ids = TeamPlayers::select(DB::raw('GROUP_CONCAT(DISTINCT user_id) AS player_a_ids'))->where('team_id', $matchScheduleDetails['winner_id'])->pluck('player_a_ids');
            }else {
                $player_a_ids = $matchScheduleDetails['winner_id'];
            }

                //check if corresponding team is in database
            if(count($matchScheduleData)){
                   if(!empty($matchScheduleData->a_id)){
                        $matchScheduleData->b_id=$matchScheduleDetails['winner_id'];
                        $matchScheduleData->player_b_ids=$player_a_ids;
                   }
                   else{
                        $matchScheduleData->a_id=$matchScheduleDetails['winner_id'];
                        $matchScheduleData->player_a_ids=$player_a_ids;
                   }

                   $matchScheduleData->save();
            }
            else{
            $scheduleArray[] = [
                'tournament_id' => $matchScheduleDetails['tournament_id'],
                'tournament_round_number' => $roundNumber+1,
                'tournament_match_number' => $matchNumberToCheck,
                'sports_id' => $matchScheduleDetails['sports_id'],
                'facility_id' => $matchScheduleDetails['facility_id'],
                'facility_name' => $matchScheduleDetails['facility_name'],
                'created_by' => $matchScheduleDetails['created_by'],
                'match_category' => $matchScheduleDetails['match_category'],
                'schedule_type' => $matchScheduleDetails['schedule_type'],
                'match_type' => $matchScheduleDetails['match_type'],
                'match_location' => $matchScheduleDetails['match_location'],
                'city_id' => $matchScheduleDetails['city_id'],
                'city' => $matchScheduleDetails['city'],
                'state_id' => $matchScheduleDetails['state_id'],
                'state' => $matchScheduleDetails['state'],
                'country_id' => $matchScheduleDetails['country_id'],
                'country' => $matchScheduleDetails['country'],
                'zip' => $matchScheduleDetails['zip'],
                'match_status' => 'scheduled',
                'a_id' => $matchScheduleDetails['winner_id'],
                'player_a_ids' => !empty($player_a_ids)?(','.trim($player_a_ids).','):NULL,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'game_type'     => $tournamentDetails->game_type,
                'number_of_rubber' => $tournamentDetails->number_of_rubber
            ];

            $match=MatchSchedule::insert($scheduleArray);

        }
            
    }

    public static function insertGroupRubber($match_id){
        $match_model=MatchSchedule::find($match_id);
        $number_of_rubber = $match_model->number_of_rubber;

        if(!count($match_model->rubbers)){

        for($i=1; $i<=$number_of_rubber; $i++){
            $rubber      = new MatchScheduleRubber;

            $rubber->tournament_id  = $match_model->tournament_id;
            $rubber->tournament_group_id    = $match_model->tournament_group_id;
            $rubber->tournament_round_number = $match_model->tournament_round_number;
            $rubber->tournament_match_number = $match_model->tournament_match_number;
            $rubber->match_id       =   $match_id;
            $rubber->sports_id      =   $match_model->sports_id;
            $rubber->created_at     =   $match_model->created_at;
            $rubber->match_category       =   $match_model->match_category;
            $rubber->schedule_type  =   $match_model->schedule_type;
            $rubber->match_type     =   $match_model->match_type;
            $rubber->match_start_date     =   $match_model->match_start_date;
            $rubber->match_start_time     =   $match_model->match_start_time;
            $rubber->match_end_date     =   $match_model->match_start_date;
            $rubber->match_end_time     =   $match_model->match_start_time;
            $rubber->match_location       =   $match_model->match_location;
            $rubber->match_status   =   'scheduled';
            $rubber->a_id           =   $match_model->a_id;
            $rubber->b_id           =   $match_model->b_id;
            $rubber->player_a_ids   =   $match_model->player_a_ids;
            $rubber->player_b_ids   =   $match_model->player_b_ids;
            $rubber->rubber_number    =   $i;
            $rubber->save();

            // $match_model->hasSetupSquad = 1; 
            // $match_model->save();
        }  
    }

        return MatchScheduleRubber::whereMatchId($match_id)->get();        
       
    }
}
