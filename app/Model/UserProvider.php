<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserProvider extends Model {

    //
    protected $table = 'user_providers';
    protected $fillable = array('user_id', 'provider', 'provider_id', 'provider_token', 'avatar',);

    public function user() {
        return $this->belongsTo('App\User');
    }

}
