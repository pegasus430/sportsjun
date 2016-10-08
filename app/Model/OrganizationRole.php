<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class OrganizationRole extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organization_roles';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /** Relations */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function staff()
    {
        return $this->belongsToMany(User::class, 'organization_staffs',
            'organization_role_id', 'user_id');
    }
}
