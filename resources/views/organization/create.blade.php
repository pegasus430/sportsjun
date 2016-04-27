
@extends('layouts.app')
@section('content')
<div class="container-fluid">
	<div class="sportsjun-forms sportsjun-container wrap-2">
<div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>{{ trans('message.organization.fields.heading') }}</h4></div>

			  @if( $roletype=='admin')
                    {!! Form::open(['route' => 'admin.organization.store','class'=>'form-horizontal','method' => 'POST','id' => 'organization-form']) !!}   
				 <div class="form-body">	
                         @include ('organization._form', ['submitButtonText' => 'Create'])
			    </div>	
			  <div class="form-footer">
                  <button type="submit" class="button btn-primary">Create</button>

                </div>			 
									 
                    {!! Form::close() !!}
					  {!! JsValidator::formRequest('App\Http\Requests\CreateOrganizatonRequest', '#organization-form'); !!}
			   @endif
			    @if( $roletype=='user')
			    {!! Form::open(['route' => 'organization.store','class'=>'form-horizontal','method' => 'POST','id' => 'organization-form']) !!}  
                  <div class="form-body">				
                         @include ('organization._form', ['submitButtonText' => 'Create'])
				 </div>		
									 									 
           <div class="form-footer">
              <button type="submit" class="button btn-primary">Create</button>

             </div>
                    {!! Form::close() !!}
					  {!! JsValidator::formRequest('App\Http\Requests\CreateOrganizatonRequest', '#organization-form'); !!}
					   @endif
</div>
</div>
@endsection
