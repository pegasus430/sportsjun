<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;



class Contactus extends Model
{

    protected $table = 'contactus';
    protected $fillable = array('id','created_by','email','subject','actual_price','message');
  
 

}
