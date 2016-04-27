@extends('layouts.app')

@section('content')

<div class="container-fluid">
	<div class="sportsjun-forms sportsjun-container wrap-2">
		 <div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>{{ trans('message.team.fields.editheading') }}</h4></div><!-- end .form-header section -->   
		 {!! Form::model($teamdetails,(array('route' => array('team/update',$id),'class'=>'form-horizontal','method' => 'post','id' => 'team-form'))) !!}   
		<div class="form-body">
				@include ('teams._form', ['submitButtonText' => 'Update'])
		</div>	
	  <div class="form-footer">
		  <button type="submit" class="button btn-primary"> Update </button>		
		</div>		
        		
			{!! Form::close() !!}
			 {!! JsValidator::formRequest('App\Http\Requests\CreateTeamRequest', '#team-form'); !!}
		</div>
</div>		
@endsection
