<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;
use App\Helpers\Helper;
class MarketPlace extends Model
{
	use SoftDeletes,Eloquence;
    //
    protected $table = 'marketplace';
	protected $searchableColumns = ['item'];
    protected $fillable = array('item','item_description','marketplace_category_id','base_price','actual_price','item_type','item_status','user_id','address','city_id','city' ,'state_id','state','country_id','country','zip','location','contact_number','organization_id');
    protected $morphClass = 'marketplace';
    protected $dates = ['deleted_at'];


    public function photos()
    {
        return $this->morphMany('App\Model\Photo', 'imageable')->where('is_album_cover',1);
    }

	public function categories() {
        return $this->hasMany('App\Model\MarketPlaceCategories', 'id', 'marketplace_category_id');
    }
	
	//Global team search
	public function searchResults($req_params)
	{	
		//echo $req_params['search_by'];exit;
		$offset = !empty($req_params['offset'])?$req_params['offset']:0;		
	    $limit =  !empty($req_params['limit'])?$req_params['limit']:config('constants.LIMIT');	
		$query = $this ->search($req_params['search_by']);
		if(trim($req_params['category']) != ''){
			$query = $query->whereIn('marketplace_category_id',explode(",",$req_params['category']));				
						
		}
		if(trim($req_params['avialability']) != ''){
			$query = $query->whereIn('item_status',explode(",",$req_params['avialability']));						
		}
		if(trim($req_params['amount']) != ''){
			$amount = explode("-",$req_params['amount']);
			$query = $query->whereBetween('base_price',$amount);
		} 
		if(trim($req_params['search_city_id']) != ''){
			$query = $query->where('city_id',trim($req_params['search_city_id']));						
		}
		
		
		$totalresult = $query->get();
		$total= count($totalresult);
		$result= $query->limit($limit)->offset($offset)->orderBy('updated_at', 'desc')->get();
		$response=array('result'=>$result,'total' =>$total);
		return $response;
	}
	//End

	public static function checkPermission($params){
		if($params['loggedin_user_role'] != 'general' || $params['loggedin_user_role'] == $params['owner_user_id'])
			return true;
		else
			return false;
	}
}
