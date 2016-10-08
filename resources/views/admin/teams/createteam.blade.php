
@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">

<div class="sportsjun-wrap">
    	<div class="sportsjun-forms sportsjun-container wrap-2">
		 <div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>{{ trans('message.team.fields.heading') }}</h4></div><!-- end .form-header section -->   
		 {!! Form::open(['route' => 'admin.team.store','class'=>'form-horizontal','method' => 'POST','files'=>true,'id' => 'team-form']) !!}    
		<div class="form-body">
				@include ('admin.teams._form', ['submitButtonText' => 'Create'])
		</div>		
		<div class="form-footer">
		  <button type="submit" class="button btn-primary"> Create </button>		
		</div>		
			  {!! Form::close() !!}
			   {!! JsValidator::formRequest('App\Http\Requests\CreateTeamRequest', '#team-form'); !!}
		</div>
</div>
</div>		
@endsection
