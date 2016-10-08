  <!-- /.panel-heading -->
			@foreach($tournament as $tour)
			@foreach($tour->groups as $group)
			<div class="group_no">{{ $group->name }}</div>
			
			<div class="panel-body">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs nav-justified">
					<li class="active"><a href="#home_{{ $group->id }}" data-toggle="tab" aria-expanded="true">Teams</a>
					</li>
					<li class=""><a href="#profile_{{ $group->id }}" data-toggle="tab" aria-expanded="false">Matches</a>
					</li>
				</ul>
				<!-- Tab panes -->
				{!! Form::open(array('url' => '', 'files'=> true)) !!}
				<div class="tab-content">
				
					<div class="tab-pane fade active in table-responsive" id="home_{{ $group->id }}">
						@if(!empty($team_details[$group->id]))
							<table class="table table-striped">
                            <thead>
							<tr>
							<th>Name</th>
							<th>Matches</th>
							<th>Won</th>
							<th>Lost</th>
							<th>Points</th>
							</tr>
                            </thead>
							<tbody>
							@foreach($team_details[$group->id] as $team)
							<tr>
							<td>{{ $team['name'] }}</td>
							<td>{{ $team['match_id'] }}</td>
							<td>{{ $team['won'] }}</td>
							<td>{{ $team['lost'] }}</td>
							<td>{{ $team['points'] }}</td>
							
							</tr>	
							@endforeach	
							</tbody>
							</table>
						@else
							No Teams.
						@endif
						
						
						
					</div>
					<div class="tab-pane fade table-responsive" id="profile_{{ $group->id }}">
						<center><h4 class="mtc_details">Match Details</h4></center>
						@if(!empty($match_details[$group->id]))
						<table class="table table-striped">
							<tbody>
							@foreach($match_details[$group->id] as $match)
							@if($match['a_id']!='' && $match['b_id'])
								@if($match['schedule_type']=='team')
							<tr>
							
								<td>
                                <center>
                                <p>{{ $team_name_array[$match['a_id']] }}</p>
								@if(!empty($team_logo[$match['a_id']]))
								@if($team_logo[$match['a_id']]['url']!='')
								<img class="fa fa-user fa-2x img-circle" height="42" width="42" src="{{ url('/uploads/teams/'.$team_logo[$match['a_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">
								@else
								<img  class="fa fa-user fa-2x img-circle" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">
								</center>
								</td>
								@endif
								@endif
								<td>
                                	<center>{{ $match['match_start_date'].','.$match['match_end_date'] }}</center>
								</td>
								<td>
                                <center>
                                	<p>{{ $team_name_array[$match['b_id']] }}</p>
                                    @if(!empty($team_logo[$match['b_id']]))
									@if($team_logo[$match['b_id']]['url']!='')
                                    <img class="fa fa-user fa-2x img-circle" height="42" width="42" src="{{ url('/uploads/teams/'.$team_logo[$match['b_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">
									@else
									<img  class="fa fa-user fa-2x img-circle" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">
									@endif
                                 </center>
								</td>
								@endif
								
							</tr>
							@else
								
							<tr>
								<td>{{ $user_name[$match['a_id']] }}
								@if($user_profile[$match['a_id']]['url']!='')
								<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/user_profile/'.$user_profile[$match['a_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">
								@else
								<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">
								
								</td>
								@endif
								<td>{{ $match['match_start_date'].','.$match['match_end_date'] }}
								</td>
								<td>{{ $user_name[$match['b_id']] }}
								@if($user_profile[$match['b_id']]['url']!='')
								<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/user_profile/'.$user_profile[$match['b_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">
								@else
								<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">
								@endif
								</td>
								
							</tr>
								
							@endif
							@else
								No Scheduled Matches.
							@endif
							@endforeach	
							</tbody>
						</table>
						@else
							No Scheduled Matches.
						@endif
						
					</div>
					
				</div>
				
				{!!Form::close()!!}
			</div>
			@endforeach
			@endforeach
			