<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InfoListStoreRequest;
use App\Http\Requests\Admin\InfoListUpdateRequest;
use App\Model\Infolist;

class HomepageInfolistsController extends Controller {

    public function index($type){
        $lists = InfoList::whereType($type)->paginate(15);
        return view('admin.home.'.$type.'.index',compact('lists'));
    }

    public function create($type){
        return view('admin.home.'.$type.'.form',['item'=>null]);
    }

    public function store(InfoListStoreRequest $request,$type){

        $img = Helper::uploadImageSimple($request->image,$type);

        $infolist = Infolist::create([
            'name'=>$request->name,
            'description'=>object_get($request,'description'),
            'type'=>$type,
            'image'=>$img,
            'active'=>1,
            'weight'=>object_get($request,'weight',0)
        ]);

        \Session::flash('message','Successfully created');
        return redirect()->route('admin.home.infolists',$type);

    }

    public function edit(Infolist $infolist){
        return view('admin.home.'.$infolist->type.'.form',['item'=>$infolist]);
    }

    public function update(InfolistUpdateRequest $request,Infolist $infolist){
        if ($request->image) {
            $infolist->image =  Helper::uploadImageSimple($request->image,$infolist->type);
        }
        $infolist->name = $request->name;
        $infolist->description = object_get($request,'description');
        $infolist->weight = object_get($request,'weight',0);
        $infolist->save();

        \Session::flash('message','Successfully updated');
        return redirect()->route('admin.home.infolists',$infolist->type);

    }

    public function delete(Infolist $infolist){
        $type= $infolist->type;
        $infolist->delete();
        \Session::flash('message','Successfully deleted');
        return redirect()->route('admin.home.infolists',$type);

    }

}
?>