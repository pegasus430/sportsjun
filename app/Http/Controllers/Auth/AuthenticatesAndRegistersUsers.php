<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
//use Illuminate\Contracts\Auth\Guard;
//use Illuminate\Contracts\Auth\Registrar;
use App\Model\Photo;
use Session;
use Auth;
use App\User;

trait AuthenticatesAndRegistersUsers
{

        /**
         * The Guard implementation.
         *
         * @var Guard
         */
        protected $auth;

        /**
         * The registrar implementation.
         *
         * @var Registrar
         */
        protected $registrar;

        /**
         * Show the application registration form.
         *
         * @return \Illuminate\Http\Response
         */
        public function getRegister()
        {
                return view('auth.register');
        }

        /**
         * Handle a registration request for the application.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function postRegister(Request $request)
        {
                $this->validate($request, [
                        'firstname' => 'required|max:255',
                        'lastname'  => 'required|max:255', 'email'     => 'required|email|max:255|unique:users,email',
                        'password'  => 'required|confirmed|min:6', 'captcha'   => 'required|captcha'
                ]);

                //$request->verification_key = md5($request->email);
                //dd($request);
                $this->create($request->all());
                //$this->auth->login($this->create($request->all()));
                //return redirect($this->redirectPath());
                if ($request->ajax())
                {
                        return response()->json([
                                'status'   => 'success'
                        ]);
                }
                else
                {
                        return redirect('verifyemail');
                }
        }

        /**
         * Show the application login form.
         *
         * @return \Illuminate\Http\Response
         */
        public function getLogin()
        {
                return view('auth.login');
        }

        /**
         * Handle a login request to the application. Also put profile picture into session.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function postLogin(Request $request)
        {

                $this->validate($request, [
                        'email'    => 'required|email', 'password' => 'required',
                ]);

                $credentials                = $request->only('email', 'password');
                $credentials['is_verified'] = 1;

                //To check whether user is verified or not
                $userDetails = User::where('email', $request->email)->first();
                if (count($userDetails) > 0)
                {
                        if ($userDetails->is_verified == 0)
                        {
                                if ($this->auth->attempt($credentials, $request->has('remember')))
                                {
                                        $profilePhoto = Photo::where(['user_id' => Auth::user()->id,
                                                        'is_album_cover' => 1, 'imageable_type' => config('constants.PHOTO.USER_PHOTO')])->get(['url']);
                                        if (count($profilePhoto))
                                        {
                                                $profileUrl = array_collapse($profilePhoto->toArray());
                                                session(['profilepic' => $profileUrl['url']]);
                                        }
                                        
                                        if ($request->ajax())
                                        {
                                                return response()->json([
                                                        'status'   => 'email_not_verified',
                                                        'message'  => $this->getFailedVerifyMessage($request->email),
                                                        'intended' => \URL::previous()
                                                ]);
                                        }
                                        else
                                        {
                                                return redirect()->intended($this->redirectPath());
                                        }
                                }

                                if ($request->ajax())
                                {
                                        return response()->json([
                                                'status'   => 'invalid_credentials',
                                                'message'  => $this->getFailedVerifyMessage($request->email),
                                                'intended' => \URL::previous()
                                        ]);
                                }
                                else
                                {
                                        return redirect($this->loginPath())
                                                ->withInput($request->only('email', 'remember'))
                                                ->withErrors([
                                                        'email' => $this->getFailedVerifyMessage($request->email),
                                                ]);
                                }
                        }
                        elseif ($userDetails->isactive == 0)
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
                                        return redirect($this->loginPath())
                                                ->withInput($request->only('email', 'remember'))
                                                ->withErrors([
                                                        'email' => trans('message.login.accountdeactived', ['email' => $request->email]),
                                                ]);
                                }
                        }
                        else
                        {
                                if ($this->auth->attempt($credentials, $request->has('remember')))
                                {
                                        $profilePhoto = Photo::where(['user_id' => Auth::user()->id,
                                                        'is_album_cover' => 1, 'imageable_type' => config('constants.PHOTO.USER_PHOTO')])->get(['url']);
                                        if (count($profilePhoto))
                                        {
                                                $profileUrl = array_collapse($profilePhoto->toArray());
                                                session(['profilepic' => $profileUrl['url']]);
                                        }
                                        if ($request->ajax())
                                        {
                                                return response()->json([
                                                        'status'   => 'logged_in',
                                                        'intended' => \URL::previous()
                                                ]);
                                        }
                                        else
                                        {
                                                return redirect()->intended($this->redirectPath());
                                        }
                                        
                                }

                                if ($request->ajax())
                                {
                                        return response()->json([
                                                'status'   => 'login_failed',
                                                'message'  => $this->getFailedLoginMessage(),
                                                'intended' => \URL::previous()
                                        ]);
                                }
                                else
                                {
                                        return redirect($this->loginPath())
                                                        ->withInput($request->only('email', 'remember'))
                                                        ->withErrors([
                                                                'email' => $this->getFailedLoginMessage(),
                                        ]);
                                }
                        }
                }
                else
                {
                        if ($this->auth->attempt($credentials, $request->has('remember')))
                        {
                                $profilePhoto = Photo::where(['user_id' => Auth::user()->id,
                                                'is_album_cover' => 1, 'imageable_type' => config('constants.PHOTO.USER_PHOTO')])->get(['url']);
                                if (count($profilePhoto))
                                {
                                        $profileUrl = array_collapse($profilePhoto->toArray());
                                        session(['profilepic' => $profileUrl['url']]);
                                }
                                if ($request->ajax())
                                {
                                        return response()->json([
                                                'status'   => 'logged_in',
                                                'intended' => \URL::previous()
                                        ]);
                                }
                                else
                                {
                                        return redirect()->intended($this->redirectPath());
                                }
                        }


                        if ($request->ajax())
                        {
                                return response()->json([
                                        'status'   => 'login_failed',
                                        'message'  => $this->getFailedLoginMessage(),
                                        'intended' => \URL::previous()
                                ]);
                        }
                        else
                        {
                                return redirect($this->loginPath())
                                                ->withInput($request->only('email', 'remember'))
                                                ->withErrors([
                                                        'email' => $this->getFailedLoginMessage(),
                                ]);
                        }
                }

                //End
                //dd($userDetails);
        }

        /**
         * Get the failed login message.
         *
         * @return string
         */
        protected function getFailedLoginMessage()
        {
                return 'Please enter valid Username and Password.';
        }

        protected function getFailedVerifyMessage($email)
        {
                return 'Your email is not verified yet.<br />Click <a href="/resendverifyemail/' . $email . '">here</a> to resend the verification email.';
        }

        /**
         * Log the user out of the application.
         *
         * @return \Illuminate\Http\Response
         */
        public function getLogout()
        {
                $this->auth->logout();
                Session::flush();
                return redirect('/');
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

                return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
        }

        /**
         * Get the path to the login route.
         *
         * @return string
         */
        public function loginPath()
        {
                return property_exists($this, 'loginPath') ? $this->loginPath : '/auth/login';
        }

        /**
         * Create a new user instance after a valid registration.
         *
         * @param  array  $data
         * @return User
         */
        public function create(array $data)
        {
                //dd($data);
                $user = User::create([
                                'firstname'        => $data['firstname'],
                                'lastname'         => $data['lastname'],
                                'name'             => $data['lastname'] . ' ' . $data['firstname'],
                                'email'            => $data['email'],
                                'password'         => bcrypt($data['password']),
                                'newsletter'       => !empty($data['newsletter']) ? 1 : 0,
                                'verification_key' => md5($data['email'])
                ]);

                //Start send verification mail to user
                //--------------------------------------------------------------------------------------------------------------
                /* $data['verification_key']  = $user->verification_key;

                  Mail::send('emails.welcome', $data, function($message) use ($data)
                  {
                  $message->from('no-reply@site.com', "Site name");
                  $message->subject("Welcome to site name");
                  $message->to($data['email']);
                  }); */


                $verification_key = $user->verification_key;
                $to_email_id      = $data['email'];
                $user_name        = $data['firstname'];
                $subject          = 'Welcome to SportsJun';
                $view_data        = array('name' => $user_name, 'verification_key' => $verification_key);
                $view             = 'emails.welcome';
                $mail_data        = array('view' => $view, 'subject' => $subject,
                        'to_email_id' => $to_email_id, 'view_data' => $view_data,
                        'flag' => 'user', 'send_flag' => 1, 'verification_key' => $verification_key);
                if (SendMail::sendmail($mail_data))
                {
                        //return redirect()->back()->with('status', trans('message.contactus.emailsent'));
                        $response = array(
                                'status' => 'success',
                                'msg'    => 'user registered successfully',
                        );
                }
                //End

                return $user;
        }

        public function refereshCapcha()
        {
                return captcha_img('flat');
        }

}
