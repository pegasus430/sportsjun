@extends('layouts.app')

@section('content')


<div class="container-fluid">
	<div class="sportsjun-forms sportsjun-container wrap-2">
		 <div class="form-header header-primary">
         
         	<h4><i class="fa fa-pencil-square"></i>{{ trans('message.organization.fields.editheading') }}</h4>
         
         </div>				
				
				
				@if( $roletype=='admin')
                    {!! Form::model($organization,(array('route' => array('admin.organization.update',$id),'class'=>'form-horizontal','method' => 'put','id' => 'organization-form'))) !!}   
				  <div class="form-body">
                        @include ('organization._form', ['submitButtonText' => 'Update'])
				  </div>
				 <div class="form-footer">
                  <button type="submit" class="button btn-primary">Update</button>

                </div>			
                    {!! Form::close() !!}
				{!! JsValidator::formRequest('App\Http\Requests\CreateOrganizatonRequest', '#organization-form'); !!}
			    @endif
				@if( $roletype=='user')
				 {!! Form::model($organization,(array('route' => array('organization.update',$id),'class'=>'form-horizontal','method' => 'put','id' => 'organization-form'))) !!}
             <div class="form-body">			 
                        @include ('organization._form', ['submitButtonText' => 'Update'])
						</div>
						 <div class="form-footer">
                  <button type="submit" class="button btn-primary">Update</button>

                </div>		
                    {!! Form::close() !!}
				{!! JsValidator::formRequest('App\Http\Requests\CreateOrganizatonRequest', '#organization-form'); !!}
				@endif	
</div>
</div>	
@endsection
