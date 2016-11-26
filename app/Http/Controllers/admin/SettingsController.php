<?php

namespace App\Http\Controllers\admin;

use Request;
use App\Http\Requests;
use App\Helpers\AllRequests;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use Response;
use App\Helpers\Helper;
use App\Model\BasicSettings;
use Carbon\Carbon;


class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {

      $settings = BasicSettings::first();
     // dd($settings);
      return view('admin.settings.index')->with(array('settings' => $settings));

      }
       public function postIndex()
    {

      //dd($_POST);
      BasicSettings::where('id', 1)->update(['description' => $_POST['description']]);
      return redirect('admin/settings');

      }
}
