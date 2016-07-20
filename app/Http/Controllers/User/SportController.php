<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Model\Sport;
use App\Model\SportQuestion;
use App\Model\SportQuestionAnswer;
use App\Model\UserStatistic;
use Request;
use Auth;
use Carbon\Carbon;
use Response;
use App\Helpers\Helper;
use App\Helpers\AllRequests;
use DB;
use App\User;

class SportController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //
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
//    public function show($id) {
//
//        $userStatistic = UserStatistic::where('user_id', Auth::user()->id)->first();
//        if (count($userStatistic)) {
//            return redirect(route('sport.edit', [Auth::user()->id]));
////            $followingSportsArray = explode(',', $userStatistic->following_sports);
//        }
//        $sports = Sport::all(['id', 'sports_name']);
//        return view('sportprofile.show', ['sports' => $sports, 'followingSports' => !empty($followingSportsArray) ? $followingSportsArray : []]);
//    }

    public function showSportProfile($userId) {
        $user = User::where('id', $userId)->first();
        $userExists = 'true';
        if(!count($user)) {
            $userExists = 'false';
            return view('sportprofile.show', ['userExists' => $userExists]);
        }
        $userStatistic = UserStatistic::where('user_id', $userId)->first();
        if (count($userStatistic)) {
            if(Auth::user())
                return redirect()->route('editsportprofile', [$userId]);
            else return redirect()->to("/viewpublic/editsportprofile/$userId");
        }
        $sports = Sport::all(['id', 'sports_name']);
        return view('sportprofile.show', ['sports' => $sports,'userId'=>  $userId,'userExists' => $userExists]);
    }

    /**
     * Show the form for editing the sports profile.
     *
     * @param  int  $id
     * @return Response
     */
//    public function edit($id) {
//	Helper::setMenuToSelect(3,2);
//        $userStatistic = UserStatistic::where('user_id', Auth::user()->id)->first();
//        if (count($userStatistic)) {
//            $followingSportsArray = explode(',', trim($userStatistic->following_sports, ','));
//            $userSports = Sport::whereIn('id', $followingSportsArray)->get(['id', 'sports_name']);
//            $sports = Sport::whereNotIn('id', $followingSportsArray)->get(['id', 'sports_name']);
//            return view('sportprofile.edit', ['sports' => $sports, 'userSports' => $userSports, 'followingSports' => !empty($followingSportsArray) ? $followingSportsArray : [],'id'=>  Auth::user()->id]);
//        }
//        $sports = Sport::all(['id', 'sports_name']);
//
//        return view('sportprofile.show', ['sports' => $sports, 'followingTeams' => !empty($followingSportsArray) ? $followingSportsArray : []]);
//    }

    public function editSportProfile($userId) {
        Helper::setMenuToSelect(3,2);
        $self_user_id        = isset(Auth::user()->id)?Auth::user()->id:0;
        $selfProfile         = ($userId != $self_user_id) ? false : true;
        $user = User::where('id', $userId)->first();
        $userExists = 'true';
        if(!count($user)) {
            $userExists = 'false';
            return view('sportprofile.show', ['userExists' => $userExists]);
        }
        //for suggested teams
        $managing_teams = Helper::getManagingTeams($userId,'');
        if(count($managing_teams))
        {
            $managing_teams = $managing_teams->toArray();
        }
        $userStatistic = UserStatistic::where('user_id', $userId)->first();
        if (count($userStatistic)) {
            if(count($userStatistic->following_sports)){
                $followingSportsArray = explode(',', trim($userStatistic->following_sports, ','));
                $userSports = Sport::whereIn('id', $followingSportsArray)->get(['id', 'sports_name']);
                $sports = Sport::whereNotIn('id', $followingSportsArray)->get(['id', 'sports_name']);
                return view('sportprofile.edit', ['sports' => $sports, 'userSports' => $userSports, 'followingSports' => !empty($followingSportsArray) ? $followingSportsArray : [],'userId'=>  $userId,'managing_teams'=>$managing_teams,'userExists' => $userExists,'selfProfile'=>$selfProfile]);
            }else
            {
                $sports = Sport::all(['id', 'sports_name']);
                return view('sportprofile.show', ['sports' => $sports, 'followingTeams' => !empty($followingSportsArray) ? $followingSportsArray : [],'userId'=>  $userId,'managing_teams'=>$managing_teams,'userExists' => $userExists]);
            }
        }
        $sports = Sport::all(['id', 'sports_name']);
        return view('sportprofile.show', ['sports' => $sports, 'followingTeams' => !empty($followingSportsArray) ? $followingSportsArray : [],'userId'=>  $userId,'managing_teams'=>$managing_teams,'userExists' => $userExists]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($userId) {
        $sportsId = Request::get('sports_id');
        $requests = collect(Request::all())->except(['_token', '_method', 'sports_id','chk_available','chk_follow']);
        $this->deleteUserSportAnswers($userId,$sportsId);
        if (count($requests)) {
            foreach ($requests as $key => $request) {
                $requestoptions = explode('_', $request);
                $answerarray[] = [
                    'user_id' => $userId,
                    'sports_questions_id' => $requestoptions[0],
                    'sports_option_id' => $requestoptions[1],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
            SportQuestionAnswer::insert($answerarray);
        }
        return redirect()->route('editsportprofile', [$userId])->with('status', 'Your sports profile updated succesfully');
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
     * Function to get the sports question,options and answers for logged in user for a particular sport
     *
     */
    public function getQuestions() {
        $sportsId = Request::get('sportsid');
        $userId = Request::get('userId');
        $flag = Request::get('flag');
        $viewFlag = Request::get('viewflag');
        if (empty($sportsId) || empty($userId))
            return view('sportprofile.question', ['sportsQuestions' => [], 'exception' => []]);
        $questions = array();
        $sportsPlayerStatistics = array();
        $allowedSports = array();
        $sportDetails = array();
        $sportsCount = 0;
        $loggedInUserId = isset(Auth::user()->id) ? Auth::user()->id:0;
        if ($userId == $loggedInUserId)
            $dispView = 'question';
        else
            $dispView = 'userquestion';
        try {
            if ($flag == 'follow') {
                if ($userId == $loggedInUserId) {
                    $userStatistic = UserStatistic::where('user_id', $userId)->first();
                    if (count($userStatistic)) {
                        if (count($userStatistic->following_sports)) {
                            $sportsCount = count(explode(',',trim($userStatistic->following_sports,',')));
                            if ($sportsCount>7) {
                                return view('sportprofile.'.$dispView, ['sportsCount' => $sportsCount]);
                            }

                            $userStatistic->following_sports = $userStatistic->following_sports . $sportsId . ',';
                        }
                        else {
                            $userStatistic->following_sports = ',' . $sportsId . ',';
                        }

                        if (count($userStatistic->allowed_sports)) {
                            $sportsCount = count(explode(',',trim($userStatistic->allowed_sports,',')));
                            if($sportsCount>7) {
                                return view('sportprofile.'.$dispView, ['sportsCount' => $sportsCount]);
                            }

                            $userStatistic->allowed_sports = $userStatistic->allowed_sports . $sportsId . ',';
                        }
                        else {
                            $userStatistic->allowed_sports = ',' . $sportsId . ',';
                        }

                        if(count($userStatistic->allowed_player_matches)) {
                            $sportsCount = count(explode(',',trim($userStatistic->allowed_player_matches,',')));
                            if($sportsCount>7) {
                                return view('sportprofile.'.$dispView, ['sportsCount' => $sportsCount]);
                            }

                            $userStatistic->allowed_player_matches = $userStatistic->allowed_player_matches . $sportsId . ',';
                        }
                        else {
                            $userStatistic->allowed_player_matches = ',' . $sportsId . ',';
                        }

                        $userStatistic->save();
                    } else {
                        UserStatistic::create(['user_id' => $userId, 'following_sports' => ',' . $sportsId . ',']);
                    }
                }
            }
            $sportDetails = Sport::where('id',$sportsId)->first();
            $sportsQuestions = SportQuestion::with(array('options' => function($query) {
                $query->select('id', 'sports_questions_id', 'options');
            }, 'answers' => function($answerquery) use ($userId) {
                $answerquery->select('id', 'user_id', 'sports_questions_id', 'sports_option_id')->where('user_id', $userId);
            }))->where('sports_id', $sportsId)->get();
            if (count($sportsQuestions)) {
                $questions = $this->buildQuestionAndAnswer($sportsQuestions,$dispView);
            }

            // Sports Statistic related to user.
            $model = config('constants.SPORT.' . $sportsId);
            $appPrefix = 'App\\Model\\';
            $modelName = $appPrefix . $model;
            if (class_exists($modelName)) {
                $stats = $modelName::where('user_id', $userId);
                if($sportsId == config('constants.SPORT_ID.Cricket')) {
                    $stats
                        ->groupBy( 'match_type' )
                        ->select( "*", DB::raw( 'SUM( innings_bat ) innings_bat, SUM(notouts) notouts, SUM(totalruns) totalruns, SUM(totalballs) totalballs, '
                            . 'SUM(fifties) fifties,SUM(hundreds) hundreds,SUM(fours) fours,SUM(sixes) sixes,CAST(AVG(average_bat) AS DECIMAL(10,2)) average_bat,'
                            . 'CAST(AVG(strikerate) AS DECIMAL(10,2)) strikerate, SUM(catches) catches, SUM(stumpouts) stumpouts, SUM(runouts) runouts,'
                            . 'SUM(innings_bowl) innings_bowl, SUM(wickets) wickets, SUM(runs_conceded) runs_conceded, SUM(overs_bowled) overs_bowled, SUM(wides_bowl) wides_bowl, SUM(noballs_bowl) noballs_bowl,'
                            . 'CAST(AVG(average_bowl) AS DECIMAL(10,2)) average_bowl, CAST(AVG(ecomony) AS DECIMAL(10,2)) ecomony' ) );
                }
                $sportsPlayerStatistics= $stats->get();
            }
            $statsview = 'sportprofile.'.preg_replace('/\s+/', '',strtolower(config('constants.SPORT_NAME.'.$sportsId))).'statsview';

            // by default insert user to Interested to join teams? and Interested to play matches?
            //$this->updateUserStatistics();

            //get the allowed sports
            $existingAllowedSportsString = UserStatistic::where('user_id', $userId)->pluck('allowed_sports');
            $existingAllowedSportsString = trim($existingAllowedSportsString,',');
            $existingAllowedSportsArray = explode(',', $existingAllowedSportsString);

            $existingAllowedMatchesString = UserStatistic::where('user_id', $userId)->pluck('allowed_player_matches');
            $existingAllowedMatchesArray = explode(',', trim($existingAllowedMatchesString,','));

            // Match Statistic related to user as a part of team or player
            $searchArray = ['sportsId'=>$sportsId, 'limit'=>config('constants.SCHEDULE_LIMIT'), 'offset'=>0, 'matchStatus'=>'completed', 'userId'=>$userId];
            $matchScheduleData = Helper::buildMyMatchScheduleData($searchArray);
            if (!empty($matchScheduleData)) {
                $matchScheduleData = Helper::buildMyListViewData($matchScheduleData, $userId);
            }


        } catch (\Exception $e) {
            return view('sportprofile.'.$dispView, ['sportsQuestions' => $questions, 'sportsPlayerStatistics' => $sportsPlayerStatistics, 'sportsId' => $sportsId,
                'userId'=>$userId,'existingAllowedSportsArray' => !empty($existingAllowedSportsArray)?$existingAllowedSportsArray:[],'existingAllowedMatchesArray' => !empty($existingAllowedMatchesArray)?$existingAllowedMatchesArray:[],
                'statsview'=>!empty($statsview)?$statsview:'', 'matchScheduleData'=>!empty($matchScheduleData)?$matchScheduleData:[],
                'sportDetails'=>$sportDetails,'sportsCount' => $sportsCount,'exception' => $e->getMessage(),
                'viewFlag'=>$viewFlag, 'flag'=>$flag]);
        }
        return view('sportprofile.'.$dispView, ['sportsQuestions' => $questions, 'sportsPlayerStatistics' => $sportsPlayerStatistics, 'sportsId' => $sportsId,
            'userId'=>$userId,'existingAllowedSportsArray' => $existingAllowedSportsArray,'existingAllowedMatchesArray' => $existingAllowedMatchesArray, 'matchScheduleData'=>$matchScheduleData,
            'statsview'=>$statsview,'sportDetails'=>$sportDetails,'sportsCount' => $sportsCount,
            'exception' => [], 'viewFlag'=>$viewFlag, 'flag'=>$flag]);
    }

    /*
     * Function to build the question and options.
     */

    function buildQuestionAndAnswer($sportsQuestions,$dispView) {
        $questions = $sportsQuestions->toArray();
        foreach ($questions as $key => $question) {
            if (count($question)) {
                foreach ($question['options'] as $qskey => $option) {
                    if (count($question['answers'])) {
                        $checkOptionExist = $this->checkOptionExist($option['id'], $question['answers']);
                        if ($checkOptionExist) {
                            if ($question['sports_element'] == 'radio_button') {
                                $questions[$key]['options'][$qskey]['answer'] = $option['options'];
                            } else {
                                if($dispView == 'question')
                                    $questions[$key]['options'][$qskey]['answer'] = 1;
                                else
                                    $questions[$key]['options'][$qskey]['answer'] = $option['options'];
                            }
                        } else {
                            $questions[$key]['options'][$qskey]['answer'] = null;
                        }
                    } else {
                        $questions[$key]['options'][$qskey]['answer'] = null;
                    }
                }
            }
        }
        return $questions;
    }

    function checkOptionExist($optionId, $answers) {

        foreach ($answers as $answer) {
            if ($answer['sports_option_id'] == $optionId) {
                return true;
            }
        }
        return false;
    }

    //function to update the allowed sports in sports profile edit
    public function updateUserStatistics()
    {
        $request = Request::all();
        $flag = $request['flag'];
        $sportsId = $request['sportId'];
        $userId = $request['userId'];
        $dbFlag = $request['dbFlag'];
        //if flag is available then column name
        // $dbColName = ($dbFlag == 'available')?'allowed_sports':'following_sports';
        if($dbFlag=='available')
            $dbColName = 'allowed_sports';
        else if($dbFlag=='availableteams')
            $dbColName = 'allowed_player_matches';
        else if($dbFlag=='follow')
            $dbColName = 'following_sports';
        //if null update the new value by appending a comma before and after else append the value to existing value with a comma after
        $existingAllowedSportsString = UserStatistic::where('user_id', $userId)->pluck($dbColName);
        $existingAllowedSportsArray = array();
        $updatedAllowedSportsString = null;
        if($flag ==  'true')//for adding
        {
            if(!empty($existingAllowedSportsString))
            {
                if(!$this->isExist($existingAllowedSportsString,$sportsId))
                {
                    $updatedAllowedSportsString = trim($existingAllowedSportsString).$sportsId.',';
                }
            }
            else
            {
                $updatedAllowedSportsString = ','.$sportsId.',';
            }
        }
        else //for delete
        {
            $updatedAllowedSportsString = $this->removeFromArray($existingAllowedSportsString,$sportsId);
        }
        $result = array();
        //update
        if(UserStatistic::where('user_id', $userId)->update([$dbColName=>$updatedAllowedSportsString,'updated_at'=>Carbon::now()]))
        {
            //if it is unfollow then delete the sport question's answers
            if($flag == 'false' && $dbFlag=='follow')
            {
                $this->deleteUserSportAnswers($userId,$sportsId);
                $updatedAllowedSportsString = $this->removeFromArray($existingAllowedSportsString,$sportsId);
                UserStatistic::where('user_id', $userId)->update(['allowed_sports'=>$updatedAllowedSportsString,
                    'allowed_player_matches'=>$updatedAllowedSportsString,
                    'updated_at'=>Carbon::now()]);
            }
            echo trans('message.sports.updateuserstatssuccess');
        }
        else
        {
            echo trans('message.sports.updateuserstatsfail');
        }
    }
    public function isExist($existing_ids_str,$new_id)
    {
        $existing_ids_str = trim($existing_ids_str,',');
        $existing_ids = explode(',',$existing_ids_str);
        if(in_array($new_id,$existing_ids))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //function to remove an element from an array
    public function removeFromArray($existing_ids_str, $new_id)
    {
        $existing_ids_str = trim($existing_ids_str, ',');
        $existing_ids     = explode(',', $existing_ids_str);
        $key              = array_search($new_id, $existing_ids);
        unset($existing_ids[$key]);
        $final_str        = null;
        $existing_ids     = array_unique(array_filter($existing_ids));
        if (count($existing_ids))
        {
            $final_str = implode(',', $existing_ids);
        }
        return $final_str;
    }

    //function to delete the quenstion's answers

    function deleteUserSportAnswers($userId,$sportsId)
    {
        $sportsQuestions = SportQuestion::where('sports_id',$sportsId)->get(['id']);
        $sportsQuestionsArray = [];
        if(count($sportsQuestions))
        {
            $sportsQuestionsArray = array_flatten($sportsQuestions->toArray());
        }
        SportQuestionAnswer::where('user_id', $userId)->whereIn('sports_questions_id', $sportsQuestionsArray)->delete();
    }
    //function to show the requests
    public function getplayerrequests($id)
    {
        $limit = config('constants.LIMIT');
        $offset = 0;
        $type_ids = config('constants.REQUEST_TYPE.TEAM_TO_PLAYER').','.config('constants.REQUEST_TYPE.PLAYER_TO_TEAM').','.config('constants.REQUEST_TYPE.PLAYER_TO_TOURNAMENT').','.config('constants.REQUEST_TYPE.PLAYER_TO_PLAYER');
        //pass type flag to get the request
        $result = AllRequests::getrequests($id,$type_ids,$limit,$offset);
        $result_count = AllRequests::getrequestscount($id,$type_ids);
        //get sent request
        $sent = AllRequests::getsentrequests($id,$type_ids,$limit,$offset);
        $sent_count = AllRequests::getsentrequestscount($id,$type_ids);

        return view('sportprofile.playerrequests',['result'=>$result,'sent'=>$sent,'received_count'=>$result_count,'sent_count'=>$sent_count,'limit'=>$limit,'offset1'=>$limit+$offset,'offset2'=>$limit+$offset,'userId'=>isset(Auth::user()->id)?Auth::user()->id:0]);
    }
    //function to show the requests
    public function getviewmoreplayerrequests()
    {
        $id = !empty(Request::get('id'))?Request::get('id'):0;
        $flag = !empty(Request::get('flag'))?Request::get('flag'):'';
        $limit = !empty(Request::get('limit'))?Request::get('limit'):0;
        $offset1 = !empty(Request::get('offset1'))?Request::get('offset1'):0;
        $offset2 = !empty(Request::get('offset2'))?Request::get('offset2'):0;
        $type_ids = config('constants.REQUEST_TYPE.TEAM_TO_PLAYER').','.config('constants.REQUEST_TYPE.PLAYER_TO_TEAM').','.config('constants.REQUEST_TYPE.PLAYER_TO_TOURNAMENT').','.config('constants.REQUEST_TYPE.PLAYER_TO_PLAYER');
        //pass type flag to get the request
        if($flag == 'Received')
        {
            $result = AllRequests::getrequests($id,$type_ids,$limit,$offset1);
            $offset1 = $offset1+$limit;
        }
        else
        {
            //get sent request
            $result = AllRequests::getsentrequests($id,$type_ids,$limit,$offset2);
            $offset2 = $offset2+$limit;
        }
        return view('common.viewmorerequests',['sent'=>$result,'limit'=>$limit,'offset1'=>$offset1,'offset2'=>$offset2,'flag'=>$flag]);
    }
    //function to get the sports
    public function getsports()
    {
        $sports = Sport::where('is_schedule_available',1)->where('is_scorecard_available',1)->get(array('id','sports_name','sports_type'));
        return Response::json(['sports'=>$sports]);
    }

    public function removeFollowedSport($sportId)
    {
        $userId           = isset(Auth::user()->id)?Auth::user()->id:0;
        $userSports       = [];
        $updated          = [];
        $result['userId'] = $userId;

        $existing = UserStatistic::where('user_id', $userId)->get(['following_sports',
            'allowed_player_matches', 'allowed_sports']);
        $existing = $existing->toarray();

        $updated['following_sports']       = $this->removeFromArray($existing[0]['following_sports'], $sportId);
        $updated['allowed_player_matches'] = $this->removeFromArray($existing[0]['allowed_player_matches'], $sportId);
        $updated['allowed_sports']         = $this->removeFromArray($existing[0]['allowed_sports'], $sportId);

        if (UserStatistic::where('user_id', $userId)->update(
            ['allowed_sports'         => $updated['allowed_sports']
                , 'allowed_player_matches' => $updated['allowed_player_matches']
                , 'following_sports'       => $updated['following_sports']
                , 'updated_at'             => Carbon::now()]))
        {
            if (!empty($updated['following_sports']))
            {
                $followingSportsArray = explode(',', trim($updated['following_sports'], ','));
                $userSports           = Sport::whereIn('id', $followingSportsArray)->get(['id','sports_name']);
                if (count($userSports))
                {
                    $userSports = $userSports->toArray();
                }
            }
            $result['result']     = 'success';
            $result['userSports'] = $userSports;
            return Response::json($result);
        }
        else
        {
            $result['result'] = 'fail';
            return Response::json($result);
        }
    }

}