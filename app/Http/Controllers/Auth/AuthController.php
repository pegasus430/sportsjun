<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Helpers\Helper;
use App\Http\Requests\Auth\OrganizationRegisterRequest;
use App\Model\Organization;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Repository\StateRepository;
use App\User;
use Validator;
use JsValidator;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Auth;
use Socialite;
use Captcha;
use App\Helpers\SendMail;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

//    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
    use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|unique:users,email|email|max:255',
            'password' => 'required|confirmed|min:6',
            'captcha' => 'required'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        //dd($data);
        $user = User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'name' => $data['firstname'] . ' ' . $data['lastname'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'newsletter' => !empty($data['newsletter']) ? 1 : 0,
            'verification_key' => md5($data['email'])
        ]);


        //Start send verification mail to user
        //--------------------------------------------------------------------------------------------------------------
        $verification_key = $user->verification_key;
        $to_email_id = $data['email'];
        $to_user_id = $user->id;
        $user_name = $data['firstname'];
        $subject = 'Welcome to SportsJun';
        $view_data = array('name' => $user_name, 'verification_key' => $verification_key);
        $view = 'emails.welcome';
        $mail_data = array('view' => $view,
            'subject' => $subject,
            'to_email_id' => $to_email_id,
            'to_user_id' => $to_user_id,
            'view_data' => $view_data,
            'flag' => 'user',
            'send_flag' => 1,
            'verification_key' => $verification_key);

        if (SendMail::sendmail($mail_data)) {
            //return redirect()->back()->with('status', trans('message.contactus.emailsent'));
            $response = array(
                'status' => 'success',
                'msg' => 'user registered successfully',
            );
        }
        //End

        return $user;
    }

 
    public function postRegisterOrganization(OrganizationRegisterRequest $request)
    {
        $data = $request->all();

        $logo = Helper::uploadImageSimple($data['org_logo'], 'organization');
        $user = User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'name' => $data['firstname'] . ' ' . $data['lastname'],
            'email' => $data['email'],
            'contact_number' => array_get($data, 'mobile'),
            'password' => bcrypt($data['password']),
            'newsletter' => !empty($data['newsletter']) ? 1 : 0,
            'type'       => 1,
            'profile_updated '=>'1',
                    'address' => $data['address'],
                    'city_id' => $data['city_id'],
                    'city' => object_get(CityRepository::getModel($data['city_id']),'city_name'),
                    'state_id' => $data['state_id'],
                    'state' => object_get(StateRepository::getModel($data['state_id']), 'state_name'),
                    'country_id' => $data['country_id'],
                    'country' => object_get(CountryRepository::getModel($data['country_id']), 'country_name'),
                    'logo' => $logo,
            'verification_key' => md5($data['email']) //TODO:: these thing should be changed across all site
        ]);

        $user->profile_updated = 1;  //profile updated
        $user->save(); 

        if ($user) {
            \Event::fire(new UserRegistered($user));

            $organization = Organization::create([
                    'name' => $data['org_name'],
                    'organization_type' => $data['org_type'],
                    'email' => $data['email'],
                    'address' => $data['address'],
                    'city_id' => $data['city_id'],
                    'city' => object_get(CityRepository::getModel($data['city_id']),'city_name'),
                    'state_id' => $data['state_id'],
                    'state' => object_get(StateRepository::getModel($data['state_id']), 'state_name'),
                    'country_id' => $data['country_id'],
                    'country' => object_get(CountryRepository::getModel($data['country_id']), 'country_name'),
                    'logo' => $logo,
                    'about' => array_get($data,'org_about'),
                    'user_id' => $user->id,
                    'subdomain'=>array_get($data,'subdomain')
                ]
            );
            if ($organization) {
                #\Event::fire(new OrganizationCreated($organization));
                if (\Request::is()) {
                    return ['message' => 'Organization successfully registered.'];
                } else {
                    \Session::flash('message', 'Organization successfully registered.');
                    return redirect()->back();
                }
            } else {

                $user->delete();
                $error = 'Failed create organization';
            }
        } else {
            $error = 'Failed to create user';
        }

        if (\Request::ajax()) {
            return ['error' => $error];
        };

        \Session::flash('error', $error);
        return redirect()->back();
    }



    /*
    |--------------------------------------------------------------------------
    | JsValidation integration
    |--------------------------------------------------------------------------
    |
    | To integrate JsValidation package with default Auth Controller
    | override getLogin and getRegister methods implemented in 
    | AuthenticatesAndRegistersUsers trait to pass JsValidator instance to the view
    |
    */

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        $rules = array(
            'email' => 'required|email',
            'password' => 'required',
        );
        $validator = JsValidator::make($rules);

        return view('auth.login', ['validator' => $validator]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        $validator = JsValidator::validator(
            $this->validator([])
        );
        return view('auth.register', ['validator' => $validator]);
    }

    public function refereshCapcha()
    {

        return captcha_img('flat');
    }

    /*Start email verification email function*/
    public function verifyEmail()
    {
        return view('auth.verifyemail');
    }

    /* Check email confirmation*/
    public function confirmEmail($code)
    {
        $data = array();
        $userDetails = User::where('verification_key', $code)->first();
        $data['userDetails'] = $userDetails;
        if (count($userDetails) > 0) {
            if ($userDetails->is_verified == 0) {
                User::where('id', $userDetails->id)->update(['is_verified' => 1]);
            }
        }

        return view('auth.confirmemail', $data);
    }

    /* Resend verification email*/
    public function resendVerifyEmail($email)
    {
        $user = User::where(['email' => $email])->first();
        if (count($user) > 0) {
            if ($user->is_verified == 1) {
                return redirect('/');
            }

            //Start send verification mail to user
            //--------------------------------------------------------------------------------------------------------------
            $verification_key = $user->verification_key;
            $to_email_id = $user->email;
            $to_user_id = $user->id;
            $user_name = $user->firstname;
            $subject = 'Welcome to SportsJun';
            $view_data = array('name' => $user_name, 'verification_key' => $verification_key);
            $view = 'emails.welcome';
            $mail_data = array('view' => $view, 'subject' => $subject, 'to_email_id' => $to_email_id, 'to_user_id' => $to_user_id, 'view_data' => $view_data, 'flag' => 'user', 'send_flag' => 1, 'verification_key' => $verification_key);

            if (SendMail::sendmail($mail_data)) {
                //return redirect()->back()->with('status', trans('message.contactus.emailsent'));
                $response = array(
                    'status' => 'success',
                    'msg' => 'user registered successfully',
                );
            }
            //End
            return view('auth.verifyemail');
        } else {
            return view('auth.notexist');
        }
    }
}
