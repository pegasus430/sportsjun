<?php

namespace App\Http\Middleware;

use App\Model\Organization;
use Closure;


class SubdomainMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        $subdomain = $request->subdomain;
        $organization = Organization
            ::where('subdomain',$subdomain)
            ->first();
        if(!$organization)
            \App::abort(404);
        $request->merge(['organization_id' => $organization->id]);
        return $next($request);
    }
}
