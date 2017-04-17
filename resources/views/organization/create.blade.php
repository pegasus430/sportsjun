@extends(Helper::check_if_org_template_enabled()?'layouts.organisation':'layouts.app') 
@section('content')
<div class="col-sm-3">
        <div class="row" id="create-tournament-instructions">
                <div class="intro_list_container">
                        <ul class="intro_list_on_empty_pages">
                                <span class="steps_to_follow">Steps to create organization:</span>
                                <li><span class="bold">Create</span> your organization.</li>
                                <li>If teams are already created, then add them to the organization.</li>
                                <li><span class="bold">Manage</span> all your teams easily.</li>
                        </ul>
                </div>
        </div>
</div>
<div class="container-fluid col-sm-6">
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
