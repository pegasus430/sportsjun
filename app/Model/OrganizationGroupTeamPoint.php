<?php

namespace App\Model;

use App\Model\TournamentGroupTeams;

use Illuminate\Database\Eloquent\Model;

class OrganizationGroupTeamPoint extends Model
{
    //
    
    function sport(){
    		return $this->hasOne('App\Model\Sport',  'id', 'sports_id');
    }

    function TournamentParent(){
    		return $this->belongsTo('App\Model\TournamentParent');
    }

    function Tournament(){
    		return $this->belongsTo('App\Model\Tournament');
    }

    function getGroupPoints($tournament_parent_id, $organization_group_id, $sport_id){
    		return $this->whereTournamentParentId($tournament_parent_id)->whereOrganizationGroupId($organization_group_id)->whereSportId($sport_id)->first();
    }


}
