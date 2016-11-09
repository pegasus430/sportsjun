<?php

namespace App\Model;

use App\Helpers\Helper;
use App\User;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Sofa\Eloquence\Eloquence;

class CartDetails extends Model
{

  protected $table = 'cart_details';
  public $timestamps = false;
  protected $fillable = ['cart_id','event_id', 'enrollment_fee', 'match_type','participant_count'];

   public function cart()
    {
        return $this->belongsTo('App\Model\Cart', 'cart_id',
            'id');
    }

    public function tournaments()
    {
        return $this->belongsTo('App\Model\Tournaments', 'event_id', 'id');
    }


  }