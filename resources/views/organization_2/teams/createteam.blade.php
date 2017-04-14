@extends(Helper::check_if_org_template_enabled()?'layouts.organisation':'layouts.app') 
@section('content')
<div class="col-sm-3">
        <div class="row" id="create-tournament-instructions">
                <div class="intro_list_container">
                        <ul class="intro_list_on_empty_pages">
                                <span class="steps_to_follow">Steps to create team:</span>
                                <li><span class="bold">Fill</span> the create team form.</li>
                                <li>Click <span class="bold">create.</span></li>
                                <li><span class="bold">Add players</span> using Add Player / Invite Player wizard.</li>
                                <li>Make a player the <span class="bold">Manager / Captain</span> of the team using <span class="glyphicon glyphicon-option-vertical"></span> button.</li>
                        </ul>
                </div>
        </div>
</div>
<div class="container-fluid col-sm-6">
	<div class="sportsjun-forms sportsjun-container wrap-2">
		 <div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>{{ trans('message.team.fields.heading') }}</h4></div><!-- end .form-header section -->
		 {!! Form::open(['route' => 'team.store','class'=>'form-horizontal','method' => 'POST','files'=>true,'id' => 'team-form']) !!}
		<div class="form-body">
				@include ('organization_2.teams._form', ['submitButtonText' => 'Create'])
		</div>
		<div class="form-footer">
		  <button type="submit" class="button btn-primary"> Create </button>
		</div>
			  {!! Form::close() !!}
			   {!! JsValidator::formRequest('App\Http\Requests\CreateTeamRequest', '#team-form'); !!}
		</div>
</div>

    @if($organization_id)
        <script>
            $(function () {
                $('#organization_id').val('{{ $organization_id }}');
                displayOrgGroups('{{ $organization_id }}');
            });
        </script>
    @endif
@endsection
