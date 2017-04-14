<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;

class HockeyStatistic extends Model {
    use SoftDeletes,Eloquence;
    //
    protected $table = 'hockey_statistic';
    //protected $fillable = array('user_id', 'following_sports', 'following_teams', 'managing_teams', 'joined_teams', 'following_tournaments', 'managing_tournaments', 'joined_tournaments', 'following_players', 'following_facilities', 'provider_id', 'provider_token', 'avatar',);

    public static function updateUserStatistic($user_id){
        //check already player has record or not
        $user_hockey_details = HockeyStatistic::select()->where('user_id',$user_id)->get();

        $hockey_details = HockeyPlayerMatchwiseStats::selectRaw('count(match_id) as match_count')->selectRaw('sum(yellow_cards) as yellow_cards')->selectRaw('sum(red_cards) as red_cards')->selectRaw('sum(goals_scored) as goals_scored')->where('user_id',$user_id)->groupBy('user_id')->get();
        $yellow_card_cnt = (!empty($hockey_details[0]['yellow_cards']))?$hockey_details[0]['yellow_cards']:0;
        $red_card_cnt = (!empty($hockey_details[0]['red_cards']))?$hockey_details[0]['red_cards']:0;
        $goals_cnt = (!empty($hockey_details[0]['goals_scored']))?$hockey_details[0]['goals_scored']:0;
        if(count($user_hockey_details)>0)
        {
            $match_count = (!empty($hockey_details[0]['match_count']))?$hockey_details[0]['match_count']:0;
            HockeyStatistic::where('user_id',$user_id)->update(['matches'=>$match_count,'yellow_cards'=>$yellow_card_cnt,'red_cards'=>$red_card_cnt,'goals_scored'=>$goals_cnt]);
        }else
        {
            $hockey_statistics = new HockeyStatistic();
            $hockey_statistics->user_id = $user_id;
            $hockey_statistics->matches = 1;
            $hockey_statistics->yellow_cards = $yellow_card_cnt;
            $hockey_statistics->red_cards = $red_card_cnt;
            $hockey_statistics->goals_scored = $goals_cnt;
            $hockey_statistics->save();
        }
    }

}
