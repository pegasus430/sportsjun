@extends('layouts.login')

@section('content')

<div class="container_login">
<div class="divone">
	<div class="form_div_new">
        <div class="login_form">
        
        
        
         <img src="{{ URL::to('/') }}/images/SportsJun_Logo.png" class="sportsjun_logo" />
        
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="verification_key" value="{{ csrf_token() }}">
<div class="form_div">
                      <!--  <div class="form-group">
                            <div class="col-md-12">
                            <label>{{ trans('message.auth.fields.registartionname') }}</label>
                                <input type="text" class="form-control input_bg" name="name" value="{{ old('name') }}" placeholder="{{ trans('message.auth.fields.nameplaceholder') }}">
                                @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                            </div>
                        </div>-->
					    <div class="form-group">
                            <div class="col-md-12">
                            <label>{{ trans('message.auth.fields.registartionfirstname') }}&nbsp;<span>*</span></label>
                                <input type="text" class="form-control input_bg" name="firstname" value="{{ old('firstname') }}" placeholder="{{ trans('message.auth.fields.firstnameplaceholder') }}">
                                @if ($errors->has('firstname')) <p class="help-block">{{ $errors->first('firstname') }}</p> @endif
                            </div>
                        </div>
					     <div class="form-group">
                            <div class="col-md-12">
                            <label>{{ trans('message.auth.fields.registartionlastname') }}&nbsp;<span>*</span></label>
                                <input type="text" class="form-control input_bg" name="lastname" value="{{ old('lastname') }}" placeholder="{{ trans('message.auth.fields.lastnameplaceholder') }}">
                                @if ($errors->has('lastname')) <p class="help-block">{{ $errors->first('lastname') }}</p> @endif
                            </div>
                        </div>
						

                        <div class="form-group">
                            <div class="col-md-12">
                            <label>{{ trans('message.auth.fields.registartionemail') }}&nbsp;<span>*</span></label>
                                <input type="email" class="form-control input_bg" name="email" value="{{ old('email') }}" placeholder="{{ trans('message.auth.fields.emailplaceholder') }}">
                                @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                            							<label>{{ trans('message.auth.fields.password') }}&nbsp;<span>*</span></label>
                                <input type="password" class="form-control input_bg" name="password" placeholder="{{ trans('message.auth.fields.passwordplaceholder') }}">
                                @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
							<label>{{ trans('message.auth.fields.confirmpassword') }}</label>
                                <input type="password" class="form-control input_bg" name="password_confirmation" placeholder="Confirm Password">
                                @if ($errors->has('password_confirmation')) <p class="help-block">{{ $errors->first('password_confirmation') }}</p> @endif
                            </div>
                        </div>
    
                       <div class="form-group">
                            <div class="col-md-12">
                               <div style="position:relative;">
                               <span id="capcha"> {!!Captcha::img()!!}</span>
                                <input type="text" name="captcha" class="captcha-input">
                                
								<a href="javascript:void(0)" onclick="refreshCaptcha()" class="signup_capthca"> <img src="{{ asset('/images/refresh.png') }}" alt="" /></a>
                                </div>
                                <div style="clear:both;">
                                @if ($errors->has('captcha')) <p class="help-block">{{ $errors->first('captcha') }}</p> @endif
                                </div>
                            </div>
                        </div>
						  
                    
						

                        <div class="form-group">
                            <div class="col-md-12">
                                
                                <input type="checkbox" name="accept_terms"  id="accept_terms" checked="checked" value="{{ old('accept_terms') }}">
                               <label style="display:inline;">&nbsp;{{ trans('message.auth.fields.terms') }}</label>
                                @if ($errors->has('accept_terms')) <p class="help-block">{{ $errors->first('accept_terms') }}</p> @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                {!! Form::checkbox('newsletter', 1,true, array('class'=>'')) !!}
                                <label style="display:inline;">&nbsp;{{ trans('message.auth.fields.bulletinalert') }}</label>
                                @if ($errors->has('newsletter')) <p class="help-block">{{ $errors->first('newsletter') }}</p> @endif
                            </div>
                        </div>

                        <div id="submitbuttondiv">
                            <div class="form-group" style="margin:0;">
                                <div class="col-md-12">
                                    <button type="submit" class="btn sign_in_sports">
                                        Register
                                    </button>
                                    
                                    <a href="{{ url('/auth/login') }}" class="forgot_link pull-right">&larr; Back to login</a>
                                </div>
                            </div>
                        </div>     
                        </div>
                    </form>
        {!! $validator !!} 
        </div>
    	<hr color="#b2ebba" size="1"/>
        <div class="social-buttons reg-social">
                <!--<div><h2>Sign with Social Accounts</h2></div>-->
                <div class="col-md-6 social_but"><a class="btn btn-block btn-social btn-twitter" href="{{ route('social.login', ['twitter']) }}"><span class="fa fa-twitter"></span> Twitter</a></div>
                <div class="col-md-6 social_but"><a class="btn btn-block btn-social btn-facebook" href="{{ route('social.login', ['facebook']) }}"><span class="fa fa-facebook"></span> Facebook</a></div>
                <div class="col-md-6 social_but"><a class="btn btn-block btn-social btn-google" href="{{ route('social.login', ['google']) }}"><span class="fa fa-google-plus"></span> Google</a></div>
                <div class="col-md-6 social_but"><a class="btn btn-block btn-social btn-linkedin" href="{{ route('social.login', ['linkedin']) }}"><span class="fa fa-linkedin"></span> LinkedIn</a></div>

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

<!--<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
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

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                            </div>
                        </div>

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
                            <label class="col-md-4 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation">
                                @if ($errors->has('password_confirmation')) <p class="help-block">{{ $errors->first('password_confirmation') }}</p> @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">I agree to the terms and conditions of this site.</label>
                            <div class="col-md-6">
                                <input type="hidden" name="accept_terms" value="0">
                                <input type="checkbox" class="form-control" name="accept_terms" onclick="showRegisterButton()" id="accept_terms" checked="checked" value="{{ old('accept_terms') }}" >
                                @if ($errors->has('accept_terms')) <p class="help-block">{{ $errors->first('accept_terms') }}</p> @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Send me the weekly bulletin alert.</label>
                            <div class="col-md-6">
                                {!! Form::checkbox('newsletter', 1,true, array('class'=>'form-control')) !!}
                                @if ($errors->has('newsletter')) <p class="help-block">{{ $errors->first('newsletter') }}</p> @endif
                            </div>
                        </div>

                        <div id="submitbuttondiv">
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="button btn-primary">
                                        Register
                                    </button>
                                </div>
                            </div>
                        </div>     
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>-->

<script type="text/javascript">
    $(document).ready(function() {
        jQuery(document.body).on("ifToggled", "#accept_terms", function(event) {
            if(event.currentTarget.checked) {
                $("#accept_terms").val(1);
//                $("#submitbuttondiv").html(" <div class='form-group' style='margin:0;'><div class='col-md-12'><button type='submit' class='btn sign_in_sports'>Register</button><a href='{{ url('/auth/login') }}' class='forgot_link pull-right'>&larr; Back to login</a></div></div>");
                  $('.sign_in_sports').prop('disabled', false);
                  $('.sign_in_sports').css("background-color", "#2f3c4d");
            }else{
                $('.sign_in_sports').prop('disabled', true);
                $('.sign_in_sports').css("background-color", "#808080");
//                $("#submitbuttondiv").html('');
            }
            
        });
        /*$("#accept_terms").on("ifToggled", function(event){
            if(event.currentTarget.checked) {
                $("#accept_terms").val(1);
                $("#submitbuttondiv").html(" <div class='form-group' style='margin:0;'><div class='col-md-12'><button type='submit' class='btn sign_in_sports'>Register</button><a href='{{ url('/auth/login') }}' class='forgot_link pull-right'>&larr; Back to login</a></div></div>");
            }else{
                $("#submitbuttondiv").html('');
            }   
        });*/
        
        
    });
    
    function showRegisterButton() {
        if ($('#accept_terms').is(":checked"))
        {
            $("#submitbuttondiv").html(" <div class='form-group' style='margin:0;'><div class='col-md-12'><button type='submit' class='btn sign_in_sports'>Register</button></div></div>");
        }
        else
        {
            $("#submitbuttondiv").html('');
        }
    }

            var base_url = "{{URL::to('/')}}";
            var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
            var global_record_count = 0;
        
function refreshCaptcha(){
	   
$.ajax({

 url: "{{ url('/refereshcapcha') }}",
 type: 'get',
//dataType: 'html',   
  //data: {'_token': token }, 
  success: function(json) {
    $('#capcha').html(json);
  },
  error: function(data) {
    alert('Try Again.');
  }
});
}
</script>
@endsection
