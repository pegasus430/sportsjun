<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facilitysports extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;

    protected $table = 'facility_sports';
    protected $dates = ['deleted_at'];

    public function facilityprofile() {
        return $this->belongsTo('App\Model\Facilityprofile', 'facility_id');
    }
	public function sports() {
        return $this->belongsTo('App\Model\Sport', 'sports_id');
    }

}
