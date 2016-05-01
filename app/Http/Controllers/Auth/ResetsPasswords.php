<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use DB;
use App\User;

trait ResetsPasswords
{

        /**
         * The Guard implementation.
         *
         * @var Guard
         */
        protected $auth;

        /**
         * The password broker implementation.
         *
         * @var PasswordBroker
         */
        protected $passwords;

        /**
         * Display the form to request a password reset link.
         *
         * @return Response
         */
        public function getEmail()
        {
                return view('auth.password');
        }

        /**
         * Send a reset link to the given user.
         *
         * @param  Request  $request
         * @return Response
         */
        public function postEmail(Request $request)
        {
                $this->validate($request, ['email' => 'required|email']);
                $userDetails = User::where('email', $request->email)->first();
                if (sizeof($userDetails) == 0)
                {
                        if ($request->ajax())
                        {
                                return response()->json([
                                                'status'   => 'account_does_not_exist',
                                                'message'  => trans('message.login.accountnotexist'),
                                                'intended' => \URL::previous()
                                ]);
                        }
                        else
                        {
                                return redirect()->back()->withErrors(['email' => trans('message.login.accountnotexist')]);
                        }
                }

                if ($userDetails->isactive == 0)
                {
                        if ($request->ajax())
                        {
                                return response()->json([
                                                'status'   => 'account_deactivated',
                                                'message'  => trans('message.login.accountdeactived', ['email' => $request->email]),
                                                'intended' => \URL::previous()
                                ]);
                        }
                        else
                        {
                                return redirect()->back()->withErrors(['email' => trans('message.login.accountdeactived', ['email' => $request->email])]);
                        }
                }

                $response = $this->passwords->sendResetLink($request->only('email'), function($m) {
                        $m->subject($this->getEmailSubject());
                });

                switch ($response)
                {
                        case PasswordBroker::RESET_LINK_SENT:
                                if ($request->ajax())
                                {
                                        return response()->json([
                                                        'status'   => 'reset_link_sent',
                                                        'message'  => trans($response),
                                                        'intended' => \URL::previous()
                                        ]);
                                }
                                else
                                {
                                        return redirect()->back()->with('status', trans($response));
                                }
                        case PasswordBroker::INVALID_USER:
                                if ($request->ajax())
                                {
                                        return response()->json([
                                                        'status'   => 'invalid_user',
                                                        'message'  => trans($response),
                                                        'intended' => \URL::previous()
                                        ]);
                                }
                                else
                                {
                                        return redirect()->back()->withErrors(['email' => trans($response)]);
                                }
                }
        }

        /**
         * Get the e-mail subject line to be used for the reset link email.
         *
         * @return string
         */
        protected function getEmailSubject()
        {
                return isset($this->subject) ? $this->subject : 'Your Password Reset Link';
        }

        /**
         * Display the password reset view for the given token.
         *
         * @param  string  $token
         * @return Response
         */
        public function getReset($token = null)
        {

                if (is_null($token))
                {
                        throw new NotFoundHttpException;
                }
                $results = DB::table('password_resets')
                        ->where('token', $token)
                        ->get();
                $email   = $results[0]->email;
                return view('auth.reset')->with(['token' => $token, 'email' => $email]);
        }

        /**
         * Reset the given user's password.
         *
         * @param  Request  $request
         * @return Response
         */
        public function postReset(Request $request)
        {

                $this->validate($request, [
                        'token'                 => 'required',
                        'email'                 => 'required|email',
                        'password'              => 'required|confirmed',
                        'password_confirmation' => 'required'
                ]);

                $credentials = $request->only(
                        'email', 'password', 'password_confirmation', 'token'
                );

                $response = $this->passwords->reset($credentials, function($user, $password) {
                        $user->password = bcrypt($password);

                        $user->save();

                        $this->auth->login($user);
                });

                switch ($response)
                {
                        case PasswordBroker::PASSWORD_RESET:
                                return redirect('/');
                        //return redirect()->intended($this->redirectPath());
                        //return redirect($this->redirectPath());

                        default:
                                return redirect()->back()
                                                ->withInput($request->only('email'))
                                                ->withErrors(['email' => trans($response)]);
                }
        }

        /**
         * Get the post register / login redirect path.
         *
         * @return string
         */
        public function redirectPath()
        {
                if (property_exists($this, 'redirectPath'))
                {
                        return $this->redirectPath;
                }

                return property_exists($this, 'redirectTo') ? $this->redirectTo : '/myschedule';
        }

}
