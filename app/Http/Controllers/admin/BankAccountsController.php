<?php

namespace App\Http\Controllers\admin;

use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\Sport;
use App\Model\VendorBankAccounts;
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

class BankAccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {

		$globalurl=url(); 
		
		
       	$filter = \DataFilter::source(VendorBankAccounts::with('user'));

        $filter->add('user.name','Name','text');
        $filter->add('account_holder_name','Account Holder Name','text');

		
        $filter->submit('search');
        $filter->reset('reset');
        $filter->build();
		$grid = \DataGrid::source($filter);
		$grid->attributes(array("class"=>"table table-striped"));

		$grid->add('user.name','Name');
        $grid->add('<a href="bankaccounts/details/{{ $id }}">{{ $account_holder_name }}</a>','Account Holder Name');
        $grid->add('bank_name','Bank Name');
        $grid->add('<a href="bankaccounts/details/{{ $id }}">{{ $user->email }}</a>','User Email');
	    $grid->add('varified','Status')->cell( function( $value, $row) {
	        return ['0'=>'Pending','1'=>'Approved'][$value];
	   	});
        // $grid->edit('editTournament', 'Operation','modify|delete');
        $grid->orderBy('id','desc');		
        // $grid->link('admin/tournaments/create',"New Tournament", "TR");
		$grid->paginate(
        	config('constants.DEFAULT_PAGINATION')
        );
		//Helper::printQueries();
        return  view('admin.bankaccounts.index', compact('filter', 'grid'));
    }
    public function getDetails($id){
        $bankDetails = VendorBankAccounts::with('user')->where('id',$id)->first();
        if($bankDetails){
            return  view('admin.bankaccounts.details', compact('bankDetails'));
        }
    }
}
