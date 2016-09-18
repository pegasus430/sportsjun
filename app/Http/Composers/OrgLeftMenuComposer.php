<?php
namespace App\Http\Composers;

use App\Model\Organization;
use Illuminate\Contracts\View\View;
use Request;

class OrgLeftMenuComposer implements Composer
{
    
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $organization = Organization::find(Request::route('id'));

        $view->with('organization', $organization);
    }
}
