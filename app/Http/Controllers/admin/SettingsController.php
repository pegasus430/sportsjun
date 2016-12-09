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
use Input;


class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {

      $settings = BasicSettings::get();
      //dd($settings);
      return view('admin.settings.index')->with(array('settings' => $settings));

      }
       public function postIndex()
    {

      
      $data=Input::except('_token');
      //dd($data);
      foreach($data as $key => $value) {
        BasicSettings::where('name', str_replace('_', ' ',$key))->update(['description' => $value]);
      }
      return redirect('admin/settings');

      }
}
