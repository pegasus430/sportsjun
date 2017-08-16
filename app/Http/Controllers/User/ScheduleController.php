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
use App\Model\TournamentGroups;
use App\Model\MatchScheduleRubber;
use App\Http\Controllers\User\ScoreCard\ArcheryController;
use App\Model\TournamentFinalTeams;
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
                    $query->where('a_id', $teamId)->orWhere('b_id', $teamId)->orWhere('player_or_team_ids', 'like','%'.$teamId.'%');

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
                    $query->where('a_id', $teamId)
                          ->orWhere('b_id', $teamId)
                          ->orWhere('player_or_team_ids', $teamId);
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

        if($sportsId!=18){

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

        //Archery Stats

        $tourStats = '';

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

        if(in_array($sportsId, [18])){
               $statsArray = Helper::getArcheryStats($teamId);
               $matchScheduleData = MatchSchedule::where('player_or_team_ids', 'like', "%$teamId%")->whereNotNull('tournament_id') ->orderby('match_start_date', 'desc')
                ->orderby('match_start_time', 'desc')->get();
        }

        $statsview = 'schedules.'.preg_replace('/\s+/', '',strtolower(config('constants.SPORT_NAME.'.$sportsId))).'statsview';

       // dd($matchScheduleData);

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
// pDos 2017.7.21
     

   

    private function generateLeagueMatching( $TeamN , $G ,  $repeat , $nPlaces  )
    {
        if( $nPlaces <= 0  ) return;
        if( $nPlaces > $TeamN / 2 ) $nPlaces = round( $TeamN / 2 );
        $GN = count($G);
        
        for( $i = 0 ;$i < $GN ; $i++ )
        {
          $tN = count($G[$i]['team']);

          $isOdd = $tN % 2;
          $tN2 = $tN + $isOdd;

          // initial state of players
          //generate match
          $k = 0;
          for( $j = 0 ; $j < $tN2 - 1 ; $j++ ) // rotate : https://en.wikipedia.org/wiki/Round-robin_tournament
          {
            if( $j == 0 ) for($p = 0; $p < $tN2 ; $p++) $pos[$p] = $p;
            else { // rotate it
              $save_id = $pos[$tN2 - 1];
              for($p = $tN2 - 1 ; $p >= 2 ; $p--)
                $pos[$p] = $pos[$p-1];
              $pos[1] = $save_id;
            }
            // not organize it.
            for( $p = 0 ; $p < $tN / 2 ; $p++ )
            {
              $ii = $pos[$p];
              $jj = $pos[$tN2 - 1 - $p];
              if( $isOdd && ( ($ii == $tN2 - 1) || ($jj == $tN2 - 1) ) )
                continue;
              $match[$i][$k] = array( 'group' => $i , 'left' => $ii , 'right' => $jj );
              $k++;
            }
          }

        if( $k > 0 )
        {
            $matchN = count($match[$i]);

            for( $j = 1 ; $j < $repeat  ; $j++ )
            for( $q = 0 ; $q < $matchN ; $q++ )
                $match[$i][$j*$matchN+$q] = $match[$i][$q];
        }

        

        }// team iterator
       

        //// contructing table with stadium and timeline

        $found = true;
        $iPlace = 0;
        $iTimes = 0;
        $iG = -1;
        for( $i = 0 ;$i < $GN ; $i++ ) $GCur[$i] = 0;
        while($found)
        {
            $found = false;
            for($k = 0 ; $k < $GN ; $k++)
            {
                $iG = ( $iG + 1 ) % $GN;
                if( isset($match[$iG]) && $GCur[$iG] < count( $match[$iG] ) )
                {
                    $found = true;
                    $selMatch = $match[$iG][$GCur[$iG]];
                    $GCur[$iG]++;

                    $timeline[$iTimes][$iPlace] = $selMatch;
                    $iPlace = ( $iPlace + 1 ) % $nPlaces;
                    if( $iPlace == 0 ) $iTimes++;
                    break;
                }
            }
        }
         
        return $timeline;
    }

    private function getPlayerListofTeam( $team_id )
    { 
        //if schedule type is team then get the team players comma seperated
         $player_ids = TeamPlayers::select(DB::raw('GROUP_CONCAT(DISTINCT user_id) AS player_ids'))->where('deleted_at',null)->where('team_id', $team_id)->pluck('player_ids');

         return !empty($player_ids)?(','.trim($player_ids).','):NULL; 
    }


    private function generateDoubleElimination( $TeamN , $nPlaces )
    {
       
            $wingo = $this->build_first_second_line( $TeamN );

            // (win_lose , round number , match number) => 
        ///////////////////////////////////////////////// loser course construction /////////////////////////////////////////////////
            
            // we construct loser game first because of loser hash ( pointor from winner to loser )
            // loser game course for double elimination
            $loserstartN = $wingo['firstMatchCount'];

            
            if( $wingo['NN'] == $TeamN ) // use lose start round with one line
            {
                $useoneline = true;            
            } 
            else 
            {
                $useoneline = false;
                $loserstartN += $wingo['NN'] / 4;   // $wingo['NN'] / 2 : number of team in second line , 
                                                    // $wingo['NN'] / 4 : number of match in second line
            }
    
            $losego = $this->build_first_second_line( $loserstartN ); 

            $match_cur = 0;
            $loser_round_no = 1; 
            $loser_hash = array(); // $loser_hash[n]: second round no of having n match in lose course
            $loser_duplicate_start = ( $wingo['NN'] == $TeamN ) ?  $wingo['NN'] / 4 : $wingo['NN'] / 8; 
                                    // $wingo['NN'] / 4 : match count of second line 
                                    // $wingo['NN'] / 8 : match count of third line  

            $loser_rmHash = array();

            
            for( $i = $losego['level'] ; $i > 0 ; $i-- ) // $i >0 , loser course doen't require last one match
            {
                $p = pow( 2 , $i - 1 );
                // build losener side
                for( $j = 0 ; $j < $p ; $j++ )
                {
                    // create box with 2 * j , 2* j + 1
                    $T1 = -1;
                    $T2 = -1;
                    if( $i == $losego['level'] )     { $T1 =  $losego['fline'][ $j * 2 ] ; $T2 = $losego['fline'][ $j * 2 + 1 ]; }
                    if( $i == $losego['level'] - 1 ) { $T1 =  $losego['sline'][ $j * 2 ] ; $T2 = $losego['sline'][ $j * 2 + 1 ]; }
                    if( !( $i == $losego['level'] && $T1 == -1 && $T2 == -1 ) )
                    {
                        $match_lose[ $match_cur ]['left'] = $T1;
                        $match_lose[ $match_cur ]['right'] = $T2;
                        $match_lose[ $match_cur ]['round_no'] = $loser_round_no ;
                        $match_lose[ $match_cur ]['match_no'] = $j + 1;
                        $match_lose[ $match_cur ]['double_wl_type']='l';
                        $loser_rmHash[ $loser_round_no ][ $j + 1 ] = $match_cur ;
                        

                        if( !isset($H['lose'][$loser_round_no]) )
                            $H['lose'][$loser_round_no] = array();

                        array_push( $H['lose'][$loser_round_no] , $match_cur );
                                

                        if( $i >= 1 ) // $i >= 1 is important, last game added
                        {
                            if( $losego['NN'] != $loserstartN && $i == $losego['level'] ) // first case , go normal method for next position
                            {
                                $next_match_no = floor( $j / 2 ) + 1;
                                $match_lose[ $match_cur ]['winner'] = array( 'next_round_no' =>  $loser_round_no + 1 ,
                                                                        'next_match_no' =>  $next_match_no , 
                                                                        'next_match_ab_pos' =>  $j % 2  ?  'b' : 'a',
                                                                        'go_wl_type' => 'l'
                                                                        ); 
                            }
                            else
                            {
                                $next_match_no = $j;
                                $match_lose[ $match_cur ]['winner'] = array( 'next_round_no' =>  $loser_round_no + 1 ,
                                                                        'next_match_no' =>  $next_match_no ,
                                                                        'next_match_ab_pos' =>  'b',  // always position b
                                                                        'go_wl_type' => 'l'
                                                                        ); 

                            }
                        }
                        $match_cur++;
                    }
                }
                $loser_round_no++;

                if(  $loser_duplicate_start >= $p ) 
                // start duplication of loser game
                {
                    for( $j = 0 ; $j < $p ; $j++ )
                    {
                        // create box with 2 * j , 2* j + 1
                        $T1 = -1;
                        $T2 = -1;
                        
                        $match_lose[ $match_cur ]['left'] = $T1;
                        $match_lose[ $match_cur ]['right'] = $T2;
                        $match_lose[ $match_cur ]['round_no'] = $loser_round_no ;
                        $match_lose[ $match_cur ]['match_no'] = $j + 1;
                        $match_lose[ $match_cur ]['double_wl_type']='l';
                        $loser_rmHash[ $loser_round_no ][ $j + 1 ] = $match_cur ;

                        if( !isset($H['lose'][$loser_round_no]) )
                            $H['lose'][$loser_round_no] = array();

                        array_push( $H['lose'][$loser_round_no] , $match_cur );

                        $next_match_no = floor( $j / 2 ) + 1;
                        $match_lose[ $match_cur ]['winner'] = array( 'next_round_no' =>  $loser_round_no + 1 ,
                                                                'next_match_no' =>  $next_match_no , 
                                                                'next_match_ab_pos' =>  $j % 2  ?  'b' : 'a' , 
                                                                'go_wl_type' => 'l' );
                        $match_cur++; 
                    }
                    //only applied for second 
                    $loser_hash[$p] =  $loser_round_no;
                    $loser_round_no++;
                }
            }

        


            ///////////////////////////////////////////////// winner course construction /////////////////////////////////////////////////

            $match_cur = 0;
            $winner_round_no = 1;
            
            
            // winner course 

            for( $i = $wingo['level'] ; $i >= 0 ; $i-- ) // $i >=0 is important , because double elimination requires last one more game
            {
                $p = pow( 2 , $i - 1 );
                // build winner side
                for( $j = 0 ; $j < $p ; $j++ )
                {
                    // create box with 2 * j , 2* j + 1
                    $T1 = -1;
                    $T2 = -1;
                    if( $i == $wingo['level'] )     { $T1 =  $wingo['fline'][ $j * 2 ] ; $T2 = $wingo['fline'][ $j * 2 + 1 ]; }
                    if( $i == $wingo['level'] - 1 ) { $T1 =  $wingo['sline'][ $j * 2 ] ; $T2 = $wingo['sline'][ $j * 2 + 1 ]; }
                    if( !( $i == $wingo['level'] && $T1 == -1 && $T2 == -1 ) )
                    {
                        $match_win[ $match_cur ]['left'] = $T1;
                        $match_win[ $match_cur ]['double_wl_type']='w';
                        $match_win[ $match_cur ]['right'] = $T2;
                        $match_win[ $match_cur ]['round_no'] = $winner_round_no ;
                        $match_win[ $match_cur ]['match_no'] = $j + 1;
                        

                        if( !isset($H['win'][$winner_round_no]) )
                                $H['win'][$winner_round_no] = array();

                        array_push( $H['win'][$winner_round_no] , $match_cur ); 

                        if( $i >= 1 ) // $i >= 1 is important, last game added
                        {
                            $next_match_no = floor( $j / 2 ) + 1;
                            $match_win[ $match_cur ]['winner'] = array( 'next_round_no' =>  $winner_round_no + 1 ,
                                                                    'next_match_no' =>  $next_match_no , 
                                                                    'next_match_ab_pos' =>  $j % 2  ?  'b' : 'a',
                                                                    'go_wl_type' => 'w' );

                            $match_win[ $match_cur ]['loser'] = array( 'next_round_no' =>  isset($loser_hash[$p]) ? $loser_hash[$p] : 0 ,
                                                                    'next_match_no' =>  $j + 1 , 
                                                                    'next_match_ab_pos' =>  'a', // always go a of loser team
                                                                    'go_wl_type' => 'l' );

                        }
                        $match_cur++;
                    }
                }
                $winner_round_no++;
            }

            // $loserstartN , (first match corresponding)


            $j = 0;
            for( $i = 0 ; $i < $loserstartN ; $i++ )
            {
                while( $j < $loserstartN )
                {
                    if( $match_lose[$j]['left'] != -1 || $match_lose[$j]['right'] != -1 )
                    {
                        $match_win[$i]['loser']['next_round_no'] = $match_lose[$j]['round_no'];
                        $match_win[$i]['loser']['next_match_no'] = $match_lose[$j]['match_no'];
                        if(  $match_lose[$j]['left'] != -1 )
                        {
                            $match_win[$i]['loser']['next_match_ab_pos'] = 'a';
                            $match_lose[$j]['left']  = -1;
                        }
                        else 
                        {
                            $match_win[$i]['loser']['next_match_ab_pos'] = 'b';
                            $match_lose[$j]['right'] = -1;
                        }
                        break;
                    }
                    else $j++;
                }
            }
            

            // loser mark for loser team

            // echo "<pre>";
            // print_r($match_win);
            // echo "</pre>";

            if( isset($match_win) )
            for( $i = 0 ; $i < count($match_win) ; $i++)
                if( isset($match_win[ $i ]['loser']) )
                {
                    $lrno = $match_win[ $i ]['loser']['next_round_no'];
                    $lmno = $match_win[ $i ]['loser']['next_match_no'];
                    $labpos = $match_win[ $i ]['loser']['next_match_ab_pos'];
                    if( $lrno > 0 && $lmno > 0 && isset( $loser_rmHash[ $lrno ][ $lmno ] ) )
                    {
                        $match_cur = $loser_rmHash[ $lrno ][ $lmno ];
                        $enable_loser_match[ $match_cur ][ $labpos ] = 'open'; // mark position of loser of winner course in $match_lose[$match_cur] ,
                    }
                        // 
                }



    //       echo "<pre>";
    //       // print_r($match_win);
    //       print_r($loser_rmHash);
    //       echo "</pre>";
    // //      die;
    
            // place and time
            $iPlace = 0;
            $iTimes = 0;
            $winner_round_cur = 1;
            $loser_round_cur  = 1;
            while( $winner_round_cur < $winner_round_no || $loser_round_cur < $loser_round_no )
            {
                //try winner 1 round
                if( isset( $H['win'][$winner_round_cur] ) )
                {
                    foreach( $H['win'][$winner_round_cur] as $key => $match_cur )
                    {
                        $timeline[$iTimes][$iPlace] = $match_win[ $match_cur ];
                        // Mark Loser Match , can processed
                        if( isset( $match_win[ $match_cur ]['loser']['next_round_no'] )) // winner course last game should be disappeared
                        {
                            $loser_next_round_no =  $match_win[ $match_cur ]['loser']['next_round_no'];
                            $loser_next_match_no =  $match_win[ $match_cur ]['loser']['next_match_no'];
                            if( !isset($loser_rmHash[ $loser_next_round_no ][ $loser_next_match_no ]) )
                                echo  $loser_next_round_no."--".$loser_next_match_no."<br>";
                            $loser_next_cur = $loser_rmHash[ $loser_next_round_no ][ $loser_next_match_no ];
                            $enable_loser_match[ $loser_next_cur ][ $match_win[ $match_cur ]['loser']['next_match_ab_pos'] ] = 'closed';
                        }
                        
                        $iPlace++;
                        if( $iPlace == $nPlaces ){
                            $iPlace = 0;
                            $iTimes++;
                        }
                    }
                    $winner_round_cur++;
                }
                else break;

                //try loser as much round as possible
                while( $loser_round_cur < $loser_round_no )
                {
                    $passFlag = true;
                    if( isset( $H['lose'][$loser_round_cur] ) ) // checking
                        foreach( $H['lose'][$loser_round_cur] as $key => $match_cur )
                        {
                            if( ( isset( $enable_loser_match[$match_cur]['a'] ) && $enable_loser_match[$match_cur]['a'] == 'open') || 
                             ( isset( $enable_loser_match[$match_cur]['b'] ) && $enable_loser_match[$match_cur]['b'] == 'open' ) )  
                            // means  || $enable_loser_match[$match_cur] != 1
                                {
                                    $passFlag = false;
                                    break;
                                }
                        }

                    if( !$passFlag ) break;

                    foreach( $H['lose'][$loser_round_cur] as $key => $match_cur )
                    {
                        $timeline[$iTimes][$iPlace] = $match_lose[ $match_cur ];
                        $iPlace++;
                        if( $iPlace == $nPlaces ){
                            $iPlace = 0;
                            $iTimes++;
                        }
                    }
                    $loser_round_cur++;
                }
            }
            // echo "<pre>";
            // print_r($enable_loser_match);
            // echo "</pre>";

            return $timeline;
    }


   

    public function JsonOutputScheduleKnockoutDouble($tournament_id)
    {
        foreach( array( 'w' , 'l' ) as $key => $WH )
        {

            $matchschedule = MatchSchedule::where('tournament_id',$tournament_id)->where( 'double_wl_type' , $WH )->orderBy('tournament_round_number')->orderBy('tournament_match_number')->get();

            $roundno = 0;
            $matchno = 0;
            $units = Array();

            $fields = Array('id','tournament_id','tournament_round_number','tournament_match_number','sports_id','match_category','schedule_type','match_type','match_start_date','a_id'
            ,'b_id','winner_id','is_tied','a_score','b_score','winner_schedule_id' , 'winner_schedule_position' , 'winner_go_wl_type' , 'loser_schedule_id' , 'loser_schedule_position' ,'loser_go_wl_type' , 'double_wl_type' );

            foreach( $matchschedule as $r )
            {
                $roundno = max( $r['tournament_round_number'] , $roundno );
                $matchno = max( $r['tournament_match_number'] , $matchno );
                foreach( $fields as $f )
                    $fr[$f] = $r[$f];

                $fr['team_name_a'] = $fr['a_id'] > 0 ? Team::where( 'id', $fr['a_id'] )->first()->name : '' ;
                $fr['team_name_b'] = $fr['b_id'] > 0 ? Team::where( 'id', $fr['b_id'] )->first()->name : '' ;
                
                array_push( $units , $fr );
            }
            
            $results[$WH]['success'] = 'Match scheduled successfully.';
            $results[$WH]['roundno'] = $roundno;
            $results[$WH]['matchno'] = $matchno;
            $results[$WH]['units']   = $units;
        }

        return Response::json($results);

    }

 
    public function generateScheduleKnockoutDouble(Requests\GenerateMatchRequest $request)
    {
        $tournament_id = $request['tournament_id_save'];
      
        MatchSchedule::where('tournament_id',$tournament_id)->where('is_knockout' , $request['is_knockout'])->delete();
        Tournaments::where('id',$tournament_id)->update(array('noofplaces'=>$request['noofplaces'] , 'roundofplay' => $request['roundofplay'] ));
   

        $tournament       = Tournaments::where('id',$tournament_id)->first();
        $tournamentFinalTeams  = TournamentFinalTeams::where('tournament_id',$tournament_id)->get();

        $timeline = $this->generateDoubleElimination( count($tournamentFinalTeams) , $tournament['noofplaces']  );
        $timePoints = $this->MakeTimeList( $request , $tournament['start_date'] ,  count( $timeline ) );
 
        for( $i = 0 ; $i < count( $timeline ) ; $i++ )
            for( $j = 0 ; $j <  $tournament['noofplaces'] ; $j++ )
            {

                if( !isset( $timeline[$i][$j] ) ) continue;

                $A = $timeline[$i][$j]['left']  != -1 ? $tournamentFinalTeams[ $timeline[$i][$j]['left' ] ] : array('team_id' => 0 );
                $B = $timeline[$i][$j]['right'] != -1 ? $tournamentFinalTeams[ $timeline[$i][$j]['right'] ] : array('team_id' => 0 );
                
                $schedule_data = array(
                    'tournament_id' => $tournament_id,
                    'tournament_round_number' => $timeline[$i][$j]['round_no'] ,
                    'tournament_match_number' => $timeline[$i][$j]['match_no'] ,
                    'sports_id' => $tournament['sports_id'],
                    'facility_id' => $tournament['facility_id'],
                    'facility_name' =>  $tournament['facility_name'],
                    'created_by' => Auth::user()->id,
                    'match_category' => $tournament['player_type'],
                    'schedule_type' => $tournament['schedule_type'],
                    'match_type' => $tournament['match_type'],
                    'match_start_date' => $timePoints[$i]['match_start_date'],
                    'match_start_time' => $timePoints[$i]['match_start_time'],
                    'match_end_date' => $timePoints[$i]['match_end_date'],
                    'match_end_time' => $timePoints[$i]['match_end_time'],
                    'match_location' => $tournament['location'],
                    'address' => $tournament['address'],
                    'city_id' => $tournament['city_id'],
                    'city' => $tournament['city'],
                    'state_id' => $tournament['state_id'],
                    'state' => $tournament['state'],
                    'country_id' => $tournament['country_id'],
                    'country' => $tournament['country'],
                    'zip' => $tournament['zip'],
                    'match_status' => 'scheduled',
                    'a_id' => $A['team_id'],
                    'b_id' => $B['team_id'],
                    'player_a_ids' => $this->getPlayerListofTeam($A['team_id']),
                    'player_b_ids' => $this->getPlayerListofTeam($B['team_id']),
                    'match_invite_status'=> 'pending',
                    'game_type'     => $tournament['game_type'],
                    'number_of_rubber' => $tournament['number_of_rubber'],
                    'sports_category'  =>  Sport::find( $tournament['sports_id'])->sports_category , 
                    'winner_schedule_position' => isset($timeline[$i][$j]['winner']['next_match_ab_pos']) ? $timeline[$i][$j]['winner']['next_match_ab_pos'] : '' ,
                    'winner_go_wl_type' => isset( $timeline[$i][$j]['winner']['go_wl_type'] ) ? $timeline[$i][$j]['winner']['go_wl_type'] : '' ,
                    'loser_schedule_position' => isset( $timeline[$i][$j]['loser']['next_match_ab_pos'] ) ? $timeline[$i][$j]['loser']['next_match_ab_pos'] : '' ,
                    'loser_go_wl_type' => isset( $timeline[$i][$j]['loser']['go_wl_type'] ) ? $timeline[$i][$j]['loser']['go_wl_type'] : '' ,
                    'double_wl_type' => $timeline[$i][$j]['double_wl_type'],
                    'is_knockout' => 1
                );
                

                // echo "<pre>";
                // print_r($schedule_data);
                // echo "</pre>";


                $match_schedule_result = MatchSchedule::create($schedule_data);
 
 
                $timeline[$i][$j]['id'] = $match_schedule_result->id;
                $hash[$timeline[$i][$j]['double_wl_type']][ $timeline[$i][$j]['round_no'] ][ $timeline[$i][$j]['match_no'] ] = $match_schedule_result->id;
            }

        for( $i = 0 ; $i < count( $timeline ) ; $i++ )
            for( $j = 0 ; $j <  $tournament['noofplaces'] ; $j++ )
            {
                if( isset( $timeline[$i][$j]['winner'] ) ) 
                {
                    $next_round_no      = $timeline[$i][$j]['winner']['next_round_no'];
                    $next_match_no      = $timeline[$i][$j]['winner']['next_match_no']; 
                    $go_wl_type         = $timeline[$i][$j]['winner']['go_wl_type'];

                    if( isset( $hash[$go_wl_type][$next_round_no][$next_match_no] ) )
                        MatchSchedule::where('id', $timeline[$i][$j]['id'] )->update( array( 'winner_schedule_id' => $hash[$go_wl_type][$next_round_no][$next_match_no] ) );
                }

                if( isset( $timeline[$i][$j]['loser'] ) ) 
                {
                    $next_round_no      = $timeline[$i][$j]['loser']['next_round_no'];
                    $next_match_no      = $timeline[$i][$j]['loser']['next_match_no'];
                    $go_wl_type         = $timeline[$i][$j]['loser']['go_wl_type'];


                    if( isset( $hash[$go_wl_type][$next_round_no][$next_match_no] ) )
                        MatchSchedule::where('id', $timeline[$i][$j]['id'] )->update( array( 'loser_schedule_id' => $hash[$go_wl_type][$next_round_no][$next_match_no] ) );
                }
            }

        $results['success'] = 'Match scheduled successfully.';

        return Response::json($results);
    }

 
    private function build_first_second_line( $TeamN )
    {
        $level = log($TeamN) / log(2);
        if( $level != round($level) ) $level = ceil($level);
//         console.log( "teamcount and level"  , teamcount , level );

        $pos = array();
        $npos = array();
        
        $NN = pow( 2 , $level );
         
        for( $i = 0 ; $i < $NN / 2 ; $i++ )
        {
            $pos[ $i * 2 ]        = $i;
            $pos[ $i * 2 + 1 ]    = $NN - $i - 1 >= $TeamN ? -1 : $NN - $i - 1 ; // -1 means blank
            $npos[$i] = -1;
        }

        for( $j = 0 ; $j < $TeamN ; $j++ )
        {
            $a =  rand( 0 , $NN / 2 - 1 ) ;
            $b =  rand( 0 , $NN / 2 - 1 ) ;

            $c       = $pos[$a * 2];
            $pos[$a * 2]  = $pos[$b * 2];
            $pos[$b * 2]  = $c;

            $c = $pos[$a * 2 + 1];
            $pos[$a * 2 + 1]  = $pos[$b * 2 + 1];
            $pos[$b * 2 + 1]  = $c;
        }

        $firstMatchCount = 0;

         // bye action
        for( $i = 0 ; $i < $NN / 2 ; $i++ )
        {
            if( $pos[ $i * 2 + 1 ] == -1  )
            {
                $npos[$i] = $pos[ $i * 2 ];
                $pos[ $i * 2 ] = -1;
            }
            else $firstMatchCount++;
        }

        $res['fline'] = $pos;
        $res['sline'] = $npos;
        $res['level'] = $level;
        $res['NN'] = $NN;
        $res['firstMatchCount'] = $firstMatchCount;

        return $res;
    }


    
        

        // match information with level , pos , teamA, temaB


        

    private function generateSingleElimination( $TeamN  , $nPlaces )
    {
        $R = $this->build_first_second_line($TeamN);

        $level = $R['level'];
        $NN = $R['NN'];
        $pos = $R['fline'];
        $npos = $R['sline'];
          
        // match information with level , pos , teamA, temaB

        $match_cur = 0;
        for( $i = $level ; $i > 0 ; $i-- )
        {
            $p = pow( 2 , $i );
            for( $j = 0 ; $j < $p / 2 ; $j++ )
            {
                // create box with 2 * j , 2* j + 1
                $T1 = -1;
                $T2 = -1;
                if( $i == $level )     { $T1 =  $pos[ $j * 2 ] ; $T2 =  $pos[ $j * 2 + 1 ]; }
                if( $i == $level - 1 ) { $T1 = $npos[ $j * 2 ] ; $T2 = $npos[ $j * 2 + 1 ]; }
                if( $i == $level && $T1 == -1 && $T2 == -1 ) continue;

                $match[ $match_cur ]['left'] = $T1;
                $match[ $match_cur ]['right'] = $T2;
                $match[ $match_cur ]['round_no'] = $level - $i + 1 ;
                $match[ $match_cur ]['match_no'] = $j + 1;

                if( $i > 1 )
                {
                    $next_match_no = floor( $j / 2 ) + 1;
                    $match[ $match_cur ]['winner'] = array( 'next_round_no' =>  $level - $i + 2 ,
                                                            'next_match_no' =>  $next_match_no , 
                                                            'next_match_ab_pos' =>  $j % 2  ?  'b' : 'a' );
                }
                $match_cur++;
            }
        }
 
        // place and time
        $iPlace = 0;
        $iTimes = 0;
        for( $i = 0 ; $i < $match_cur ; $i++ )
        {
            $timeline[$iTimes][$iPlace] = $match[$i];
            $iPlace++;
            if( $iPlace == $nPlaces ){
                $iPlace = 0;
                $iTimes++;
            }
        }

        return $timeline;
    }


    public function JsonOutputScheduleKnockout($tournament_id)
    {
        $matchschedule = MatchSchedule::where('tournament_id',$tournament_id)->orderBy('tournament_round_number')->orderBy('tournament_match_number')->get();

        $roundno = 0;
        $matchno = 0;
        $units = Array();

        $fields = Array('id','tournament_id','tournament_round_number','tournament_match_number','sports_id','match_category','schedule_type','match_type','match_start_date','a_id'
        ,'b_id','winner_id','is_tied','a_score','b_score');

        foreach( $matchschedule as $r )
        {
            $roundno = max( $r['tournament_round_number'] , $roundno );
            $matchno = max( $r['tournament_match_number'] , $matchno );
            foreach( $fields as $f )
                $fr[$f] = $r[$f];

            $fr['team_name_a'] = $fr['a_id'] > 0 ? Team::where( 'id', $fr['a_id'] )->first()->name : '' ;
            $fr['team_name_b'] = $fr['b_id'] > 0 ? Team::where( 'id', $fr['b_id'] )->first()->name : '' ;
            
            array_push( $units , $fr );
        }

        $results['success'] = 'Match scheduled successfully.';
        $results['roundno'] = $roundno;
        $results['matchno'] = $matchno;
        $results['units']   = $units;

        return Response::json($results);
    }

    private function MakeTimeList( $D , $start_date , $nRows )
    {
        $i = 0 ;
        
        $daystart   = strtotime($start_date." ".$D['auto_start_time']);
        $dayend     = strtotime($start_date." ".$D['auto_end_time']); 
        $daymatchcount = 0;
        $timecurrent = $daystart;

        $R = array();
        $Delta = $D['breakeachmatch'] * 1 + $D['minutespermatch'];

        while( $i < $nRows )
        {
            $r['match_start_date']  = date("Y-m-d" , $timecurrent );
            $r['match_start_time']  = date("H:i:s" , $timecurrent );
            $r['match_end_date']    = date("Y-m-d" , strtotime( "+".$D['minutespermatch']." minutes" , $timecurrent ) );
            $r['match_end_time']    = date("H:i:s" , strtotime( "+".$D['minutespermatch']." minutes" , $timecurrent ) );

            array_push( $R , $r );

            $timecurrent = strtotime( "+".$Delta." minutes" , $timecurrent );
            $daymatchcount++;

            if( $timecurrent > $dayend || $daymatchcount >= $D['matchperday'] )
            {
                $dayend = strtotime('+1 day', $dayend );
                $daystart= strtotime('+1 day', $daystart );
                $timecurrent = $daystart;
                $daymatchcount = 0;
            }
            // increase time table
            $i++;
        }

        return $R;
    }

    public function matchScheduleExistCheck( $tournament_id , $is_knockout )
    {     
        $results['match_count'] = count( MatchSchedule::where('tournament_id',$tournament_id)->where('is_knockout',$is_knockout)->get() ) ;
        return Response::json($results);
    } 

    public function generateScheduleKnockout(Requests\GenerateMatchRequest $request)
    {
        $tournament_id = $request['tournament_id_save'];
        MatchSchedule::where('tournament_id',$tournament_id)->where('is_knockout' , $request['is_knockout'])->delete();

        Tournaments::where('id',$tournament_id)->update(array('noofplaces'=>$request['noofplaces'] , 'roundofplay' => $request['roundofplay'] ));

        $tournament       = Tournaments::where('id',$tournament_id)->first();
        $tournamentFinalTeams  = TournamentFinalTeams::where('tournament_id',$tournament_id)->get();
        

        $timeline = $this->generateSingleElimination( count($tournamentFinalTeams) , $tournament['noofplaces'] );
        $timePoints = $this->MakeTimeList( $request , $tournament['start_date'] ,  count( $timeline ) );

        for( $i = 0 ; $i < count( $timeline ) ; $i++ )
            for( $j = 0 ; $j <  $tournament['noofplaces'] ; $j++ )
            { 
                if( !isset( $timeline[$i][$j] ) ) continue;

                $A = $timeline[$i][$j]['left']  != -1 ? $tournamentFinalTeams[ $timeline[$i][$j]['left' ] ] : array('team_id' => 0 );
                $B = $timeline[$i][$j]['right'] != -1 ? $tournamentFinalTeams[ $timeline[$i][$j]['right'] ] : array('team_id' => 0 );
                
                $schedule_data = array(
                    'tournament_id' => $tournament_id,
                    'tournament_round_number' => $timeline[$i][$j]['round_no'] ,
                    'tournament_match_number' => $timeline[$i][$j]['match_no'] ,
                    'sports_id' => $tournament['sports_id'],
                    'facility_id' => $tournament['facility_id'],
                    'facility_name' =>  $tournament['facility_name'],
                    'created_by' => Auth::user()->id,
                    'match_category' => $tournament['player_type'],
                    'schedule_type' => $tournament['schedule_type'],
                    'match_type' => $tournament['match_type'],
                    'match_start_date' => $timePoints[$i]['match_start_date'],
                    'match_start_time' => $timePoints[$i]['match_start_time'],
                    'match_end_date' => $timePoints[$i]['match_end_date'],
                    'match_end_time' => $timePoints[$i]['match_end_time'],
                    'match_location' => $tournament['location'],
                    'address' => $tournament['address'],
                    'city_id' => $tournament['city_id'],
                    'city' => $tournament['city'],
                    'state_id' => $tournament['state_id'],
                    'state' => $tournament['state'],
                    'country_id' => $tournament['country_id'],
                    'country' => $tournament['country'],
                    'zip' => $tournament['zip'],
                    'match_status' => 'scheduled',
                    'a_id' => $A['team_id'],
                    'b_id' => $B['team_id'],
                    'player_a_ids' => $this->getPlayerListofTeam($A['team_id']),
                    'player_b_ids' => $this->getPlayerListofTeam($B['team_id']),
                    'match_invite_status'=> 'pending',
                    'game_type'     => $tournament['game_type'],
                    'number_of_rubber' => $tournament['number_of_rubber'],
                    'sports_category'  =>  Sport::find( $tournament['sports_id'])->sports_category,
                    'winner_schedule_position' =>  isset($timeline[$i][$j]['winner']['next_match_ab_pos']) ? $timeline[$i][$j]['winner']['next_match_ab_pos'] : '' ,
                    'is_knockout' => 1
                );

                $match_schedule_result = MatchSchedule::create($schedule_data);
 
                $timeline[$i][$j]['id'] = $match_schedule_result->id;
                $hash[ $timeline[$i][$j]['round_no'] ][ $timeline[$i][$j]['match_no'] ] = $match_schedule_result->id;
            }

        for( $i = 0 ; $i < count( $timeline ) ; $i++ )
            for( $j = 0 ; $j <  $tournament['noofplaces'] ; $j++ )
                if( isset( $timeline[$i][$j]['winner'] ) ) 
                {
                    $next_round_no      = $timeline[$i][$j]['winner']['next_round_no'];
                    $next_match_no      = $timeline[$i][$j]['winner']['next_match_no'];

                    if( isset( $hash[$next_round_no][$next_match_no] ) )
                        MatchSchedule::where('id', $timeline[$i][$j]['id'] )->update( array( 'winner_schedule_id' => $hash[$next_round_no][$next_match_no] ) );
                }

        $results['success'] = 'Match scheduled successfully.';

        return Response::json($results);
    }

    public function generateScheduleLeague(Requests\GenerateMatchRequest $request)
    {
        $tournament_id = $request['tournament_id_save'];

        MatchSchedule::where('tournament_id',$tournament_id)->where('is_knockout' , $request['is_knockout'])->delete();
  
        Tournaments::where('id',$tournament_id)->update(array('noofplaces'=>$request['noofplaces'] , 'roundofplay' => $request['roundofplay'] ));

        $tournament       = Tournaments::where('id',$tournament_id)->first();
        $tournamentGroups = TournamentGroups::where('tournament_id',$tournament_id)->get();

        if( count($tournamentGroups) == 0 ) return;
        $no_of_teams = 0;
        
        for( $i = 0 ; $i < count($tournamentGroups) ; $i++ )
        {
          $tournamentGroups[$i]['team'] = TournamentGroupTeams::where('tournament_group_id',$tournamentGroups[$i]['id'])->get();
          $no_of_teams += count( $tournamentGroups[$i]['team'] );
        }
  
        $timeline = $this->generateLeagueMatching( $no_of_teams , $tournamentGroups  , $request['roundofplay'], $request['noofplaces'] );
        $timePoints = $this->MakeTimeList( $request , $tournament['start_date'] ,  count( $timeline ) );

        for( $i = 0 ; $i < count( $timeline ) ; $i++ )
            for( $j = 0 ; $j <  $tournament['noofplaces'] ; $j++ )
            {
                if( !isset( $timeline[$i][$j] ) ) continue;

                $A = $tournamentGroups[$timeline[$i][$j]['group']]['team'][ $timeline[$i][$j]['left'] ];
                $B = $tournamentGroups[$timeline[$i][$j]['group']]['team'][ $timeline[$i][$j]['right'] ];
                
                $schedule_data = array(
                    'tournament_id' => $tournament_id,
                    'tournament_group_id' => $tournamentGroups[$timeline[$i][$j]['group']]['id'],
                    // 'tournament_round_number' => $tournament_round_number,  those values are null for roundtrip
                    // 'tournament_match_number' => $tournament_match_number,
                    'sports_id' => $tournament['sports_id'],
                    'facility_id' => $tournament['facility_id'],
                    'facility_name' =>  $tournament['facility_name'],
                    'created_by' => Auth::user()->id,
                    'match_category' => $tournament['player_type'],
                    'schedule_type' => $tournament['schedule_type'],
                    'match_type' => $tournament['match_type'],
                    'match_start_date' => $timePoints[$i]['match_start_date'],
                    'match_start_time' => $timePoints[$i]['match_start_time'],
                    'match_end_date' => $timePoints[$i]['match_end_date'],
                    'match_end_time' => $timePoints[$i]['match_end_time'],
                    'match_location' => $tournament['location'],
                    'address' => $tournament['address'],
                    'city_id' => $tournament['city_id'],
                    'city' => $tournament['city'],
                    'state_id' => $tournament['state_id'],
                    'state' => $tournament['state'],
                    'country_id' => $tournament['country_id'],
                    'country' => $tournament['country'],
                    'zip' => $tournament['zip'],
                    'match_status' => 'scheduled',
                    'a_id' => $A['team_id'],
                    'b_id' => $B['team_id'],
                    'player_a_ids' => $this->getPlayerListofTeam($A['team_id']),
                    'player_b_ids' => $this->getPlayerListofTeam($B['team_id']),
                    'match_invite_status'=> 'pending',
                    'game_type'     => $tournament['game_type'],
                    'number_of_rubber' => $tournament['number_of_rubber'],
                    'sports_category'  =>  Sport::find( $tournament['sports_id'])->sports_category ,
                    'is_knockout' => 0
                );  
 

                $match_schedule_result = MatchSchedule::create($schedule_data);
            }

        $results['success'] = 'Match scheduled successfully.';

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
            $player_a_ids = TeamPlayers::select(DB::raw('GROUP_CONCAT(DISTINCT user_id) AS player_a_ids'))->where('deleted_at',null)->where('team_id', $a_id)->pluck('player_a_ids');
            if(!empty($b_id)) {
                $player_b_ids = TeamPlayers::select(DB::raw('GROUP_CONCAT(DISTINCT user_id) AS player_b_ids'))->where('deleted_at',null)->where('team_id', $b_id)->pluck('player_b_ids');
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


        $sports_category = Sport::find($sports_id)->sports_category;
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
            'number_of_rubber' => $number_of_rubber,
            'sports_category'  => $sports_category

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
            if (isset($match_schedule_id) && $match_schedule_id > 0 && (!empty($b_id) || $sports_category=='athletics')) 
            {
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

                    if($b_id)
                      AllRequests::sendMatchNotifications($tournament_id,$schedule_type,$a_id,$b_id,$match_start_date);

                      if($game_type=='rubber' && (is_numeric($number_of_rubber) && $number_of_rubber>0)){
                            //$this->insertGroupRubber($match_schedule_id);
                      }

                        if( $sports_category=='athletics'){         //if is archery add in the db

                    $match_model = matchSchedule::find($match_schedule_id);
                    $match_model->match_invite_status = 'accepted';
                    $match_model->save();

                        $number_of_players = $request['number_of_players'];
                        $archery_model = new ArcheryController;

                        $players_array='';
                        $players_a_list ='';

                        for($z=1; $z<=$number_of_players; $z++){

                            if($schedule_type=='player')
                                $archery_model->insert_players_in_db($tournament_id,$match_schedule_id,null,$request['player_id_'.$z],User::find($request['player_id_'.$z])->name);
                            else{
                               $archery_model->insert_teams_in_db($tournament_id,$match_schedule_id,$request['player_id_'.$z],Team::find($request['player_id_'.$z])->name);

                                $team_details = team::find($request['player_id_'.$z]);
                                if($team_details){
                                     foreach($team_details->teamplayers as $td){
                                            $players_a_list.=$td->user_id.',';
                                        }
                                }

                            }

                             $players_array.= $request['player_id_'.$z] . ',';
                        }

                    matchSchedule::find($match_schedule_id)->update(['player_or_team_ids'=>$players_array, 'player_a_ids'=>$players_a_list, 'player_b_ids'=>$players_array]);

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

        if(empty($a_id)){
            if(!empty(Request::get('player_id_1'))){
               // $a_id = Request::get('player_id_1');
            }
        }
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
            $player_a_ids = TeamPlayers::select(DB::raw('GROUP_CONCAT(DISTINCT user_id) AS player_a_ids'))->where('deleted_at',null)->where('team_id', $a_id)->pluck('player_a_ids');

            $player_b_ids = TeamPlayers::select(DB::raw('GROUP_CONCAT(DISTINCT user_id) AS player_b_ids'))->where('deleted_at',null)->where('team_id', $b_id)->pluck('player_b_ids');

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


         $sports_category = Sport::find($sports_id)->sports_category;
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
            'number_of_rubber' => $number_of_rubber,
            'sports_category'  => $sports_category
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

                  // If match is archery, save players;


                        if($sports_category=='athletics'){         //if is archery add in the db

                    $match_model = matchSchedule::find($match_schedule_id);
                    $match_model->match_invite_status = 'accepted';
                    $match_model->save();

                        $number_of_players = $request['number_of_players'];
                        $archery_model = new ArcheryController;

                        $players_array='';
                        $players_a_list ='';

                        for($z=1; $z<=$number_of_players; $z++){

                             if($schedule_type=='player')
                                $archery_model->insert_players_in_db($tournament_id,$match_schedule_id,null,$request['player_id_'.$z],User::find($request['player_id_'.$z])->name);

                             else{
                               $archery_model->insert_teams_in_db($tournament_id,$match_schedule_id,$request['player_id_'.$z],Team::find($request['player_id_'.$z])->name);

                                $team_details = team::find($request['player_id_'.$z]);
                                if($team_details){
                                     foreach($team_details->teamplayers as $td){
                                            $players_a_list.=$td->user_id.',';
                                        }
                                }

                            }

                             $players_array.= $request['player_id_'.$z] . ',';
                        }

                    matchSchedule::find($match_schedule_id)->update(['player_or_team_ids'=>$players_array, 'player_a_ids'=>$players_a_list, 'player_b_ids'=>$players_array]);



                  }


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
        $managingteams = Helper::getManagingTeams(Auth::user()->id,$sportId);

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
                $player_a_ids = TeamPlayers::select(DB::raw('GROUP_CONCAT(DISTINCT user_id) AS player_a_ids'))->where('deleted_at',null)->where('team_id', $matchScheduleDetails['winner_id'])->pluck('player_a_ids');
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
