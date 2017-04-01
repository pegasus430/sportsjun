<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SmiteSession extends Model
{
    //
    protected $table = 'smite_sessions';

    protected $fillable = ['token'];
}
