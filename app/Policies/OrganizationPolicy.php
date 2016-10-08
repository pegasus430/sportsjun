<?php

namespace App\Policies;

use App\Model\Organization;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\User $user
     * @param \App\Model\Organization $organization
     *
     * @return bool
     */
    public function createStaff(User $user, Organization $organization)
    {
        return $organization->user->id == $user->id;
    }

    /**
     * @param \App\User $user
     * @param \App\Model\Organization $organization
     *
     * @return bool
     */
    public function createGroup(User $user, Organization $organization)
    {
        return $organization->user->id == $user->id;
    }

    /**
     * @param \App\User $user
     * @param \App\Model\Organization $organization
     *
     * @return bool
     */
    public function createTeam(User $user, Organization $organization)
    {
        return $organization->user->id == $user->id;
    }

    /**
     * @param \App\User $user
     * @param \App\Model\Organization $organization
     *
     * @return bool
     */
    public function createTournament(User $user, Organization $organization)
    {
        return $organization->user->id == $user->id;
    }
}
