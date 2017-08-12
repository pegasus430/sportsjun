<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\User;
use App\Model\MarketPlace;
use App\Model\Sport;
use App\Model\MatchSchedule;
use App\Model\Team;
use Response;
use View;
use PDO;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Model\MarketPlaceCategories;
use App\Model\MarketPlaceLogs;
use DB;
use Auth;
use Input;
use Validator;
use Session;
use Redirect;
use App\Model\State;
use App\Model\City;
use App\Model\Country;
use App\Model\Photo;

class SearchController extends Controller
{

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
                /* Helper::setMenuToSelect(9,1);
                  return view('search.list'); */
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
                //
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
                //
        }

        /**
         * Display the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
                //
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id)
        {
                //
        }

        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, $id)
        {
                //
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function destroy($id)
        {
                //
        }

        public function searchResultsByType(Request $request)
        {
                //dd($request->all());
                //Dynamic Search based on search parameter
                $modelType  = $request['id'];
                $offset     = $request['page'];
                $search     = $request['search'];
                $model      = ucfirst($modelType);
                $appPrefix  = 'App\\Model\\';
                if ($modelType == 'user')
                        $appPrefix  = 'App\\';
                if ($modelType == 'facility')
                        $model      = 'Facilityprofile';
                if ($modelType == 'tournament')
                        $model      = 'Tournaments';
                $modelName  = $appPrefix . $model;
                //echo $modelName;
                $userResult = '';
                if (class_exists($modelName))
                {
                        //$userResult = $modelName::limit(5)->search($search)->get();
                        $userResult = $modelName::limit(5)->search($search)->get();
                }
                //End Search		
                //dd($userResult);
                //return view('search/result');
                return view('search.' . $modelType)->with('userResult', $userResult);
        }

        public function searchResultsByTypeAutoComplete(Request $request)
        {
                //dd($request->all());    
                $limit  = config('constants.AUTOCOMPLETE_LIMIT');
                //$models = array('App\\User','App\\Model\\Facilityprofile','App\\Model\\Organization','App\\Model\\Tournaments','App\\Model\\Team');
                $models = array('App\\User', 'App\\Model\\Facilityprofile', 'App\\Model\\Tournaments',
                        'App\\Model\\Team');
                $result = array();
                foreach ($models as $key => $value)
                {
                        $modelResult  = $value::limit($limit)->with('photos')->search($request['term'])->get()->toArray();
                        $tmp          = explode('\\', $value);
                        $categoryName = end($tmp);
                        $imageURL     = '';
                        //$categoryName = '';
                        if ($categoryName == 'User')
                        {
                                $category = 'Users';
                                $imageURL = '';
                        }
                        elseif ($categoryName == 'Organization')
                        {
                                $category = 'Organizations';
                        }
                        elseif ($categoryName == 'Facilityprofile')
                        {
                                $category = 'Facilities';
                        }
                        elseif ($categoryName == 'Team')
                        {
                                $category = 'Teams';
                        }
                        elseif ($categoryName == 'Tournaments')
                        {
                                $category = 'Tournaments';
                        }
                        else
                        {
                                $category = '';
                        }
                        foreach ($modelResult as $key => $value)
                        {
                                $result[$key . '_' . $category]['label']    = $value['name'];
                                $result[$key . '_' . $category]['category'] = $category;
                                $result[$key . '_' . $category]['url']      = 'http://sj.com/' . $value['id'];
                                $result[$key . '_' . $category]['image']    = isset($value['photos'][0]) ? $value['photos'][0]['url'] : '';
                                $result[$key . '_' . $category]['location'] = $value['location'];
                        }
                }
                //$userResult = \App\User::limit($limit)->with('photos')->search($request['term'])->get()->toArray();
                //retrun Response::json($user);
                return Response::json(!empty($result) ? $result : []);
                //dd($userResult);
        }

        public function search(Request $request)
        {
                $search = $request['txtGlobalSearch'];
                View::share('search', $search);
                Helper::setMenuToSelect(9, 1);
                return view('search.list');
        }

        public function unique(&$a, $b)
        {
                return $a ? array_merge(array_diff($a, $b), array_diff($b, $a)) : $b;
        }

        //Start Global search
        public function searchresults(Request $request)
        {


                $modelType      = $request['service'];
                $model          = ucfirst($modelType);
                $limit          = config('constants.LIMIT');
                $offset         = 0;
                $sport_by       = $request['sport'];
                $city_by        = $request['search_city_id'];
                $category_by    = $request['category'];
                $search_by_name = trim($request['search_by']);
                $search_city    = $request['search_city'];
                $view_more_flag = 0;

                $appPrefix = 'App\\Model\\';
                if ($modelType == 'user')
                        $appPrefix = 'App\\';
                if ($modelType == 'facility')
                        $model     = 'Facilityprofile';
                if ($modelType == 'tournament')
                        $model     = 'Tournaments';
                if ($modelType == 'team')
                        $model     = 'Team';
                if ($modelType == 'marketplace')
                        $model     = 'MarketPlace';

                $modelName = $appPrefix . $model;
                $result    = array();
                $max       = '';
                $min       = '';
                $name      = '';
                $city      = "";
                $category  = "";
                $states    = array();
                $cities    = array();
                if (class_exists($modelName))
                {
                        $modelObj   = new $modelName();
                        $response   = $modelObj->searchResults($request);
                        $totalcount = $response['total'];
                        $result     = $response['result'];
                        //if(count($result)>0){
                        if ($model == 'Tournaments')
                        {
                                $max = $modelObj->select()->max('enrollment_fee');
                                $min = $modelObj->select()->min('enrollment_fee');
                        }
                        if ($model == 'MarketPlace')
                        {
                                $max                   = MarketPlace::select()->max('base_price');
                                $min                   = 0;
                                $states                = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();
                                $cities                = [];
                                $modelType             = $request['service'];
                                $name                  = $request['search_by'];
                                $city                  = $request['search_city_id'];
                                $category              = $request['category'];
                                $marketPlaceCategories = MarketPlaceCategories::where('isactive', '=', 1)->get();
                                Helper::setMenuToSelect(7, 0, $marketPlaceCategories);
                        }

                        /* Start - By search results to display join button */
                        $checkArray         = '';
                        $exist_array        = array();
                        $exist_items_array  = array();
                        $follow_array       = array();
                        $follow_items_array = array();

                        if ($result != '')
                        {
                                foreach ($result as $teamdet)
                                {
                                        if ($model == 'User')
                                        {
                                                $checkArray.= $teamdet->user_id . ",";
                                        }
                                        elseif ($model == 'Tournaments') {
                                            $currentTimestamp   = time();
                                            $startDateTimestamp = strtotime($teamdet->start_date);
                                            $endDateTimestamp   = strtotime($teamdet->end_date);
                                            if ($endDateTimestamp <= $currentTimestamp)
                                            {
                                                $teamdet->status = "Completed";
                                                $teamdet->statusColor = "black";
                                                $tournament_winner_details = self::getTournamentWinner($teamdet, ["name"]);
                                                if (!empty($tournament_winner_details))
                                                {
                                                    $teamdet->winnerName = $tournament_winner_details["name"];
                                                }
                                            }
                                            else if ($startDateTimestamp > $currentTimestamp){
                                                $teamdet->status = "Not started";
                                                $teamdet->statusColor = "green";
                                            }
                                            else if ($currentTimestamp >= $startDateTimestamp)
                                            {
                                                $teamdet->status = "In progress";
                                                $teamdet->statusColor = "black";
                                            }
                                        }
                                        else
                                        {
                                                $checkArray.= $teamdet->id . ",";
                                        }
                                }
                        }
                        $checkArray  = trim($checkArray, ",");
                        $loginUserId = Auth::user()->id;
                        if ($model == 'Team')
                        {
                                if (!empty($checkArray))
                                {
                                        DB::setFetchMode(PDO::FETCH_ASSOC);
                                        $exist_array = DB::select("SELECT DISTINCT tp.team_id as item
												FROM `team_players` tp  
												WHERE tp.user_id = $loginUserId AND tp.team_id IN ($checkArray) AND `status` != 'rejected' AND tp.deleted_at IS NULL ");

                                        $follow_array = DB::select("SELECT DISTINCT tp.type_id as item
												FROM `followers` tp  
												WHERE tp.user_id = $loginUserId AND tp.type_id IN ($checkArray) AND `type` = 'team' AND tp.deleted_at IS NULL ");
                                        DB::setFetchMode(PDO::FETCH_CLASS);
                                }
                        }
                        if ($model == 'Organization')
                        {
                                if (!empty($checkArray))
                                {
                                         DB::setFetchMode(PDO::FETCH_ASSOC);
                                        $follow_array = DB::select("SELECT DISTINCT tp.type_id as item
                                                FROM `followers` tp  
                                                WHERE tp.user_id = $loginUserId AND tp.type_id IN ($checkArray) AND `type` = 'organization' AND tp.deleted_at IS NULL ");
                                        DB::setFetchMode(PDO::FETCH_CLASS);
                                }
                        }
                        //echo "<pre>";print_r($exist_items_array);exit;
                        //dd($exist_array);
                        if ($model == 'Tournaments')
                        {
                                if (!empty($checkArray))
                                {
                                        DB::setFetchMode(PDO::FETCH_ASSOC);
                                        $exist_array  = DB::select("SELECT DISTINCT t.id as item
                                                FROM `tournaments` t
                                                INNER JOIN `tournament_final_teams` f ON f.tournament_id = t.id AND f.team_id = $loginUserId
                                                WHERE t.schedule_type = 'individual' AND t.id IN ($checkArray) AND t.type != 'league'  
                                                UNION ALL
                                                SELECT DISTINCT t.id 
                                                FROM `tournaments` t
                                                INNER JOIN `tournament_group_teams` g ON g.tournament_id = t.id AND g.team_id = $loginUserId
                                                WHERE t.schedule_type = 'individual' AND t.id IN ($checkArray) AND t.type != 'knockout' AND t.type != 'doubleknockout' ");
                                        $follow_array = DB::select("SELECT DISTINCT tp.type_id as item
												FROM `followers` tp  
												WHERE tp.user_id = $loginUserId AND tp.type_id IN ($checkArray) AND `type` = 'tournament' AND tp.deleted_at IS NULL ");
                                        DB::setFetchMode(PDO::FETCH_CLASS);
                                }
                        }
                        if ($model == 'User')
                        {
                                if (!empty($checkArray))
                                {
                                        DB::setFetchMode(PDO::FETCH_ASSOC);

                                        $follow_array = DB::select("SELECT DISTINCT tp.type_id as item
												FROM `followers` tp  
												WHERE tp.user_id = $loginUserId AND tp.type_id IN ($checkArray) AND `type` = 'player' AND tp.deleted_at IS NULL ");
                                        DB::setFetchMode(PDO::FETCH_CLASS);
                                }
                        }
                        DB::setFetchMode(PDO::FETCH_CLASS);
                        /* End */
                        if (count($exist_array) > 0)
                        {
                                foreach ($exist_array as $item)
                                {
                                        array_push($exist_items_array, $item['item']);
                                }
                        }
                        if (count($follow_array) > 0)
                        {
                                foreach ($follow_array as $item)
                                {
                                        array_push($follow_items_array, $item['item']);
                                }
                        }
                }
                $sports       = Sport::get();
                $sports_array = array();
                foreach ($sports as $sport)
                {
                        $sports_array[$sport->id] = $sport->sports_name;
                }
                $categories_array      = Helper::getAllMarketPlaceCategories();
                $marketPlaceCategories = $categories_array;
                //dd($sports_array);
                if ($request->ajax())
                {
                    
                        

                        return view('search.' . $modelType . 'ajax')->with(array(
                                        'result' => $result, 'exist_array' => $exist_items_array,
                                        'follow_array' => $follow_items_array, 'sports_array' => $sports_array,
                                        'request_params' => $request, 'max' => $max,
                                        'min' => $min, 'states' => ['' => 'Select State'] + $states,
                                        'cities' => ['' => 'Select City'] + $cities,
                                        'page' => 'marketplace', 'categories_array' => $categories_array,
                                        'marketPlaceCategories' => $marketPlaceCategories,
                                        'offset' => $offset + $limit, 'limit' => $limit,
                                        'totalcount' => $totalcount, 'name' => $name,
                                        'city' => $city, 'category' => $category,
                                        'modeltype' => $modelType, 'sport_by' => $sport_by,
                                        'search_city' => $search_city, 'city_by' => $city_by,
                                        'category_by' => $category_by, 'search_by_name' => $search_by_name,
                                        "view_more_flag" => $view_more_flag));
                }
                else
                {
                    
                      //echo "<pre>"; print_r($result); echo "</pre>"; exit;
                         

                        return view('search.' . $modelType)->with(array('result' => $result,
                                        'exist_array' => $exist_items_array, 'follow_array' => $follow_items_array,
                                        'sports_array' => $sports_array, 'request_params' => $request,
                                        'max' => $max, 'min' => $min, 'states' => ['' => 'Select State'] + $states,
                                        'cities' => ['' => 'Select City'] + $cities,
                                        'page' => 'marketplace', 'categories_array' => $categories_array,
                                        'marketPlaceCategories' => $marketPlaceCategories,
                                        'offset' => $offset + $limit, 'limit' => $limit,
                                        'totalcount' => $totalcount, 'name' => $name,
                                        'city' => $city, 'category' => $category,
                                        'modeltype' => $modelType, 'sport_by' => $sport_by,
                                        'search_city' => $search_city, 'city_by' => $city_by,
                                        'category_by' => $category_by, 'search_by_name' => $search_by_name,
                                        "view_more_flag" => $view_more_flag));
                }
        }

        public function viewmore(Request $request)
        {
                // echo "here";exit;
                // dd($request);			
                $modelType = $request['page'];
                $request['sport_by'];
                $model     = ucfirst($modelType);
                $limit     = config('constants.LIMIT');
                $offset    = $request['offset'];

                $sport_by       = $request['sport'];
                $city_by        = $request['search_city_id'];
                $category_by    = $request['category'];
                $search_by_name = trim($request['search_by']);
                $search_city    = $request['search_city'];
                $view_more_flag = 1;


                $appPrefix = 'App\\Model\\';
                if ($modelType == 'user')
                        $appPrefix = 'App\\';
                if ($modelType == 'facility')
                        $model     = 'Facilityprofile';
                if ($modelType == 'tournament')
                        $model     = 'Tournaments';
                if ($modelType == 'team')
                        $model     = 'Team';
                if ($modelType == 'marketplace')
                        $model     = 'MarketPlace';

                $modelName = $appPrefix . $model;
                $result    = array();
                $max       = '';
                $min       = '';
                $states    = array();
                $cities    = array();
                if (class_exists($modelName))
                {
                        $modelObj   = new $modelName();
                        $response   = $modelObj->searchResults($request);
                        $totalcount = $response['total'];
                        $result     = $response['result'];

                        //if(count($result)>0){
                        if ($model == 'Tournaments')
                        {
                                $max = $modelObj->select()->max('enrollment_fee');
                                $min = $modelObj->select()->min('enrollment_fee');
                        }
                        if ($model == 'MarketPlace')
                        {
                                $max                   = MarketPlace::select()->max('base_price');
                                $min                   = 0;
                                $states                = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();
                                $cities                = [];
                                $marketPlaceCategories = MarketPlaceCategories::where('isactive', '=', 1)->get();
                                Helper::setMenuToSelect(7, 0, $marketPlaceCategories);
                        }
                        //}
                }
                $sports       = Sport::get();
                $sports_array = array();
                foreach ($sports as $sport)
                {
                        $sports_array[$sport->id] = $sport->sports_name;
                }
                $categories_array      = Helper::getAllMarketPlaceCategories();
                $marketPlaceCategories = $categories_array;
                //dd($sports_array);

                /* Start - By search results to display join button */
                $checkArray         = '';
                $exist_array        = array();
                $exist_items_array  = array();
                $follow_array       = array();
                $follow_items_array = array();

                if ($result != '')
                {
                        foreach ($result as $teamdet)
                        {
                                  if ($model == 'User')
                                        {
                                                $checkArray.= $teamdet->user_id . ",";
                                        }
                                        elseif ($model == 'Tournaments') {
                                            $currentTimestamp   = time();
                                            $startDateTimestamp = strtotime($teamdet->start_date);
                                            $endDateTimestamp   = strtotime($teamdet->end_date);
                                            if ($endDateTimestamp <= $currentTimestamp)
                                            {
                                                $teamdet->status = "Completed";
                                                $teamdet->statusColor = "black";
                                                $tournament_winner_details = self::getTournamentWinner($teamdet, ["name"]);
                                                if (!empty($tournament_winner_details))
                                                {
                                                    $teamdet->winnerName = $tournament_winner_details["name"];
                                                }
                                            }
                                            else if ($startDateTimestamp > $currentTimestamp){
                                                $teamdet->status = "Not started";
                                                $teamdet->statusColor = "green";
                                            }
                                            else if ($currentTimestamp >= $startDateTimestamp)
                                            {
                                                $teamdet->status = "In progress";
                                                $teamdet->statusColor = "black";
                                            }
                                        }
                                        else
                                        {
                                                $checkArray.= $teamdet->id . ",";
                                        }
                        }
                }
                $checkArray  = trim($checkArray, ",");
                $loginUserId = Auth::user()->id;
                if ($model == 'Team')
                {
                        if (!empty($checkArray))
                        {
                                DB::setFetchMode(PDO::FETCH_ASSOC);
                                $exist_array  = DB::select("SELECT DISTINCT tp.team_id as item
										FROM `team_players` tp  
										WHERE tp.user_id = $loginUserId AND tp.team_id IN ($checkArray) AND `status` != 'rejected' AND tp.deleted_at IS NULL ");
                                $follow_array = DB::select("SELECT DISTINCT tp.type_id as item
												FROM `followers` tp  
												WHERE tp.user_id = $loginUserId AND tp.type_id IN ($checkArray) AND `type` = 'team' AND tp.deleted_at IS NULL ");
                        }
                }

                //echo "<pre>";print_r($exist_items_array);exit;
                //dd($exist_array);
                if ($model == 'Tournaments')
                {
                        if (!empty($checkArray))
                        {

                                DB::setFetchMode(PDO::FETCH_ASSOC);
                                $exist_array  = DB::select("SELECT DISTINCT t.id as item
    										FROM `tournaments` t
    										INNER JOIN `tournament_final_teams` f ON f.tournament_id = t.id AND f.team_id = $loginUserId
    										WHERE t.schedule_type = 'individual' AND t.id IN ($checkArray) AND t.type != 'league'  
    										UNION ALL
    										SELECT DISTINCT t.id 
    										FROM `tournaments` t
    										INNER JOIN `tournament_group_teams` g ON g.tournament_id = t.id AND g.team_id = $loginUserId
    										WHERE t.schedule_type = 'individual' AND t.id IN ($checkArray) AND t.type != 'knockout' AND t.type != 'doubleknockout' ");
                                $follow_array = DB::select("SELECT DISTINCT tp.type_id as item
												FROM `followers` tp  
												WHERE tp.user_id = $loginUserId AND tp.type_id IN ($checkArray) AND `type` = 'tournament' AND tp.deleted_at IS NULL ");
                        }
                }

                if ($model == 'User')
                {
                        if (!empty($checkArray))
                        {
                                DB::setFetchMode(PDO::FETCH_ASSOC);

                                $follow_array = DB::select("SELECT DISTINCT tp.type_id as item
										FROM `followers` tp  
										WHERE tp.user_id = $loginUserId AND tp.type_id IN ($checkArray) AND `type` = 'player' AND tp.deleted_at IS NULL ");
                                DB::setFetchMode(PDO::FETCH_CLASS);
                        }
                }
                DB::setFetchMode(PDO::FETCH_CLASS);
                /* End */
                if (count($exist_array) > 0)
                {
                        foreach ($exist_array as $item)
                        {
                                array_push($exist_items_array, $item['item']);
                        }
                }
                if (count($follow_array) > 0)
                {
                        foreach ($follow_array as $item)
                        {
                                array_push($follow_items_array, $item['item']);
                        }
                }

                if ($request->ajax())
                {
                        return view('search.' . $modelType . 'ajax')->with(array(
                                        'result' => $result, 'exist_array' => $exist_items_array,
                                        'follow_array' => $follow_items_array, 'sports_array' => $sports_array,
                                        'request_params' => $request, 'max' => $max,
                                        'min' => $min, 'states' => ['' => 'Select State'] + $states,
                                        'cities' => ['' => 'Select City'] + $cities,
                                        'page' => 'marketplace', 'categories_array' => $categories_array,
                                        'marketPlaceCategories' => $marketPlaceCategories,
                                        'offset' => $offset + $limit, 'limit' => $limit,
                                        'totalcount' => $totalcount, 'sport_by' => $sport_by,
                                        'search_city' => $search_city, 'city_by' => $city_by,
                                        'category_by' => $category_by, 'search_by_name' => $search_by_name,
                                        "view_more_flag" => $view_more_flag));
                }
                else
                {
                        return view('search.' . $modelType)->with(array('result' => $result,
                                        'exist_array' => $exist_items_array, 'follow_array' => $follow_items_array,
                                        'sports_array' => $sports_array, 'request_params' => $request,
                                        'max' => $max, 'min' => $min, 'states' => ['' => 'Select State'] + $states,
                                        'cities' => ['' => 'Select City'] + $cities,
                                        'page' => 'marketplace', 'categories_array' => $categories_array,
                                        'marketPlaceCategories' => $marketPlaceCategories,
                                        'offset' => $offset + $limit, 'limit' => $limit,
                                        'totalcount' => $totalcount, 'sport_by' => $sport_by,
                                        'search_city' => $search_city, 'city_by' => $city_by,
                                        'category_by' => $category_by, 'search_by_name' => $search_by_name,
                                        "view_more_flag" => $view_more_flag));
                }
        }

        public function suggestedWidget(Request $request)
        {
                $modelType      = $request['type'];
                $type_id        = !empty($request['type_id']) ? $request['type_id'] : 0;
                $sport_id       = !empty($request['sport_id']) ? $request['sport_id'] : 0;
                $flag_type      = !empty($request['flag_type']) ? $request['flag_type'] : null;
                $search_city_id = !empty($request['city']) ? $request['city'] : 0;
                if (!empty($search_city_id))
                {
                        $city_id = $search_city_id;
                }
                else
                {
                        $city_id = User::where('id', Auth::user()->id)->pluck('city_id');
                }
                $city      = City::where('id', $city_id)->pluck('city_name');
                $appPrefix = 'App\\Model\\';
                if ($modelType == 'players')
                        $appPrefix = 'App\\';$model     = 'User';
                if ($modelType == 'facilities')
                        $model     = 'Facilityprofile';
                if ($modelType == 'tournaments')
                        $model     = 'Tournaments';
                if ($modelType == 'teams')
                        $model     = 'Team';
                $modelName = $appPrefix . $model;
                $result    = '';
                if (class_exists($modelName))
                {
                        if ($modelType == 'players')
                        {
                                $result = $this->getSuggestedPlayers($type_id, $sport_id, $city_id);
                        }
                        else if ($modelType == 'teams' && ($flag_type == 'team_to_tournament' || $flag_type == 'player_to_team'))
                        {
                                //type_id is player_id here
                                $result = $this->getSuggestedTeams($type_id, $sport_id, $flag_type, $city_id);
                        }
                        else if ($modelType == 'tournaments' && ($flag_type == 'team_to_tournament' || $flag_type == 'player_to_tournament'))
                        {
                                //type_id is player_id here
                                $result              = $this->getSuggestedTournaments($type_id, $sport_id, $flag_type, $city_id);
                                $result['flag_type'] = strtoupper($flag_type);
                        }
                        else
                        {
                                $result = $modelName::limit(5)->get();
                        }
                        //$result = $modelName::where('column', 'LIKE', '%value%')->limit(5)->get();
                        $sports       = Sport::get();
                        $sports_array = array();
                        foreach ($sports as $sport)
                        {
                                $sports_array[$sport->id] = $sport->sports_name;
                        }
                }
                $controller_arr  = Helper::getcurrentroute();
                $controller_name = $controller_arr['controller_name'];
                //echo "<pre>";print_r($result);exit;
                return view('widgets/suggested' . $modelType)->with(array('result' => $result,
                                'sports_array' => $sports_array, 'request_params' => $request,
                                'controller_name' => $controller_name, 'sport_id' => $sport_id,
                                'city' => $city, 'city_id' => $city_id));
        }

        //fucntion to get players for widget
        public function getSuggestedPlayers($team_id, $sport_id, $city_id)
        {
                $results = array();
                DB::setFetchMode(PDO::FETCH_ASSOC);

                if (!empty($team_id) && !empty($sport_id))
                {
                        $team_player_qry = DB::table('team_players')
                                ->select('team_players.user_id')
                                ->where('team_id', '=', $team_id)
                                ->whereNotIn('status', ['rejected'])
                                ->whereNull('deleted_at')
                                ->get();
                        $team_user_id    = array();
                        foreach ($team_player_qry as $team)
                        {
                                $team_user_id[] = $team['user_id'];
                        }
                        $results = DB::table('users')
                                ->join('user_statistics', 'users.id', '=', 'user_statistics.user_id')
                                ->select('users.*', 'user_statistics.*')
                                ->where('user_statistics.allowed_sports', 'LIKE', '%,' . $sport_id . ',%')
                                ->whereNotIn('users.id', $team_user_id)
                                ->where('users.city_id', $city_id)
                                ->whereNull('users.deleted_at')
                                ->limit(5)->offset(0)
                                ->get();
                }
                elseif (!empty($sport_id))
                {
                        $results = DB::table('users')
                                ->select('users.*', 'user_statistics.*')
                                ->join('user_statistics', 'users.id', '=', 'user_statistics.user_id')
                                ->where('user_statistics.allowed_sports', 'LIKE', '%,' . $sport_id . ',%')
                                ->where('users.city_id', $city_id)
                                ->whereNull('users.deleted_at')
                                ->limit(5)->offset(0)
                                ->get();
                }
                else
                {
                        $results = DB::table('users')
                                ->select('users.*', 'user_statistics.*')
                                ->join('user_statistics', 'users.id', '=', 'user_statistics.user_id')
                                ->where('users.city_id', $city_id)
                                ->whereNull('users.deleted_at')
                                ->limit(5)->offset(0)
                                ->get();
                }
                DB::setFetchMode(PDO::FETCH_CLASS);
                return $results;
        }

        //function to get the suggested teams
        public function getSuggestedTeams($player_id, $sport_id, $flag_type, $city_id)
        {
                //echo $player_id;exit;
                $results         = array();
                DB::setFetchMode(PDO::FETCH_ASSOC);
                $team_player_qry = DB::table('team_players')
                        ->select('team_players.team_id')
                        ->where('user_id', '=', $player_id)
                        //->whereNotIn('user_id', [$player_id])
                        ->whereNotIn('status', ['rejected'])
                        ->whereNull('deleted_at')
                        ->get();
                //echo "<pre>";print_r($team_player_qry);exit;
                //Helper::printQueries();
                $team_id         = array();
                foreach ($team_player_qry as $team)
                {
                        $team_id[] = $team['team_id'];
                }
                if (!empty($player_id) && !empty($sport_id))
                {
                        $results = DB::table('teams')
                                ->whereNotIn('teams.id', $team_id)
                                ->where('teams.sports_id', $sport_id)
                                ->where('teams.player_available', 1)
                                ->where('teams.city_id', $city_id)
                                ->whereNull('teams.deleted_at')
                                ->where('teams.isactive', '=', 1)
                                ->limit(5)->offset(0)
                                ->get();
                }
                elseif (!empty($sport_id))
                {
                        $results = DB::table('teams')
                                ->where('teams.city_id', $city_id)
                                ->whereNotIn('teams.id', $team_id)
                                ->where('teams.sports_id', '=', $sport_id)
                                ->where('teams.player_available', 1)
                                ->whereNull('teams.deleted_at')
                                ->where('teams.isactive', '=', 1)
                                ->limit(5)->offset(0)
                                ->get();
                }
                else
                {
                        // $results = DB::table('teams')
                        //             ->whereNotIn('teams.id',$team_id)
                        //            ->where('teams.city_id',$city_id)
                        //            ->where('teams.player_available',1)
                        //            ->whereNull('teams.deleted_at')
                        //            ->where('teams.isactive', '=', 1)
                        //            ->limit(5)->offset(0)
                        //            ->get();
                }
                // Helper::printQueries();
                DB::setFetchMode(PDO::FETCH_CLASS);
                return $results;
        }

        //function to get the suggested tournaments
        public function getSuggestedTournaments($team_id, $sport_id, $flag_type, $city_id)
        {
                $results = array();
                DB::setFetchMode(PDO::FETCH_ASSOC);
                $user_id = Auth::user()->id;
                if (!empty($team_id) && !empty($sport_id) && $flag_type == 'team_to_tournament')
                {
                        $tournament_qry = DB::select("select `tournaments`.`id` 
                                from `tournaments` 
                                inner join `tournament_groups` on `tournaments`.`id` = `tournament_groups`.`tournament_id`
                                inner join `tournament_group_teams` on `tournament_group_teams`.`tournament_group_id` = `tournament_groups`.`id`  
                                and `tournament_group_teams`.`team_id` = $team_id 
                                where `tournaments`.`sports_id` = $sport_id and `tournaments`.`schedule_type` = 'team' 
                                and `tournament_group_teams`.`deleted_at` is null 
                                and `tournaments`.`deleted_at` is null
                                union
                                select `tournaments`.`id` 
                                from `tournaments` 
                                inner join `tournament_final_teams` on `tournaments`.`id` = `tournament_final_teams`.`tournament_id`
                                and `tournament_final_teams`.`team_id` = $team_id 
                                where `tournaments`.`sports_id` = $sport_id and `tournaments`.`schedule_type` = 'team' 
                                and `tournament_final_teams`.`deleted_at` is null 
                                and `tournaments`.`deleted_at` is null");
                        $tournament_id  = array();
                        foreach ($tournament_qry as $tournament)
                        {
                                $tournament_id[] = $tournament['id'];
                        }

                        $results = DB::table('tournaments')
                                ->where('tournaments.sports_id', $sport_id)
                                ->whereNotIn('tournaments.id', $tournament_id)
                                ->where('tournaments.schedule_type', '=', 'team')
                                ->where('tournaments.city_id', $city_id)
                                ->whereDate('tournaments.end_date', '>=', date('Y-m-d'))
                                ->whereNull('tournaments.deleted_at')
                                ->limit(5)->offset(0)
                                ->get();
                }
                elseif (!empty($sport_id) && $flag_type == 'player_to_tournament')
                {
                        $tournament_qry = DB::select("select `tournaments`.`id` 
                                from `tournaments` 
                                inner join `tournament_groups` on `tournaments`.`id` = `tournament_groups`.`tournament_id`
                                inner join `tournament_group_teams` on `tournament_group_teams`.`tournament_group_id` = `tournament_groups`.`id`  
                                and `tournament_group_teams`.`team_id` = $team_id 
                                where `tournaments`.`sports_id` = $sport_id and `tournaments`.`schedule_type` = 'individual' 
                                and `tournament_group_teams`.`deleted_at` is null 
                                and `tournaments`.`deleted_at` is null
                                union
                                select `tournaments`.`id` 
                                from `tournaments` 
                                inner join `tournament_final_teams` on `tournaments`.`id` = `tournament_final_teams`.`tournament_id`
                                and `tournament_final_teams`.`team_id` = $team_id 
                                where `tournaments`.`sports_id` = $sport_id and `tournaments`.`schedule_type` = 'individual' 
                                and `tournament_final_teams`.`deleted_at` is null 
                                and `tournaments`.`deleted_at` is null");
                        $tournament_id  = array();
                        foreach ($tournament_qry as $tournament)
                        {
                                $tournament_id[] = $tournament['id'];
                        }

                        $results = DB::table('tournaments')
                                ->where('tournaments.sports_id', $sport_id)
                                ->whereNotIn('tournaments.id', $tournament_id)
                                ->where('tournaments.city_id', $city_id)
                                ->where('tournaments.schedule_type', '=', 'individual')
                                ->whereDate('tournaments.end_date', '>=', date('Y-m-d'))
                                ->whereNull('tournaments.deleted_at')
                                ->limit(5)->offset(0)
                                ->get();
                }
                elseif (!empty($sport_id))
                {
                        $results = DB::table('tournaments')
                                ->where('tournaments.city_id', $city_id)
                                ->where('tournaments.sports_id', '=', $sport_id)
                                ->whereDate('tournaments.end_date', '>=', date('Y-m-d'))
                                ->whereNull('tournaments.deleted_at')
                                ->limit(5)->offset(0)
                                ->get();
                }
                else
                {
                        // $results = DB::table('tournaments')
                        //            ->where('tournaments.city_id',$city_id)
                        //            ->limit(5)->offset(0)
                        //            ->get();
                }
                DB::setFetchMode(PDO::FETCH_CLASS);
                return $results;
        }

        //follow/unfollow
        public function follow_unfollow(Request $request)
        {
                $id      = !empty($request['id']) ? $request['id'] : 0;
                $type    = !empty($request['val']) ? strtolower($request['val']) : null;
                $flag    = !empty($request['flag']) ? $request['flag'] : 0;
                $user_id = Auth::user()->id;
                $result  = 'fail';
                if (is_numeric($id) && !empty($type) && is_numeric($flag))
                {
                        $condition = empty($flag) ? " ,deleted_at=now() " : " ,deleted_at=NULL ";
                        $query     = "INSERT INTO followers (`user_id`,`type`,`type_id`) values ($user_id,'$type',$id)
						ON DUPLICATE KEY UPDATE updated_at=now() $condition";
                        // DB::raw("$query");
                        DB::statement("$query");
                        $result    = 'success';
                }
                return Response::json(['status' => $result]);
        }
        
        static function getTournamentWinner($tournamentDetails, $returnable = [])
        {
            $winner_id = MatchSchedule::select('winner_id')
                ->where('tournament_id', $tournamentDetails->id)
                ->whereNotNull('tournament_match_number')
                ->whereNotNull('winner_id')
                ->orderBy('id','desc')
                ->first();
            
            if (isset($winner_id['winner_id']) && !empty($winner_id['winner_id']))
            {
                return Team::where('id', $winner_id['winner_id'])->first($returnable);
            }
            
            return false;
        }
}
