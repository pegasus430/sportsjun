@extends('layouts.app')
@section('content')
@include ('teams.orgleftmenu')

<div id="content" class="col-sm-10 tournament_profile">
	<div class="col-md-6">
    	<div class="group_no clearfix">
        	<h4 class="stage_head">{{ trans('message.organization.fields.orgdetails') }}</h4>
        </div>
        <table class="table table-striped table-bordered">
				<tbody>
						<tr>
							<th>{{   trans('message.organization.fields.name') }}</th>
							<td>{{ $orgInfo[0]['name']}}</td>
						</tr>
						
						<tr>
							<th>{{   trans('message.organization.fields.contactnumber') }}</th>
							<td>{{ $orgInfo[0]['contact_number']}}</td>
							
						</tr>
						@if ($orgInfo[0]['alternate_contact_number'] !== '')
						<tr>
							<th>{{   trans('message.organization.fields.altcontactnumber') }}</th>
							<td>{{ $orgInfo[0]['alternate_contact_number']}}</td>							
						</tr>
						@endif					
						<tr>
							<th>{{   trans('message.organization.fields.email') }}</th>
							<td>{{ $orgInfo[0]['email']}}</td>
							
						</tr>
						<tr>
							<th>{{   trans('message.organization.fields.organizationtype') }}</th>
							<td>{{ $orgInfo[0]['organization_type']}}</td>
							
						</tr>
					   <tr>
							<th>{{   trans('message.organization.fields.location') }}</th>
							<td>{{ $orgInfo[0]['location']}}</td>
							
						</tr>
						  <tr>
							<th>{{   trans('message.organization.fields.about') }}</th>
							<td>{{ $orgInfo[0]['about']}}</td>
							
						</tr>
						
				</tbody>
				</table>
	</div>    
</div>
@endsection
