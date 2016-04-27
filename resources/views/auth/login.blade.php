@extends('layouts.login')
@section('content')
<div class="container_login">
    <div class="divone">
        <div class="form_div_new">
            <div class="login_form">
               <img src="{{ URL::to('/') }}/images/SportsJun_Logo.png" class="sportsjun_logo" />
<!--
                <h2>Sign into your SportsJun account</h2>
                <form accept-charset="UTF-8" role="form" action="dashboard.html">
                      <div class="form_div">
                          <fieldset>
                              <div class="form-group">
                                  <input class="form-control" placeholder="Username" name="email" type="text">
                              </div>
                              <div class="form-group">
                                  <input class="form-control" placeholder="Password" name="password" type="password" value="">
                              </div>
                              <div>
                                  <input class="btn btn-lg btn-success btn-block" type="submit" value="Sign In" >
                              </div>
                          </fieldset>
                      </div>
                </form>
-->
                {{-- @if (count($errors) > 0) --}}
                <!--<div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>-->
                {{-- @endif --}}
                
                <form class="form-horizontal input_bg" role="form" method="POST" action="{{ url('/auth/login') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form_div">
                        <div class="form-group">
                            <!--<label class="col-md-4 control-label">E-Mail Address</label>-->
                            <div>
                                <input type="email" class="form-control input_bg" name="email" value="{{ old('email') }}" placeholder="Enter Email">
                                @if ($errors->has('email')) <p class="help-block">{!! $errors->first('email') !!}</p> @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <!--<label class="col-md-4 control-label">Password</label>-->
                            <div>
                                <input type="password" class="form-control input_bg" name="password" placeholder="Enter Password">
                                @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
                            </div>
                        </div>

                        <div class="form-group checkbox_div">
                            <div>
                                <div class="checkbox">
                                    <!--<label><input type="checkbox" name="remember"> Remember Me </label>-->
                                    <a class="forgot_link" href="{{ url('/password/email') }}">Forgot Your Password?</a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group actions_logindiv">
                            <div>
                                <button type="submit" class="btn sign_in_sports">Sign In</button>
                                <div class="signup_txt">New to SportsJun? <a class="signup_link" href="{{ url('/auth/register') }}">Sign up</a></div>
                                

                            </div>
                        </div>
                        
                        <hr color="#b2ebba" size="1"/>
                        
                        <div class="social-buttons clearfix">
                            @if($errors->has('socialloginerror'))
                            <div class="alert alert-danger">
                                {{$errors->first('socialloginerror')}}
                            </div>    
                            @endif
                        	<div class="row">
                                <!--<div><h2>Sign with Social Accounts</h2></div>-->
                                <div class="col-md-6 social_but">
                                    <a class="btn btn-block btn-social btn-twitter" href="{{ route('social.login', ['twitter']) }}">
                                        <span class="fa fa-twitter"></span> Twitter
                                    </a>
                                </div>
                                <div class="col-md-6 social_but">
                                   <a class="btn btn-block btn-social btn-facebook" href="{{ route('social.login', ['facebook']) }}">
                                       <span class="fa fa-facebook"></span> Facebook
                                   </a>
                                </div>
                                <div class="col-md-6 social_but">
                                   <a class="btn btn-block btn-social btn-google" href="{{ route('social.login', ['google']) }}">
                                       <span class="fa fa-google-plus"></span> Google
                                   </a>
                                </div>
                                <div class="col-md-6 social_but">
                                    <a class="btn btn-block btn-social btn-linkedin" href="{{ route('social.login', ['linkedin']) }}">
                                        <span class="fa fa-linkedin"></span> LinkedIn
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        </div>
                </form>
                {!! $validator !!}                        
                <!--<p class="copy_txt">SportsJun Powered by Sagarsoft - Copyright @ 2015-2016,<br> SportsJun. All Rights Reserved.</p>-->
            </div>
        </div>
    </div>
    
    <div class="divtwo">
        <div class="content_div_new">
            <div class="right_content_div">
                <p class="sports_head_login">Your integrated <span>sports</span> data <span>management</span> community</p>
                <p class="sports_txt">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
            </div>
        </div>
    </div>
</div>

<!--<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    {{-- @if (count($errors) > 0) --}}
                   						<div class="alert alert-danger">
                                                                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                                                            <ul>
                                                                                    @foreach ($errors->all() as $error)
                                                                                            <li>{{ $error }}</li>
                                                                                    @endforeach
                                                                            </ul>
                                                                    </div>
                    {{-- @endif --}}

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">E-Mail Address</label>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Password</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">
                                @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="button btn-primary">Login</button>

                                <a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your Password?</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <h2>Login Using Social Sites</h2>
                                <a class="button btn-primary" href="{{ route('social.login', ['twitter']) }}">Twitter</a>
                                <a class="button btn-primary" href="{{ route('social.login', ['facebook']) }}">Facebook</a>
                                <a class="button btn-primary" href="{{ route('social.login', ['google']) }}">Google</a>
                                <a class="button btn-primary" href="{{ route('social.login', ['linkedin']) }}">LinkedIn</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>-->
@endsection
