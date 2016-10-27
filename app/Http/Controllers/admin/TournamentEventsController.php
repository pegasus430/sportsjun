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
use Auth;
use Illuminate\Support\Facades\DB;
use Response;
use App\Helpers\Helper;
use App\Model\Photo;
use Carbon\Carbon;
use App\Model\UserStatistic;
use App\User;
use App\Model\MatchSchedule;

class TournamentEventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

		$globalurl=url(); 
		$sports_array2 = Sport::select('sports_name', 'id')->get();
		$sports_array1 = array('0'=>'select sport');
		$sport_id_arr=array();
		
		foreach($sports_array2  as $cat){
			$sports_array1[$cat->id] = $cat->sports_name;
		}
		
       	$filter = \DataFilter::source(Tournaments::with('sports','photos'));	
        $filter->add('tournament_parent_name','Tournament Name','text');
        $filter->add('email','Organizer email','text');

		$filter->add('sports_id','Sports name','select')->options($sports_array1)
        ->scope( function ($query, $value) use ($sports_array2) {
			
		if($value>0){
			return $query->whereIn('sports_id', [$value] );  
		}else if($value==0){
			$sports_array2 = Sport::where('isactive','=',1)->lists('sports_name', 'id')->toArray();
			foreach($sports_array2 as $key => $val){
				$sport_id_arr[] = $key;
			}
				 
			return $query->whereIn('sports_id', $sport_id_arr);  
		}
			
		});	
			
		$test = 'batt.png' ;
		$filter->add('location','Location','text');
		$filter->add('enrollment_type','Payment Type','select')->options(config('constants.ENUM.TOURNAMENTS.ENROLLMENT_TYPE'));
		$filter->add('status','Status','select')->options(config('constants.ENUM.TOURNAMENTS.STATUS'));

        $filter->submit('search');
        $filter->reset('reset');
        $filter->build();
		$grid = \DataGrid::source($filter);
		$grid->attributes(array("class"=>"table table-striped"));

		$grid->add('tournament_parent_name','Tournament Name');
		$grid->add('name','Tournament Event');
	    $grid->add('start_date','Start Date');		
	    $grid->add('end_date','End Date');	
	    $grid->add('location','Location');
	    $grid->add('{{ $email }}/{{ $contact_number }}','Organizer email/Phone');

	    $grid->add('enrollment_type','Registration Type')->cell( function( $value, $row) {
	        return config('constants.ENUM.TOURNAMENTS.ENROLLMENT_TYPE')[$value];
	   	});
	    $grid->add('status','Status')->cell( function( $value, $row) {
	        return config('constants.ENUM.TOURNAMENTS.STATUS')[$value];
	   	});
	    $grid->add('id','Registration count');

        // $grid->edit('editTournament', 'Operation','modify|delete');
        $grid->orderBy('id','desc');		
        // $grid->link('admin/tournaments/create',"New Tournament", "TR");
		$grid->paginate(
        	config('constants.DEFAULT_PAGINATION')
        );
		//Helper::printQueries();
        return  view('admin.tournamentevents.index', compact('filter', 'grid'));
    }
}
