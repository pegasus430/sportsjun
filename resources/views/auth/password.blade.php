@extends('layouts.login')

@section('content')
<div class="container-fluid">
    <div class="row">
    	<div class="col-md-12">
        	<div class="login-box">
            	<h3><center>Reset Password</center></h3>
                <hr>
                @if (session('status'))
                <div class="alert alert-success">
                	{{ session('status') }}
                </div>
                @endif
                
                {{-- @if (count($errors) > 0) --}}
                <!--<div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.
                    <br>
                    <br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>-->   
				{{-- @endif --}}  
                <form class="form-horizontal form_div" role="form" method="POST" action="{{ url('/password/email') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4">E-Mail Address</label>
                            <div class="col-md-8">
                                <input type="email" class="form-control input_bg" name="email" value="{{ old('email') }}">
                                @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                                @if($errors->has('email') && $errors->first('email') == trans('message.login.accountnotexist')) <a class="signup_link signup_link_new" href="{{ url('/auth/register') }}">Sign up</a> @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn sign_in_sports">Send Password Reset Link</button>
                            </div>
                        </div>
                    </form>       
            </div>
        </div>
    </div>
</div>
@endsection
