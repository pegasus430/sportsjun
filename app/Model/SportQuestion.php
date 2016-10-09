<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SportQuestion extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;

    protected $table = 'sports_questions';
    protected $dates = ['deleted_at'];

    public function options() {
        return $this->hasMany('App\Model\SportQuestionOption', 'sports_questions_id', 'id');
    }

    public function answers() {
        return $this->hasMany('App\Model\SportQuestionAnswer', 'sports_questions_id', 'id');
    }

}
