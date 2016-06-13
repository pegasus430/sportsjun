<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrganizationStaff extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
}
