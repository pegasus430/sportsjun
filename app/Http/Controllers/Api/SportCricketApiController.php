<?php

namespace App\Http\Controllers\Api;


use App\Model\CricketOverwiseScore;
use App\Model\CricketPlayerMatchwiseStats;
use App\Model\CricketStatistic;

class  SportCricketApiController extends BaseApiController
{

    public function cricketStatistics()
    {
        $statistic = $this
                        ->applyFilter(CricketStatistic::select(),[])
                        ->paginate(50);

        return $this->ApiResponse($statistic);
    }

    public function cricketPlayerMatchScore(){
        $stats = $this
                         ->applyFilter(CricketPlayerMatchwiseStats::select(),[])
                         ->paginate(50);
        return $this->ApiResponse($stats);
    }

    public function cricketOverwiseScore(){
        $score =  $this
                         ->applyFilter(CricketOverwiseScore::select(),[])
                         ->paginate(50);
        return $this->ApiResponse($score);
    }

    public function setCricketOverwiseScore($id){
        $data = \Request::all();

        $validator = \Validator::make($data, [
            'tournament_id' => 'required|exists:tournaments,id',
            'team_id' => 'required|exists:teams,id',
            'match_id' => 'required|exists:match_schedules,id',
            'over'=>'required',
            'ball_by_ball' => 'required'
        ]);

        if (!$validator->fails()) {
            $user = \Auth::user();
            if ($id){
                // can edit ?
                $error = 'Editing not allowed';
            } else {
                $score = new CricketOverwiseScore();
                $score->user_id = $user->id;
                $score->tournament_id = $data['tournament_id'];
                $score->team_id = $data['team_id'];
                $score->over = $data['over'];
                $score->ball_by_ball = $data['ball_by_ball'];
                $score->isactive = 1;
                $score->save();
                return $this->ApiResponse($score->id);
            }
        } else {
            $error = $validator->errors()->first();
        }

        return $this->ApiResponse(['error' => $error], 500);
    }

}