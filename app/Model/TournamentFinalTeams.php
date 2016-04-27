<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TournamentFinalTeams extends Model {

    use SoftDeletes;
    protected $table = 'tournament_final_teams';
    protected $dates = ['deleted_at'];
    
    public function getKnockoutTeams($tournamentId, $schedule_type) {
        $tournamentKnockoutTeams = TournamentFinalTeams::where('tournament_id',$tournamentId)->get(['team_id']);
        if(count($tournamentKnockoutTeams)) {
        $tournamentKnockoutTeamsIds = array_flatten($tournamentKnockoutTeams->toArray());
                if($schedule_type == 'team') {
                    $tournamentTeams = Team::whereIn('id', $tournamentKnockoutTeamsIds)->orderBy('name')->get(['id','name']);
                }else{
                    $tournamentTeams = User::whereIn('id', $tournamentKnockoutTeamsIds)->orderBy('name')->get(['id','name']);
                }
        }
       
        return !empty($tournamentTeams)?$tournamentTeams:'';
    }
   
}


