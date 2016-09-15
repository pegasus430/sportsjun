<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;

class Facilityprofile extends Model {

	use Eloquence;
	protected $table = 'facility_profile';
	protected $dates = ['deleted_at'];
	protected $searchableColumns = ['name','location'];
	protected $morphClass = 'facility';
	protected $fillable = array('user_id','created_by','name','contact_number','alternate_contact_number','contact_name','facility_type','facility_service','email','amenities_flood_lights','amenities_refrigerators','amenities_refreshments',
	'amenities_dressing_room','amenities_bathroom','amenities_water','social_facebook','social_twitter','social_linkedin','social_googleplus','website_url','description','verified','location','longitude',
	'latitude','address','city_id','city' ,'state_id','state','country_id','country','zip','alternate_personcontact_name','alternate_personcontact_number','amenities_pavilion','logo');
 public function photos()
    {
		$this->morphClass ='facility';
        return $this->morphMany('App\Model\Photo', 'imageable')->where('imageable_type','facility')->where('is_album_cover',1);
    }
	public function photo()
    {
		$this->morphClass ='form_gallery_facility';
        return $this->morphMany('App\Model\Photo', 'imageable')->where('imageable_type','form_gallery_facility')->where('is_album_cover',1);
    }
	public function facilitysports() {
        return $this->hasMany('App\Model\Facilitysports', 'facility_id', 'id');
    }
	
	public function searchResults($req_params)
	{
		/* $result = $this->limit(config('constants.LIMIT'))->search($search)->get();
		return $result; */
		
		if(trim($req_params['sport']) != ''){
			$result = $this->limit(config('constants.SEARCHLIMIT'))
						->search($req_params['search_by'])
						->join('facility_sports', 'facility_sports.facility_id', '=', 'facility_profile.id')				
						->whereIn('facility_sports.sports_id',explode(",",$req_params['sport']))
						->get();
			
		}else{
			$result = $this->limit(config('constants.SEARCHLIMIT'))
						->search($req_params['search_by'])						
						->get();
		}
		
		return $result;
		
	}
}


