@extends('layouts.login')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Confirm Email</div>
				@if(count($userDetails)>0)
					@if($userDetails->is_verified == 0)
					<div class="panel-body">
                   	Hey, Welcome to SportsJun. Your  account verified successfully.
					Please click <a href="{!! url('auth/login') !!}"> here</a> to login.
					</div>
					@endif
					@if($userDetails->is_verified == 1)
					<div class="panel-body">
                   	Hey, your account is already verified.
					Please click <a href="{!! url('auth/login') !!}"> here</a> to login.
					</div>
					@endif
				@else
					<div class="panel-body">
                   	Hey, to confirm your email please register and getback.
					Please click <a href="{!! url('auth/register') !!}"> here</a> to register.
					</div>
				@endif
               
            </div>
        </div>
    </div>
</div>
@endsection
