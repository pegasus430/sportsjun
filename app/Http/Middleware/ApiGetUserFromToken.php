<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Middleware;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Routing\ResponseFactory;

use Tymon\JWTAuth\JWTAuth;


class ApiGetUserFromToken
{
    /**
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $response;

    /**
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $auth;

    /**
     * Create a new BaseMiddleware instance.
     *
     * @param \Illuminate\Contracts\Routing\ResponseFactory  $response
     * @param \Illuminate\Contracts\Events\Dispatcher  $events
     * @param \Tymon\JWTAuth\JWTAuth  $auth
     */
    public function __construct(ResponseFactory $response, Dispatcher $events, JWTAuth $auth)
    {
        $this->response = $response;
        $this->events = $events;
        $this->auth = $auth;
    }

    protected function respond($event, $data, $status, $payload = [])
    {
        $response = $this->events->fire($event, $payload, true);

        return $response ?: $this->response->json(['status'=>'failure','code'=>$status,'response'=>['error'=>$data]]);
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $token = $request->get('token');
        if ($token){
            $this->auth->setRequest($request)->setToken($token);
        }
        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return $this->respond('tymon.jwt.absent', 'token_not_provided', 400);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            return $this->respond('tymon.jwt.expired', 'token_expired', $e->getStatusCode(), [$e]);
        } catch (JWTException $e) {
            return $this->respond('tymon.jwt.invalid', 'token_invalid', $e->getStatusCode(), [$e]);
        }

        if (! $user) {
            return $this->respond('tymon.jwt.user_not_found', 'user_not_found', 404);
        }

        $this->events->fire('tymon.jwt.valid', $user);

        return $next($request);
    }
}
