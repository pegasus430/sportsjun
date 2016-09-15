<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SportQuestionAnswer extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;

    protected $table = 'sports_questions_answers';
    protected $fillable = array('user_id', 'sports_questions_id', 'sports_option_id');
    protected $dates = ['deleted_at'];

    public function questions() {
        return $this->belongsTo('App\Model\SportQuestion', 'sports_questions_id');
    }

    public function userquestions() {
        return $this->belongsTo('App\Model\SportQuestion', 'sports_questions_id');
    }

}
