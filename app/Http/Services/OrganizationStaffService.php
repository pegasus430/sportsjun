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
        $name = $request->input('name');
        $email = $request->input('email');

        if ($email) {
            $user = User::whereEmail($email)->first();
        } else {
            $user = User::whereName($name)->first();
        }

        if (is_null($user)) {
            if ($email)
                $user = $this->createUserByNameEmail($name,$email);
            else
                return User::$USER_EMAIL_REQUIRED;
        } else {
            $userExists =
                $organization->staff->contains(function ($key, $staff) use (
                    $user
                ) {
                    return $staff->id == $user->id;
                });

            if ($userExists) {
                return User::$USER_EXISTS;
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

    /**
     * @param $email
     *
     * @return static
     */
    private function createUserByNameEmail($name,$email)
    {
        $this->password = str_random(6);
        $encrypted = bcrypt($this->password);

        $user = User::create([
            'firstname'        => null,
            'lastname'         => null,
            'name'             => $name,
            'email'            => $email ? $email : null,
            'password'         => $encrypted,
            'verification_key' => md5($email),
            'is_verified'      => 1,
        ]);

        return $user;
    }

}
