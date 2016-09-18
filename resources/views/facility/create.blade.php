
@extends('layouts.app')
@section('content')
<div class="container-fluid">
<div class="sportsjun-wrap">
<div class="sportsjun-forms sportsjun-container wrap-2">
<div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>{{ trans('message.facility.fields.heading') }}</h4></div>
					 @if( $roletype=='admin')
                      {!! Form::open(['route' => 'admin.facility.store','class'=>'form-horizontal','method' => 'POST','id' => 'facility-form']) !!} 
	                <div class="form-body">				  
                         @include ('facility._form', ['submitButtonText' => 'Create'])
						 </div>		
					<div class="form-footer">
						 <button type="submit" class="button btn-primary"> Create </button>		
                       </div>
		
                    {!! Form::close() !!}
					  {!! JsValidator::formRequest('App\Http\Requests\CreateFacilityRequest', '#facility-form'); !!}
			      @endif
			      @if( $roletype=='user')
			      {!! Form::open(['route' => 'facility.store','class'=>'form-horizontal','method' => 'POST','id' => 'facility-form']) !!}   
                     <div class="form-body">			
					 @include ('facility._form', ['submitButtonText' => 'Create'])
					 </div>	
                    <div class="form-footer">
						 <button type="submit" class="button btn-primary"> Create </button>		
                       </div>					 
                    {!! Form::close() !!}
					  {!! JsValidator::formRequest('App\Http\Requests\CreateFacilityRequest', '#facility-form'); !!}
			     @endif
				 
</div>
</div>
</div>
@endsection
