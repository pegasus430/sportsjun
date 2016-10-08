<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Validator;
use Session;
use Storage;
//use Intervention\Image\ImageManagerStatic as Image;

class TempphotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
return json_encode(array (
  'files' => 
  array (
    0 => '../uploads/Jellyfish.jpg',
  ),
  'metas' => 
  array (
    0 => 
    array (
      'date' => 'Fri, 11 Dec 2015 13:49:17 +0100',
      'extension' => 'jpg',
      'file' => '../uploads/Jellyfish.jpg',
      'name' => 'Jellyfish.jpg',
      'old_name' => 'Jellyfish',
      'replaced' => false,
      'size' => 775702,
      'size2' => '757.52 KB',
      'type' => 
      array (
        0 => 'image',
        1 => 'jpeg',
      ),
    ),
  ),
));
    }
    public function store1(Request $request)
    {
        //Image::make( 'uploads/img/05.jpg' )->fit(340, 340)->save('uploads/img/cropped.jpg')->destroy();
        //dd();
        /*
 if(Input::file())
        {
  
            $image = Input::file('image');
            $filename  = time() . '.' . $image->getClientOriginalExtension();

            $path = public_path('profilepics/' . $filename);
 
        
                Image::make($image->getRealPath())->resize(200, 200)->save($path);
                $user->image = $filename;
                $user->save();
           }
        */
        $files = Input::file('files');
        // Making counting of uploaded images
        $file_count = count($files);
        // start count how many uploaded
        $uploadcount = 0;
        foreach($files as $file) {
          $rules = array('file' => 'required'); //'required|mimes:png,gif,jpeg,txt,pdf,doc'
          $validator = Validator::make(array('file'=> $file), $rules);
          if($validator->passes()){
            $destinationPath = 'uploads/temp';
            $orgFileName = $file->getClientOriginalName();
            $filename = $orgFileName.'####SPORTSJUN####'.str_random(20).'.'.$file->getClientOriginalExtension();
            $upload_success = $file->move($destinationPath, $filename);
            //$disk = Storage::disk('rackspace')->put($filename);
            //dd($disk);
            $uploadcount ++;
          }
        }
        return json_encode(array('name' => $filename ));        
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
}
