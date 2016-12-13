<?php

namespace App\Events;

use App\Events\Event;
use App\Model\Team;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TeamOwnershipChanged extends Event
{
    use SerializesModels;
    public $user;
    public $team;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user,Team $team)
    {
        $this->user = $user;
        $this->team = $team;
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
