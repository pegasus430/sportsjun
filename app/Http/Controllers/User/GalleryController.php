<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Album;
use App\Model\Photo;
use App\Model\Gallery;
use Auth;
use App\Helpers\Helper;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id='',$type='')
    {
        //Gallery Photos
        //User or Porfile Photos
        //Check Owner or User
        //List all albums with count of photos in each album
        //Create album
        //Upload multiple photos
        if($id=='')
            $id = Auth::user()->id;
        if($type=='')
            $type = 'gallery';
        $list = Album::with('photosWithCover')->where('user_id',Auth::user()->id)
                                              ->whereIn('')
                                                ->get();
        //$photos = Gallery->where('user_id',Auth::user()->id)->get();
        //Helper::printQueries();
        //                   dd($list);
        Helper::setMenuToSelect(3,0);
        return view('gallery.list')->with('list',$list);
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
}
