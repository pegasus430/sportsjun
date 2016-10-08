
@extends('admin.layouts.app')
@section('content')

<div class="container-fluid">
<div class="sportsjun-wrap">
<div class="sportsjun-forms sportsjun-container wrap-2">
<div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>{{ trans('message.tournament.fields.heading') }}</h4></div><!-- end .form-header section -->
       
                    {!! Form::open(['route' => 'admin.tournaments.store','class'=>'form-horizontal','method' => 'POST','id' => 'my-tournaments']) !!}   
				<div class="form-body">
                         @include ('tournaments._form', ['submitButtonText' => 'Create'])
				</div>		
                 <div class="form-footer">
                  <button type="submit" class="button btn-primary">Create</button>

                </div>				
                    {!! Form::close() !!}
					{!! JsValidator::formRequest('App\Http\Requests\CreateTournamentRequest', '#my-tournaments'); !!}
			 
			    
				 
</div>
</div>
</div>
@endsection
