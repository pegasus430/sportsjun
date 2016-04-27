<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;

class Organization extends Model {

	use SoftDeletes,
		Eloquence;
	protected $table = 'organization';
	protected $dates = ['deleted_at'];
	protected $searchableColumns = ['name', 'email','city'];
	protected $fillable = array('user_id','name','contact_number','email','organization_type','social_facebook','social_twitter','social_linkedin','social_googleplus','website_url','about','location','longitude',
'latitude','address','city_id','city' ,'state_id','state','country_id','country','zip','alternate_contact_number','contact_name','logo');
  protected $morphClass = 'organization';
    public function photos()
    {   
	    $this->morphClass ='organization';
        return $this->morphMany('App\Model\Photo', 'imageable')->where('imageable_type','organization')->where('is_album_cover',1);
    }
      public function photo()
    {
		$this->morphClass ='form_gallery_organization';
        return $this->morphMany('App\Model\Photo', 'imageable')->where('imageable_type','form_gallery_organization')->where('is_album_cover',1);
    }
	public function user()
	{
		return $this->hasOne('App\User','id','user_id');
	}
	public function teamplayers()
    {
        return $this->hasMany('App\Model\team','organization_id','id');
    }
}


