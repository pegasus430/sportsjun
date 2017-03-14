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

use Illuminate\Http\Request as ObjRequest;


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
    
      foreach($data as $key => $value) {
        BasicSettings::where('name', str_replace('_', ' ',$key))->orwhere('name', $key)->update(['description' => $value]);

        //BasicSettings::where('name', $key)->update(['description' => $value]);
      }
      return redirect('admin/settings');

    }

    public function add(ObjRequest $request){
        $setting = new BasicSettings;
        $setting->name = $request->name;
        $setting->type = $request->type;
        $setting->description = $request->description;
        $setting->save();

        return redirect()->back()->with('message', 'Successful');
    }
}
