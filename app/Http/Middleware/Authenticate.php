<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Log;
use Route;
use Helper;

class Authenticate
{
        /**
         * The Guard implementation.
         *
         * @var Guard
         */
        protected $auth;

        /**
         * Create a new filter instance.
         *
         * @param  Guard  $auth
         * @return void
         */
        public function __construct(Guard $auth)
        {
                $this->auth = $auth;
        }

        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @return mixed
         */
        public function handle($request, Closure $next)
        {
                if ($this->auth->guest())
                {
                        if ($request->ajax())
                        {
                                return response('Unauthorized', 401);
                        }
                        else
                        {
                                // return redirect()->guest('auth/login');
                                $controller=  new \App\Http\Controllers\HomeController();
                                return $controller->index();
                        }
                }
                else
                {
                        // dd(Route::getCurrentRoute()->getPath());
                        $allowedRoutesBeforeProfileUpdate = ['user/{user}/edit',
                                'user/{user}', 'getcities','getstates', 'tempphoto/store'];
                        
                        if ($this->auth->user()->profile_updated == 0 && !in_array(Route::getCurrentRoute()->getPath(), $allowedRoutesBeforeProfileUpdate))
                        {
                                return redirect(route('user.edit', [$this->auth->user()->id]));
                        }

                        $allowedRoutesBeforeSportsProfileUpdate = ['showsportprofile/{userId}',
                                'editsportprofile/{userId}',
                                'schedule/getstates', 'sport/getsports', 'getquestions', 'user/set-sports',
                                'sport/{sport}',
                                'sport/updateUserStats',
                                'tournaments/registerstep3/{id}/{event_id}',
                                'tournaments/registrationstep5',
                                'tournaments/paymentform/{id}',
                                'tournaments/paymentform',
                                'tournaments/payment_success',
                                'tournaments/payment_failure',
                                'tournaments/registerstep3/{id}',
                                'tournaments/registerstep3/{id}/{event_id}',
                                'getcities',
                                'getstates',
                                'smite/save_nickname'
                                ];

                        $followingSports = Helper::getFollowingSportIds($this->auth->user()->id);
                        
                        if ($this->auth->user()->profile_updated == 1 && $this->auth->user()->type!=1)
                        {
                                if (empty($followingSports) && !in_array(Route::getCurrentRoute()->getPath(), $allowedRoutesBeforeSportsProfileUpdate))
                                {
                                        return redirect(url('/showsportprofile', [$this->auth->user()->id]));
                                }
                        }
                }
                return $next($request);
        }
}
