<?php

namespace App\Http\Controllers\Api;

use App\Model\Organization;

class OrganizationApiController extends BaseApiController
{
    /**
     * Organization - list
     * @return array
     */

    public function index(){
        $organizations = Organization::
                    select([
                        'id',
                       'name','contact_number','alternate_contact_number',
                        'contact_name','organization_type',
                        'social_facebook','social_twitter','social_linkedin','social_googleplus',
                        'website_url', 'about', 'logo','location',
                        'address','city_id','city','state_id','state','country_id','country','zip'
                    ])->paginate(20);
        return self::ApiResponse($organizations);
    }

    public function show($id){
        $organization = Organization::
            select([
                'id',
                'name','contact_number','alternate_contact_number',
                'contact_name','organization_type',
                'social_facebook','social_twitter','social_linkedin','social_googleplus',
                'website_url', 'about', 'logo','location',
                'address','city_id','city','state_id','state','country_id','country','zip'
            ])->
            where('id',$id)
            ->with([
                'groups' =>function($query){
                    /** @var \Eloquent $query */
                    $query->select(['id','name','logo']);
                }
            ])
            ->first();
        return self::ApiResponse($organization);
    }





}
