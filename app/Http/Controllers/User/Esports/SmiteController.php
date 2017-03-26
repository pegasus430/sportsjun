<?php

namespace App\Http\Controllers\User\Esports;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Model\GameUsername;
use App\Model\Sport;

class SmiteController extends Controller
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
        //dd($request);

        $this->validate($request, [
            'username' => 'required|max:30',
        ]);

        $user_id = \Auth::user();
        $sport = Sport::where('sports_name', strtolower('smite'))->first();
        $username = $request->input('username');

        if($game_username = GameUsername::find(1)->where('sport_id', $sport->id)->exists())
            $game_username = GameUsername::find(1)->where('sport_id', $sport->id)->first();
        else
            $game_username = new GameUsername;

        $game_username->user_id = $user_id->id;
        $game_username->sport_id = $sport->id;
        $game_username->username = $username;

        $game_username->save();

        return response()->json(['status' => 'true']);
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
