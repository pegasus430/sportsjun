<?php

namespace App\Model;

use App\Helpers\Helper;
use App\User;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Sofa\Eloquence\Eloquence;

class PaymentSetups extends Model
{

  protected $table = 'payment_setups';
  public $timestamps = false;
  protected $fillable = ['gateway_id','setup_name', 'setup_value', 'status'];

  public function paymentGayeWays()
    {
        return $this->belongsTo('App\Model\paymentGayeWays', 'gateway_id',
            'id');
    }

}