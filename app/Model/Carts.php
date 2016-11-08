<?php

namespace App\Model;

use App\Helpers\Helper;
use App\User;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Sofa\Eloquence\Eloquence;

class Carts extends Model
{

  protected $table = 'carts';
  public $timestamps = false;
  protected $fillable = ['user_id', 'total_payment'];

  public function cart_details()
    {
        return $this->hasMany('App\Model\CartDetails', 'cart_id', 'id');
    }

 

  }  