@extends('layouts.app')

@section('content')				
<div class="container-fluid">
<div class="sportsjun-wrap">
<div class="sportsjun-forms sportsjun-container wrap-2">
<div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>{{ trans('message.contactus.fields.heading') }}</h4></div>				
				
		
			{!! Form::open(['route' => 'contact.store','class'=>'form-horizontal','method' => 'POST','id' => 'my-form']) !!}   
			    <div class="form-body">		
				@include ('contactus._form', ['submitButtonText' => 'Create'])
				</div>
				<div class="form-footer">

								<button type="submit" class="button btn-primary">   Send your Message
								</button>

				</div>
						 
			{!! Form::close() !!}
			  {!! JsValidator::formRequest('App\Http\Requests\contactusRequest', '#my-form'); !!}
</div>
</div>
</div>
@endsection
