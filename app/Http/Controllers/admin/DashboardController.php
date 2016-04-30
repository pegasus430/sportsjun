<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\DB;
use Auth;
use Zofe\Rapyd\RapydServiceProvider;
use Request;
use App\Http\Requests;
use Response;
use App\Helpers\Helper;

class DashboardController extends Controller {

    public function index() {
        //
		return view('admin.dashboard.dashboard');
    }	
}
?>
