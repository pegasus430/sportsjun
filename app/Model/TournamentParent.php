<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;

class TournamentParent extends Model {

    use SoftDeletes,
        Eloquence;

    protected $table = 'tournament_parent';
	
	protected $morphClass = 'tournaments';
	protected $dates = ['deleted_at'];
    protected $fillable = array('id', 'name', 'owner_id', 'created_by', 'contact_number', 'alternate_contact_number', 'logo', 'email', 'description', 'created_at', 'updated_at', 'deleted_at','manager_id');
    public function photos() {
        $this->morphClass = 'tournaments';
        return $this->morphMany('App\Model\Photo', 'imageable')->where('imageable_type', 'tournaments')->where('is_album_cover', 1);
    }
    public function photo() {
        $this->morphClass = 'form_gallery_tournaments';
        return $this->morphMany('App\Model\Photo', 'imageable')->where('imageable_type', 'form_gallery_tournaments')->where('is_album_cover', 1);
    }
 
}

