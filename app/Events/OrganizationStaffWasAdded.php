<?php

namespace App\Events;

use App\Model\Organization;
use App\User;
use Illuminate\Queue\SerializesModels;

class OrganizationStaffWasAdded extends Event
{
    use SerializesModels;

    /**
     * @var \App\Events\User|\App\User
     */
    public $user;

    /**
     * @var \App\Model\Organization
     */
    public $organization;

    /**
     * @var
     */
    public $password;

    /**
     * Create a new event instance.
     *
     * @param \App\User $user
     * @param \App\Model\Organization $organization
     * @param $password
     */
    public function __construct(User $user, Organization $organization, $password)
    {
        $this->user = $user;
        $this->organization = $organization;
        $this->password = $password;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
