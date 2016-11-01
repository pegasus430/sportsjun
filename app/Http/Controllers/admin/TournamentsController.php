<?php

namespace App\Http\Controllers\admin;

use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\Sport;
use App\Model\Tournaments;
use App\Model\Team;
use App\Model\TournamentGroups;
use App\Model\TournamentGroupTeams;
use App\Model\Facilityprofile;
use App\Model\VendorBankAccounts;
use Auth;
use Illuminate\Support\Facades\DB;
use Response;
use App\Helpers\Helper;
use App\Model\Photo;
use Carbon\Carbon;
use App\Model\UserStatistic;
use App\User;
use App\Model\MatchSchedule;

class TournamentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



 //    public function index()
 //    {
	// 	 $globalurl=url(); 
	// 	$sports_array2 = Sport::select('sports_name', 'id')->get();
	// 	$sports_array1 = array('0'=>'select sport');
	// 	$sport_id_arr=array();
		
	// 	foreach($sports_array2  as $cat)
	// 	{
	// 		$sports_array1[$cat->id] = $cat->sports_name;
	// 	}
		
 //       	$filter = \DataFilter::source(Tournaments::with('sports','photos'));	
 //        $filter->add('name','Tournament Name','text');		
	// 	$filter->add('sports_id','Sports name','select')->options($sports_array1)
 //        ->scope( function ($query, $value) use ($sports_array2) {
			
	// 		 if($value>0)
	// 		 {
	// 			 return $query->whereIn('sports_id', [$value] );  
	// 		 }else if($value==0)
	// 		 {
	// 			 $sports_array2 = Sport::where('isactive','=',1)->lists('sports_name', 'id')->toArray();
	// 			 foreach($sports_array2 as $key => $val)
	// 			 {
	// 				 $sport_id_arr[] = $key;
	// 			 }
				 
	// 			  return $query->whereIn('sports_id', $sport_id_arr);  
	// 		 }
			
	// 		});	
			
	// 		$test = 'batt.png' ;
	// 	$filter->add('start_date','Date','daterange')->format('Y-m-d H:i:s', 'en')->attr("class","test");		
 //        $filter->submit('search');
 //        $filter->reset('reset');
 //        $filter->build();
	// 	$grid = \DataGrid::source($filter);
	// 	$grid->attributes(array("class"=>"table table-striped"));
 //        //$grid->add('id','ID', true)->style("width:100px");
	// 	//$grid->add('name','TOURNAMENT NAME',true);
	// 	$grid->add('<a href="admintournaments/groups/{{ $id }}">{{ $name }}</a>','NAME');
	// 	// $grid->add('{{ implode(", ", $photos->lists("url")->all()) }}','URL');
	// 	// $grid->add('logo','logo');
	
	// 	 $grid->add('<img src="http://localhost/sportsjun/public/uploads/tournaments/{{ $logo }}" onerror=this.onerror=null;this.src="http://localhost/sportsjun/public/images/default-profile-pic.jpg" height=30 width=30>','LOGO');
		 
		 
	// 	 //$grid->add(" {!! Helper::Images($logo,'tournaments' )!!}",'TEST');
	// // $grid->add( "{!! HTML::image(url('uploads/tournaments/$logo')) !!}",'LOGO');
	// 	// $grid->add( "{!! HTML::image(url('uploads/tournaments/$test')) !!}",'LOGO');
	// 	// $grid->add('{{ implode(", ", $sport->lists("sports_name")->all()) }}','sports Name');
	// //  $grid->add( "{{ url('uploads/marketplace/market_place_default.png') }}"  ,'LOGO');
	

		
	//     $grid->add('{{ implode(", ", $sports->lists("sports_name")->all()) }}','sports Name');
	//     $grid->add('start_date','Start Date',true);		
	//     $grid->add('end_date','End Date',true);		
 //        $grid->edit('editTournament', 'Operation','modify|delete');
 //        $grid->orderBy('id','desc');		
 //        $grid->link('admin/tournaments/create',"New Tournament", "TR");
	// 	$grid->paginate(
 //        config('constants.DEFAULT_PAGINATION'));
	// 	//Helper::printQueries();
 //        return  view('admin.tournaments.filtergrid', compact('filter', 'grid'));
 //    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$enum = config('constants.ENUM.TOURNAMENTS.TYPE'); 
		$sports = Sport::where('isactive','=',1)->lists('sports_name', 'id')->all();
		$schedule_type_enum = config('constants.ENUM.TOURNAMENTS.SCHEDULE_TYPE'); 
		$cities = array();
		$states = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();
        return view("admin.tournaments.create",array('sports'=> [''=>'Select Sport']+$sports,'states' =>  [''=>'Select State']+$states,'cities' =>  [''=>'Select City']+$cities,'enum'=>[''=>'Select Type']+$enum,'type'=>'create','roletype'=>'admin','schedule_type_enum'=>$schedule_type_enum));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CreateTournamentRequest $request)
    {
		//echo Carbon::now()->toDateString();exit;
	    $request['country_id'] = config('constants.COUNTRY_INDIA');
		$request['country'] = Country::where('id', config('constants.COUNTRY_INDIA'))->first()->country_name;
        $request['state'] = !empty($request['state_id']) ? State::where('id', $request['state_id'])->first()->state_name : 'null';
		$request['city'] = !empty($request['city_id']) ? City::where('id', $request['city_id'])->first()->city_name : 'null';
		$location=Helper::address($request['address'],$request['city'],$request['state'],$request['country']);
	    $request['location']=trim($location,",");
		if(!empty($request['start_date']))
		{
		$request['start_date']=date('Y-m-d', strtotime($request['start_date']));
		}
		else
		{
			$request['start_date']= Carbon::now()->toDateString();
		}
		if(!empty($request['end_date']))
		{
		$request['end_date']=date('Y-m-d', strtotime($request['end_date']));
		}
		else
		{
			$request['end_date']= Carbon::now()->toDateString();
		}
		
		//$s=Carbon::createFromFormat('d/m/Y', $request['start_date']);
		//echo $s;exit;
		//echo	$request['end_date'];exit;
		//$request['end_date']=date('Y-m-d', strtotime($request['end_date']));
		$request['created_by'] = Auth::user()->id;
		$request['status'] = 1;
		/*if($request->input('facility_response')!=''&& $request->input('facility_response_name')!='')
		{
		$request['facility_id'] = $request->input('facility_response');
		$request['facility_name'] =$request->input('facility_response_name');
		}*/
	    
		$Tournaments = Tournaments::create($request->all());
		$last_inserted_sport_id = 0;   
	    $last_inserted_sport_id = $Tournaments->id;
		$user_id= Auth::user()->id;
		 //Upload Photos
        $albumID = 1;//Default album if no album is not selected.
        $coverPic = 1;
        if(isset($input['album_id']) && $input['album_id'])
            $albumID = $input['album_id'];
        if(isset($input['cover_pic']) && $input['cover_pic'])
            $coverPic = $input['cover_pic'];        
        Helper::uploadPhotos($request['filelist_photos'],config('constants.PHOTO_PATH.TOURNAMENT'),$last_inserted_sport_id,$albumID,$coverPic,config('constants.PHOTO.TOURNAMENT_LOGO'),$user_id);
		Helper::uploadPhotos($request['filelist_gallery'], config('constants.PHOTO_PATH.TOURNAMENT_PROFILE'),$last_inserted_sport_id, $albumID, $coverPic, config('constants.PHOTO.TOURNAMENT_PROFILE'), $user_id);
	    $count=$Tournaments->groups_number;
		$tournament_type = $Tournaments->type;
		
		
		if($tournament_type == 'league' || $tournament_type == 'multistage' )
		{
		  if($last_inserted_sport_id > 0)
		  {
			for($i=1;$i<=$count;$i++)
			{
		
				$tournamentsgroups = new TournamentGroups();
			    $tournamentsgroups->tournament_id = $last_inserted_sport_id;
			    $tournamentsgroups->name = 'GROUP'.$i;;
			    $tournamentsgroups->save();
			}
		  }
		}	
		
		//insert managed tournaments in user statistics START
		$user_id = Auth::user()->id;//login user id
		$tournament_id = $Tournaments->id;//last inserted tournament id
		if($tournament_id>0)
		{
			$this->updateManagedTournamentsStatistics($user_id,$tournament_id);
		}
		//END managed tournaments
		return redirect()->route('admin.tournaments.index')->with('status', trans('message.tournament.create'));
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\CreateTournamentRequest $request, $id)
    {	
         
        $input_val = Request::all();
       // echo "<pre>"; print_r($input_val); echo "</pre>"; exit;  
        $manager_id = Tournaments::where('id', $id)->pluck('manager_id');
        $country_id = !empty($request['country'])?$request['country']:config('constants.COUNTRY_INDIA');
		//$request['country'] = Country::where('id', config('constants.COUNTRY_INDIA'))->first()->country_name;
		$request['country'] = Country::where('id', $country_id)->first()->country_name;
        //$request['country_id'] = config('constants.COUNTRY_INDIA');
		$request['state'] = !empty($request['state_id']) ? State::where('id', $request['state_id'])->first()->state_name : 'null';
		$request['city'] = !empty($request['city_id']) ? City::where('id', $request['city_id'])->first()->city_name : 'null';
		$location=Helper::address($request['address'],$request['city'],$request['state'],$request['country']);
	    $request['location']=trim($location,",");
		$request['start_date']=date('Y-m-d', strtotime($request['start_date']));
		$request['end_date']=date('Y-m-d', strtotime($request['end_date']));
		$request['created_by'] = Auth::user()->id;
		$request['status'] = 1;


        $request['manager_id'] = !empty($request['user_id'])?$request['user_id']:$manager_id;
		$request['match_type'] = !empty($request['match_type'])?$request['match_type']:'';
		$request['player_type'] = !empty($request['player_type'])?$request['player_type']:'';

		if($request->input('facility_response')!=''&& $request->input('facility_response_name')!='')
		{
		$request['facility_id'] = $request->input('facility_response');
		$request['facility_name'] =$request->input('facility_response_name');
		}
		else
		{
		$request['facility_id'] = NULL;
		}
	

        		// new vendor details and form data
		if(!empty($request['account_holder_name']) && 
			!empty($request['account_number']) &&
			!empty($request['bank_name']) && 
			!empty($request['branch']) &&
			!empty($request['ifsc'])
		 ){
		 	$vendor = new VendorBankAccounts();
			$vendor_bank_account_id = $vendor->saveBankDetails(
				$request['account_holder_name'],
				$request['account_number'],
				$request['bank_name'],
				$request['branch'],
				$request['ifsc'],
				Auth::user()->id
			);
			if($vendor_bank_account_id){
				$request['vendor_bank_account_id'] = $vendor_bank_account_id;
			}
		}
		$request['reg_opening_date']=Helper::storeDate($request['reg_opening_date']);
		$request['reg_closing_date']=Helper::storeDate($request['reg_closing_date']);
		$request['reg_opening_time'] = !empty($request['reg_opening_time'])?$request['reg_opening_time']:'00:00:00';
		$request['reg_closing_time'] = !empty($request['reg_closing_time'])?$request['reg_closing_time']:'00:00:00';
		if( $request['enrollment_type'] == 'online' ){
			$request['enrollment_fee'] = $request['online_enrollment_fee'];
		}
		/////

		if(!empty($request['filelist_file_upload'])) {

           //  /*--files moving from temp directory to attachments directory--*/
             $image_moved=Helper::moveImage($request['filelist_file_upload'],$vendor_bank_account_id);
           /*--files moving from temp directory to attachments directory--*/
          
        }  













		Tournaments::whereId($id)->update($request->except(['_method','_token','facility_response','facility_response_name','files','filelist_photos','filelist_gallery','user_id','account_holder_name','account_number','bank_name','branch','ifsc','filelist_file_upload','online_enrollment_fee']));
		if (!empty($request['filelist_photos'])) {
		Photo::where(['imageable_id'=>$id, 'imageable_type' => config('constants.PHOTO.TOURNAMENT_LOGO')])->update(['is_album_cover' => 0]);
		 //Upload Photos
        $albumID = 1;//Default album if no album is not selected.
        $coverPic = 1;
	    $user_id= Auth::user()->id;
        if(isset($input['album_id']) && $input['album_id'])
            $albumID = $input['album_id'];
        if(isset($input['cover_pic']) && $input['cover_pic'])
            $coverPic = $input['cover_pic']; 
        Helper::uploadPhotos($request['filelist_photos'],config('constants.PHOTO_PATH.TOURNAMENT'),$id,$albumID,$coverPic,config('constants.PHOTO.TOURNAMENT_LOGO'),$user_id);
        //End Upload Photos
		}
		if (!empty($request['filelist_gallery'])) {
		// Photo::where(['user_id' => Auth::user()->id, 'imageable_type' => config('constants.PHOTO.TOURNAMENT_PROFILE')])->update(['is_album_cover' => 0]);
		 //Upload Photos
        $albumID = 1;//Default album if no album is not selected.
        $coverPic = 1;
	    $user_id= Auth::user()->id;
        if(isset($input['album_id']) && $input['album_id'])
            $albumID = $input['album_id'];
        if(isset($input['cover_pic']) && $input['cover_pic'])
            $coverPic = $input['cover_pic']; 
      	Helper::uploadPhotos($request['filelist_gallery'], config('constants.PHOTO_PATH.TOURNAMENT_PROFILE'), $id, $albumID, $coverPic, config('constants.PHOTO.TOURNAMENT_PROFILE'), $user_id);
        //End Upload Photos
		}
	
		return redirect('admin/tournamentevents')->with('status', trans('message.tournament.update'));
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
	public function tournaments_list(Request $request)
	{
		$facility = $request['term'];
		$facility_array = Facilityprofile::select('id','name')->where('name','LIKE','%'.$facility.'%')->get();
		$results = array();
		foreach($facility_array as $facilitylist_array  )
		{
			$results[] = ['id' =>  $facilitylist_array->id, 'value' =>  $facilitylist_array->name];
		}
		 return Response::json($results);
	}
	 public function edit(Request $request)
    {
	    
	    
	    $enum = config('constants.ENUM.TOURNAMENTS.TYPE'); 
	    $game_type_enum = config('constants.ENUM.TOURNAMENTS.GAME_TYPE');
	    $enrollment_type = config('constants.ENUM.TOURNAMENTS.ENROLLMENT_TYPE');
	    $player_types = array();
	    $match_types = array();

	    foreach (config('constants.ENUM.SCHEDULE.PLAYER_TYPE') as $key => $val) {
			$player_types[$key] = $val;
		}
		$sports = Sport::where('isactive','=',1)->lists('sports_name', 'id');
        $delete_tournament_id='';
        $edit_tournament_id='';
       
        if(isset($_GET['delete'])) {
        $delete_tournament_id = $_GET['delete'];
        }
        if(isset($_GET['modify'])) {
	    $edit_tournament_id =$_GET['modify'];
	    }
		
		if($delete_tournament_id!='' && $delete_tournament_id>0)
		{
			$dt=date('Y-d-m');
			//echo $dt; exit;
            $tournament=Tournaments::where('id',$delete_tournament_id)->update(['isactive'=>0,'deleted_at'=>$dt]);   
			//$tournament=Tournaments::find($delete_tournament_id )->delete();
			return redirect('admin/tournamentevents')->with('status', trans('message.tournament.delete'));
		}
		
		else if( $edit_tournament_id!='' &&  $edit_tournament_id>0)
		{
		   $tournament = Tournaments::findOrFail($edit_tournament_id);
		   $countries = Country::orderBy('country_name')->lists('country_name', 'id')->all();
	       //$states = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();
	        $states = State::where('country_id', $tournament->country_id)->orderBy('state_name')->lists('state_name', 'id')->all();
		   	$schedule_type_enum = config('constants.ENUM.TOURNAMENTS.SCHEDULE_TYPE'); 
	       $cities = City::where('state_id',  $tournament->state_id)->orderBy('city_name')->lists('city_name', 'id')->all();

	       $sport_name = Sport::where('id', $tournament['sports_id'])->pluck('sports_name');
	       $sport_name = !empty($sport_name) ? $sport_name : '';
		   $match_types = Helper::getMatchTypes(strtoupper($sport_name));
	       $manager_name='';
		   $managerName = User::where('id',$tournament->manager_id)->first(['name']);
		   if(count($managerName))
			 $manager_name = $managerName->name;

          $tournament_owner_id=$tournament->created_by;
         
		  $vendorBankAccounts = VendorBankAccounts::where('user_id',$tournament_owner_id)->get();
		  $online_enrollment_fee=$tournament->enrollment_fee;


         // echo "<pre>"; print_r($tournament); echo "</pre>"; exit;  

	       return view('admin.tournaments.edit',compact('tournament'))->with(array('sports'=> $sports,'id'=> $edit_tournament_id,'countries' => $countries,'states' =>  $states,'cities' => $cities,'enum'=>$enum,'tournament'=>$tournament,'type'=>'edit','roletype'=>'admin','schedule_type_enum'=>$schedule_type_enum,'game_type_enum'=>$game_type_enum,'player_types'=>$player_types,'match_types'=>['' => 'Select Match Type'] + $match_types,'tournamentGroupCount'=>$tournament->groups_number,'enrollment_type'=>$enrollment_type,'manager_name'=>$manager_name,'online_enrollment_fee' => $online_enrollment_fee,'vendorBankAccounts' => $vendorBankAccounts));
		}
    }
		//function to display tournament groups
	public function groups($tournament_id) {
        $tournaments = Tournaments::with('groups')->where('id', '=', $tournament_id)->get(); //get tournaments which having the type as league
        $tournament_type = $tournaments[0]['type'];
        $team_details = array();
        $match_details = array();
        foreach ($tournaments as $tournament) {
            foreach ($tournament->groups as $groups) {
                $group_id = $groups->id;
                $teamDetails = TournamentGroupTeams::select()->where('tournament_group_id', $group_id)->get();

                if (count($teamDetails) > 0)
                    $team_details[$group_id] = $teamDetails->toArray(); //get tournament group teams

                $matchDetails = MatchSchedule::select()->where('tournament_id', $tournament_id)->where('tournament_group_id', $group_id)->get();
                if (count($matchDetails) > 0)
                    $match_details[$group_id] = $matchDetails->toArray(); //get tournament group teams match schedules
            }
        }
        $sport_id = $tournaments[0]['sports_id']; //sport id
        $schedule_type = !empty($tournaments[0]['schedule_type']) ? $tournaments[0]['schedule_type'] : 'team'; //schedule type 
        $team_name_array = array();
        $team_logo = array();
        $user_name = array();
        $user_profile = array();
        if ($schedule_type == 'team') {
            $teams = Team::select('id', 'name')->where('sports_id', $sport_id)->get()->toArray(); //get teams
            foreach ($teams as $team) {
                $team_name_array[$team['id']] = $team['name']; //get team names
                $team_logo[$team['id']] = Photo::select()->where('imageable_id', $team['id'])->where('imageable_type', config('constants.PHOTO.TEAM_PHOTO'))->orderBy('id', 'desc')->first(); //get team logo
            }
        } else {
            $users = User::select('id', 'name')->get()->toArray(); //if scheduled type is player
            foreach ($users as $user) {
                $user_name[$user['id']] = $user['name']; //get team names
                $user_profile[$user['id']] = Photo::select()->where('imageable_id', $user['id'])->where('imageable_type', config('constants.PHOTO.USER_PHOTO'))->orderBy('id', 'desc')->first(); //get team logo
            }
        }

        Helper::setMenuToSelect(4, 1);
        $lef_menu_condition = 'display_gallery';
        //getting states
        $states = State::where('country_id', config('constants.COUNTRY_INDIA'))->orderBy('state_name')->lists('state_name', 'id')->all();

        $match_types = array();
        $player_types = array();

        //get sport name
        $sport_name = Sport::where('id', $sport_id)->pluck('sports_name');
        // if($isOwner)
        // {
        //building match types array
        $sport_name = !empty($sport_name) ? $sport_name : '';
        $match_types = Helper::getMatchTypes(strtoupper($sport_name));
        //building player types array
        foreach (config('constants.ENUM.SCHEDULE.PLAYER_TYPE') as $key => $val) {
            $player_types[$key] = $val;
        }
        // }
        //Start - Logic for final stage teams

        if (count($tournaments[0]['final_stage_teams'])) {
            if($schedule_type == 'team') {
                    $scheduleTypeOne = 'scheduleteamone';
                    $scheduleTypeTwo = 'scheduleteamtwo';
            }else {
                    $scheduleTypeOne = 'scheduleuserone';
                    $scheduleTypeTwo = 'scheduleusertwo';    
            }
            
            $matchScheduleData = MatchSchedule::with(array($scheduleTypeOne => function($q1) {
                    $q1->select('id', 'name');
                },
                        $scheduleTypeTwo => function($q2) {
                    $q2->select('id', 'name');
                }, $scheduleTypeOne.'.photos', $scheduleTypeTwo.'.photos'))
                    ->where('tournament_id', $tournament_id)->whereNull('tournament_group_id')
                    ->orderBy('id')
                    ->get(['id', 'tournament_id', 'tournament_round_number', 'tournament_match_number', 'a_id', 'b_id', 'match_start_date', 'winner_id']);

             
            if (count($matchScheduleData)) {
                foreach ($matchScheduleData->toArray() as $key => $schedule) {
                    if ($schedule[$scheduleTypeOne] == 1) {
                        if (count($schedule[$scheduleTypeOne]['photos'])) {
                            $teamOneUrl = array_collapse($schedule[$scheduleTypeOne]['photos']);
                            $matchScheduleData[$key][$scheduleTypeOne]['url'] = $teamOneUrl['url'];
                        } else {
                            $teamOneUrl = $matchScheduleData[$key][$scheduleTypeOne];
                            $teamOneUrl['url'] = '';
                        }

                        if (count($schedule[$scheduleTypeTwo]['photos'])) {
                            $teamTwoUrl = array_collapse($schedule[$scheduleTypeTwo]['photos']);
                            $matchScheduleData[$key][$scheduleTypeTwo]['url'] = $teamTwoUrl['url'];
                        } else {
                            $teamTwoUrl = $matchScheduleData[$key][$scheduleTypeTwo];
                            $teamTwoUrl['url'] = '';
                        }

                        $matchStartDate = Carbon::createFromFormat('Y-m-d', $schedule['match_start_date']);

                        if (!empty($schedule['winner_id'])) {
                            $matchScheduleData[$key]['winner_text'] = trans('message.schedule.matchstats');
                        } else if (Carbon::now()->gte($matchStartDate)) {
                            //                            if ($isOwner) {
                            $matchScheduleData[$key]['winner_text'] = trans('message.schedule.addscore');
                            //                            }
                        }
                        $matchScheduleData[$key]['match_start_date'] = $matchStartDate->toDayDateTimeString();
                    }
                }

                $maxRoundNumber = $matchScheduleData->max('tournament_round_number');
                for ($i = 1; $i <= $maxRoundNumber; $i++) {
                    $roundArray[$i] = $i;
                }

//                        $maxRoundNumberArray = MatchSchedule::where('tournament_id',$tournament_id)
//                                                 ->whereNull('tournament_group_id')
//                                                 ->orderBy('id','desc')->take(1)->first(['id','tournament_round_number']);
//                        $maxRoundNumber = 1;
//                        if(count($maxRoundNumberArray)) {
//                            $maxRoundNumber = $maxRoundNumberArray['tournament_round_number'];
//                            for($i=1;$i<=$maxRoundNumberArray['tournament_round_number'];$i++) {
//                                $roundArray[$i]=$i;
//                            }
//                        }

                $bracketTeamArray = $this->getBracketTeams($tournament_id, $maxRoundNumber, $schedule_type);
                if (!empty($bracketTeamArray)) {
                    foreach ($bracketTeamArray as $bracketkey => $bracketTeam) {
                        foreach ($bracketTeam as $team => $bracket) {
                            if ($team == 'start_date' && $team !== 0) {
                                unset($bracketTeamArray[$bracketkey][$team]);
                            }
                        }
                    }
                }
//                        dd($bracketTeamArray);
            }
        } else {
            $tournamentTeamIds = $this->getTournamentTeams($tournament_id);
            if (count($tournamentTeamIds)) {
                $teamIDs = explode(',', trim($tournamentTeamIds, ','));
                if($schedule_type == 'team') {
                    $tournamentTeams = Team::whereIn('id', $teamIDs)->orderBy('name')->lists('name', 'id')->all();
                }else{
                    $tournamentTeams = User::whereIn('id', $teamIDs)->orderBy('name')->lists('name', 'id')->all();
                }    
            }
        }
        // End - Logic for final stage teams
        return view('admin.tournaments.groups', array('tournament' => $tournaments, 'team_details' => $team_details, 'tournament_id' => $tournament_id,
                            'lef_menu_condition' => $lef_menu_condition, 'action_id' => $tournament_id, 'match_details' => $match_details, 'team_name_array' => $team_name_array,
                            'team_logo' => $team_logo, 'user_name' => $user_name, 'user_profile' => $user_profile, 'schedule_type' => $schedule_type,
                            'tournament_type' => $tournament_type, 'tournamentDetails' => $tournaments->toArray(), 'team_name' => '',
                            'tournamentTeams' => !empty($tournamentTeams) ? $tournamentTeams : [], 'sports_id' => $sport_id, 
                            'roundArray' => !empty($roundArray) ? $roundArray : [],
                            'scheduleTypeOne' => !empty($scheduleTypeOne)?$scheduleTypeOne:'','scheduleTypeTwo' => !empty($scheduleTypeTwo)?$scheduleTypeTwo:'',
                            'bracketTeamArray' => !empty($bracketTeamArray) ? $bracketTeamArray : []))
                        ->with('match_types', ['' => 'Select Match Type'] + $match_types)
                        ->with('player_types', ['' => 'Select Player Type'] + $player_types)
                        ->with('states', ['' => 'Select State'] + $states)
                        ->with('cities', ['' => 'Select City'] + array())
                        ->with('matchScheduleData', !empty($matchScheduleData) ? $matchScheduleData : []);
    }
	 function getTournamentTeams($tournamentId) {
	   $teams = TournamentGroups::with('group_teams' 
	                       )->where('tournament_id',$tournamentId)->get();
	   $teamIds = '';
	   if(count($teams)) {
	       foreach($teams as $team) {
	           foreach($team->group_teams as $teamid) {
	               $teamIds.= $teamid->team_id.',';
	           } 
	       }
	   }
	   if(!empty($teamIds)) {
	       $teamIds = trim($teamIds,',');
	   }
	   return $teamIds;
	   
	} 
	public function getUsers()
	{
		$results=array();
		$user_name = Request::get('term');
		$users = User::where('name','LIKE','%'.$user_name.'%')->get(['id','name']);
		if(count($users)>0)
		{
			foreach ($users as $query)
			{
				$results[] = ['id' => $query->id, 'value' => $query->name];
			}
		}
		return Response::json($results);
	}

}
