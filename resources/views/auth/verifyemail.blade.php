@extends('layouts.login')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
        	<div style="width:50%; margin:25px auto; overflow:hidden;">
            <div style="background:#196c9f; min-height:100px; text-align:center; padding:20px;">            
            <img src="{{ URL::to('/') }}/images/mail_open.png" style="margin:0 auto;" width="100" height="100">
            </div>
            
            <div style="background:#fff;">
            
            	<p style="padding:20px; font-size:18px; font-weight:400;">Thanks for joining SportsJun! The activation link has been sent to your registered email. Please check your email and click on the link to activate your account. <a href="{{ url('/?open_popup=login') }}">Login</a></p>
            
            </div>
            
        </div>
            <!--<div class="panel panel-default">
                <div class="panel-heading">Verify Email</div>
                <div class="panel-body">
                   	Thanks for joining SportsJun! The activation link has been sent to your registered email. Please check your email and click on the link 
                        to activate your account.  <a href="{{ url('/auth/login') }}">Login</a>
                </div>
            </div>-->
        </div>
        

        
    </div>
</div>
@endsection
