<?php
namespace App\Http\Services;

use App\Model\Organization;
use Illuminate\Http\Request;

/**
 * Class OrganizationGroupService
 *
 * @author Sachin Agarwal <sachinkumaragarwal05@gmail.com>
 * @package App\Http\Services
 */
class OrganizationGroupService extends Service
{
    /**
     * @var string
     */
    private $uploadPath = 'uploads/org/groups/logo';

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Model\Organization $organization
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createGroup(Request $request, Organization $organization)
    {
        $file = $request->file('logo');

        $requestArray = $request->all();
        $requestArray['logo'] = $this->generateUniqueName();

        $group = $organization->groups()->create($requestArray);
        $request->file('logo')
                ->move(public_path($this->uploadPath), $group->logo);

        return $group;
    }



    /**
     * @return string
     */
    private function generateUniqueName()
    {
        return md5(microtime(true)) . '.png';
    }
}
