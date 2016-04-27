<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SportQuestionOption extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;

    protected $table = 'sports_questions_options';
    protected $dates = ['deleted_at'];

    public function questions() {
        return $this->belongsTo('App\Model\SportQuestion', 'sports_questions_id');
    }

}
