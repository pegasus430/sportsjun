<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BasketballStatistic extends Model
{
    //
     protected $table = 'basketball_statistic';

    public static function updateUserStatistic($user_id){
        //check already player has record or not
        $user_basketball_details = BasketballStatistic::select()->where('user_id',$user_id)->get();

        $basketball_details = BasketballPlayerMatchwiseStats::selectRaw('count(match_id) as match_count')->selectRaw('sum(points_1) as points_1')->selectRaw('sum(points_2) as points_2')->selectRaw('sum(points_3) as points_3')->selectRaw('sum(total_points) as total_points')->selectRaw('sum(fouls) as fouls')->where('user_id',$user_id)->groupBy('user_id')->get();



        $points_1 = (!empty($basketball_details[0]['points_1']))?$basketball_details[0]['points_1']:0;
        $points_2 = (!empty($basketball_details[0]['points_2']))?$basketball_details[0]['points_2']:0;
        $points_3 = (!empty($basketball_details[0]['points_3']))?$basketball_details[0]['points_3']:0;
        $fouls = (!empty($basketball_details[0]['fouls']))?$basketball_details[0]['fouls']:0;
        $total_points = (!empty($basketball_details[0]['total_points']))?$basketball_details[0]['total_points']:0;

        if(count($user_basketball_details)>0)
        {
            $match_count = (!empty($basketball_details[0]['match_count']))?$basketball_details[0]['match_count']:0;

            BasketballStatistic::where('user_id',$user_id)
                               ->update([	'matches'=>$match_count,
                                             'points_1'=>$points_1,
                                             'points_2'=>$points_2,
                                             'points_3'=>$points_3,
                                             'fouls'=>$fouls,
                                             'total_points'=>$total_points
                               ]);
        }else
        {
            $basketball_statistic = new BasketballStatistic();
            $basketball_statistic->user_id = $user_id;
            $basketball_statistic->matches = 1;
            $basketball_statistic->{'points_1'} = $points_1;
            $basketball_statistic->{'points_2'} = $points_2;
            $basketball_statistic->{'points_3'} = $points_3;
            $basketball_statistic->fouls = $fouls;
            $basketball_statistic->total_points = $total_points;
            $basketball_statistic->save();
        }
    }

}
