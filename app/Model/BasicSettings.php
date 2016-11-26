<?php

namespace App\Model;

use App\Helpers\Helper;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Sofa\Eloquence\Eloquence;

class BasicSettings extends Model
{

  protected $table = 'settings';
  public $timestamps = false;
  protected $fillable = ['name','description'];

  

 

  }  