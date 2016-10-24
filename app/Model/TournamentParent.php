<?php

namespace App\Model;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;

class TournamentParent extends Model
{

    use SoftDeletes,
        Eloquence;

    protected $table = 'tournament_parent';

    protected $morphClass = 'tournaments';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'name',
        'owner_id',
        'created_by',
        'contact_number',
        'alternate_contact_number',
        'logo',
        'email',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
        'manager_id',
        'organization_id',
    ];

    public function photos()
    {
        $this->morphClass = 'tournaments';

        return $this->morphMany('App\Model\Photo', 'imageable')
                    ->where('imageable_type', 'tournaments')
                    ->where('is_album_cover', 1);
    }

    public function photo()
    {
        $this->morphClass = 'form_gallery_tournaments';

        return $this->morphMany('App\Model\Photo', 'imageable')
                    ->where('imageable_type', 'form_gallery_tournaments')
                    ->where('is_album_cover', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function tournaments()
    {
        return $this->hasMany('App\Model\Tournaments', 'tournament_parent_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orgGroups()
    {
        return $this->belongsToMany(OrganizationGroup::class,
            'tournament_org_groups', 'tournament_parent_id',
            'organization_group_id');
    }

      function getGroupPoints(){
            return $this->hasMany('App\Model\OrganizationGroupTeamPoint')->groupBy('sports_id');
    }


    function getEachGroupPoints($tournament_parent_id,$organization_group_id,$sports_id){

          $points=OrganizationGroupTeamPoint::whereTournamentParentId($tournament_parent_id)->whereOrganizationGroupId($organization_group_id)->whereSportsId($sports_id)->first();
            if(empty($points)){
                $points=0;
            }
            else{
                $points=$points->points;
            }
            return $points;
    }

    public function getLogoImageAttribute()
    {
        return Helper::getImagePath($this->logo, 'tournaments');
    }
}

