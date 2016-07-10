@extends(Auth::user() ? 'layouts.app' : 'home.layout')
@section('content')
	@include ('tournaments._leftmenu')

	<div id="content" class="col-sm-10 tournament_profile">
		@include('tournaments.share')
		<div class="col-md-8 col-md-offset-2 mrg_top">
			{!! Helper::Images(!empty($left_menu_data['logo'])?$left_menu_data['logo']:'',!empty($left_menu_data['path'])?$left_menu_data['path']:'',array('height'=>'','width'=>'','class'=>'img_tbl img-responsive') )!!}
		</div>

		<div class="col-md-8 col-md-offset-2 captureImage">
			<div class="group_no clearfix">
				<h4 class="stage_head">{{ trans('message.tournament.fields.tournamentdetails') }}</h4>
			</div>
			<table class="table table-striped table-stripped">
				<tbody>
				<!--<tr>
							<th>{{   trans('message.tournament.fields.parenttournamentname') }}</th>
							<td>{{ $tournamentInfo[0]['tournament_parent_name']}}</td>
						</tr>-->

				<tr>
					<th>{{   trans('message.tournament.fields.name') }}</th>
					<td>{{ $tournamentInfo[0]['name']}}</td>
				</tr>
				<tr>
					<th>{{   trans('message.tournament.fields.sportsname')}}</th>
					<td>{{ $sport_name.'('.$tournamentInfo[0]['match_type'].')'  }}</td>
				</tr>
				<tr>
					<th>{{   trans('message.tournament.fields.contactpersonname') }}</th>
					<td><a href="{{ url('/editsportprofile').'/'.(!empty($tournamentInfo[0]['manager_id'])?$tournamentInfo[0]['manager_id']:0) }}">{{ $manager_name }}</a></td>
				</tr>

				<tr>
					<th>{{   trans('message.tournament.fields.type') }}</th>
					<td>{{ $tournamentInfo[0]['type']}}</td>

				</tr>

				<!--<tr>
							<th>{{   trans('message.tournament.fields.match_type') }}</th>
							<td>{{ $tournamentInfo[0]['match_type']}}</td>

						</tr>-->
				<tr>
					<th>{{   trans('message.tournament.fields.player_type') }}</th>
					<td>{{ $tournamentInfo[0]['player_type']}}</td>

				</tr>
				<!--<tr>
							<th>{{   trans('message.tournament.fields.schedule_type') }}</th>
							<td>{{ $tournamentInfo[0]['schedule_type']}}</td>

						</tr>-->
				<!--<tr>
							<th>{{   trans('message.tournament.fields.status') }}</th>
							<td>{{ $tournamentInfo[0]['status']}}</td>

						</tr>-->
				<tr>
					<th>{{  "Start-End Dates" }}</th>
					<td>{{ Helper::displayDate($tournamentInfo[0]['start_date'])}} to {{ Helper::displayDate($tournamentInfo[0]['end_date'])}}</td>

				</tr>
				<!--<tr>
							<th>{{   trans('message.tournament.fields.enddate') }}</th>
							<td>{{ Helper::displayDate($tournamentInfo[0]['end_date'])}}</td>

						</tr>-->

				<tr>
					<th>{{   trans('message.tournament.fields.groups') }}</th>
					<td>{{ $tournamentInfo[0]['groups_number']}}</td>

				</tr>
				<tr>
					<th>{{   trans('message.tournament.fields.noofteams') }}</th>
					<td>{{ $tournamentInfo[0]['groups_teams']}}</td>

				</tr>
				<tr>
					<th>{{   trans('message.tournament.fields.pricemoney') }}</th>
					<td>{{ $tournamentInfo[0]['prize_money']}}</td>

				</tr>

				<tr>
					<th>{{   trans('message.tournament.fields.contactnumber') }}</th>
					<td>{{ $tournamentInfo[0]['contact_number']}}</td>

				</tr>

				@if ($tournamentInfo[0]['alternate_contact_number'] !== '')
					<tr>
						<th>{{   trans('message.tournament.fields.altcontactnumber') }}</th>
						<td>{{ $tournamentInfo[0]['alternate_contact_number']}}</td>
					</tr>
				@endif

				<tr>
					<th>{{   trans('message.tournament.fields.email') }}</th>
					<td>{{ $tournamentInfo[0]['email']}}</td>
				</tr>
				<tr>
					<th>{{   trans('message.tournament.fields.location') }}</th>
					<td>{{ $tournamentInfo[0]['location']}}</td>
				</tr>
				<tr>
					<th>{{   trans('message.tournament.fields.description') }}</th>
					<td>{{ $tournamentInfo[0]['description']}}</td>
				</tr>


				</tbody>
			</table>
		</div>
	</div>
@endsection