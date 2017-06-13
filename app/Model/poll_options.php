<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class poll_options extends Model
{
    //

	public function poll(){
		return $this->belongsTo('App\Model\poll');
	}

    public function percentage(){
    	$option_id = $this->id;
    	$total_votes = $this->poll->voters->count();
    	$my_votes = $this->poll->option_voters($option_id)->count();

    	if($total_votes==0) return 0;
    	else return ($my_votes/$total_votes) * 100;
    }


}
