<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    //https://laracasts.com/discuss/channels/general-discussion/laravel-5-modify-input-before-validation?page=2
    //https://gist.github.com/drakakisgeo/3bba2a2600b4c554f836
	public function all()
	{
	    $attributes = parent::all();
	    return array_map(array($this, 'sanitizeInput'), $attributes);
	    //return $attributes;
	}

	public function sanitizeInput($var){
		 return is_string($var) ? trim($var) : $var;
	}
}
