<?php
namespace App\Http\Services;

use App\Events\OrganizationStaffWasAdded;
use App\Model\Organization;
use App\User;
use Event;
use Illuminate\Http\Request;

class OrganizationStaffService extends Service
{
    /**
     * @var
     */
    private $password;

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Model\Organization $organization
     *
     * @return bool|\App\User
     */
    public function addStaff(Request $request, Organization $organization)
    {
        $email = $request->input('email');

        $user = User::whereEmail($email)->first();

        if (is_null($user)) {
            $user = $this->createUser($email);
        } else {
            $userExists =
                $organization->staff->contains(function ($key, $staff) use (
                    $user
                ) {
                    return $staff->id == $user->id;
                });

            if ($userExists) {
                return false;
            }
        }

        $organization->staff()->attach($user->id, [
            'organization_role_id' => $request->input('staff_role'),
            'status'               => false,
        ]);

        Event::fire(new OrganizationStaffWasAdded($user, $organization,
            $this->password));

        return $user;
    }

    /**
     * @param $email
     *
     * @return static
     */
    private function createUser($email)
    {
        $this->password = str_random(6);
        $encrypted = bcrypt($this->password);

        $user = User::create([
            'firstname'        => null,
            'lastname'         => null,
            'name'             => null,
            'email'            => $email,
            'password'         => $encrypted,
            'verification_key' => md5($email),
            'is_verified'      => 1,
        ]);

        return $user;
    }
}
