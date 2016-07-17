<?php

namespace App\Providers;

use App\Http\Composers\OrgLeftMenuComposer;
use Illuminate\Support\ServiceProvider;
use View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerOrganizationComposer();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function registerOrganizationComposer()
    {
        View::composer([
            'teams.orgleftmenu',
            'teams.orgteams',
            'organization.staff.list'
        ], OrgLeftMenuComposer::class);
    }
}
