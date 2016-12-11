<?php

namespace App\Model;

use App\Helpers\Helper;
use App\User;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Sofa\Eloquence\Eloquence;

class PaymentDetails extends Model
{

  protected $table = 'payment_details';
  public $timestamps = false;
  protected $fillable = ['cart_id','payment_firstname','payment_address','payment_country','payment_state','payment_city','payment_zipcode','payment_phone','mihpayid','status','amount','date'];

  

 

  } 