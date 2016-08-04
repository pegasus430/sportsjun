<!-- /.panel-heading -->


@include('tournaments.share_groups')

<div id='content_to_share'>

	@foreach($tournament as $tour)
		@if(count($tour->groups))
			@foreach($tour->groups as $group)
				<div class="group_no"><h4 class="stage_head">{{ $group->name }}</h4></div>

				<div class="cstmpanel-tabs">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs nav-justified">
						<li class="active"><a href="#teams_{{ $group->id }}" data-toggle="tab" aria-expanded="true">Teams</a></li>
						<li class=""><a href="#matches_{{ $group->id }}" data-toggle="tab" aria-expanded="false">Matches</a></li>
					</ul>
					<!-- Tab panes -->
					{!! Form::open(array('url' => '', 'files'=> true)) !!}
					<div class="tab-content">

						<div class="tab-pane fade active in table-responsive" id="teams_{{ $group->id }}">
							<div class="action-panel">
								@if(!empty($team_details[$group->id]))
									<table class="table table-striped">
										<thead class="thead">
										<tr>
											<th>Name</th>
											<th>Matches</th>
											<th>Won</th>
											<th>Lost</th>
											<th>Points</th>
											@if ( $tour['sports_id'] == 1 )
												<th>Net Run Rate</th>
											@endif
										</tr>
										</thead>
										<tbody>
										@foreach($team_details[$group->id] as $team)
											<tr>
												<td>{{ $team['name'] }}</td>
												<td>{{ !empty($match_count[$group->id][$team['team_id']])?$match_count[$group->id][$team['team_id']]:0 }}</td>
												<td>{{ !empty($team['won'])?$team['won']:0 }}</td>
												<td>{{ !empty($team['lost'])?$team['lost']:0 }}</td>
												<td>{{ !empty($team['points'])?$team['points']:0 }}</td>
												@if ( $tour['sports_id'] == 1 )
													<td>{{ !empty($net_run_rate[$team['team_id']])?$net_run_rate[$team['team_id']]:"--" }}</td>
												@endif
											</tr>
										@endforeach
										</tbody>
									</table>
								@else
									No Teams.
								@endif
							</div>
						</div>
						<div class="tab-pane fade table-responsive" id="matches_{{ $group->id }}">
							<center><h4 class="mtc_details">Match Details</h4></center>
							@if(!empty($match_details[$group->id]))
								<table class="table table-striped">
									<tbody>
									@foreach($match_details[$group->id] as $match)
										@if($match['a_id']!='' && $match['b_id'])
											@if($match['schedule_type']=='team')
												<tr>


													<td>
													@if(!empty($team_logo[$match['a_id']]))
														@if($team_logo[$match['a_id']]['url']!='')
															<!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/teams/'.$team_logo[$match['a_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
																<div class="team_player_sj_img">
																	{!! Helper::Images($team_logo[$match['a_id']]['url'],'teams',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
																</div>
														@else
															<!--<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
																<div class="team_player_sj_img">
																	{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
																</div>
														@endif
													@else
														<!--<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
															<div class="team_player_sj_img">
																{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
															</div>
													@endif
													{{ 'VS' }}
													@if(!empty($team_logo[$match['b_id']]))
														@if($team_logo[$match['b_id']]['url']!='')
															<!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/teams/'.$team_logo[$match['b_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
																<div class="team_player_sj_img">
																	{!! Helper::Images($team_logo[$match['b_id']]['url'],'teams',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
																</div>
														@else
															<!--	<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
																<div class="team_player_sj_img">
																	{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
																</div>
														@endif
													@else
														<!--<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
															<div class="team_player_sj_img">
																{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
															</div>
														@endif
													</td>
													<td>
														<h4 class="tour-title">
															{{ $team_name_array[$match['a_id']] }}
															{{ 'VS' }}
															{{ $team_name_array[$match['b_id']] }}
														</h4>

														<span class="event-date">{{ Helper::displayDateTime($match['match_start_date'] . (isset( $match['match_start_time'] ) ? " " . $match['match_start_time'] : ""), 1) }}</span>
														<span class='sports_text'>{{ isset($sport_name)?$sport_name:'' }}</span>
														@if($match['match_type']!='other')
															<span class='match_type_text'>({{ $match['match_type']=='odi'?strtoupper($match['match_type']):ucfirst($match['match_type']) }}, {{ucfirst($match['match_category'])}})</span>
														@endif
														<br/>
														<!-- match details -->
							<span class=''>{{$match['address']}} ({{ $match['city'] }}, {{ $match['state'] }}, {{ $match['country'] }})</span><br>
									Status: <span class='event_date'>{{ucfirst($match['match_status'])}}</span> <br>
                                    
                                    <span class="match-detail-score">Scores: <span class='blue'>{{Helper::getMatchDetails($match['id'])->scores}} </span></span><br>
									@if(!is_null($match['winner_id']))
                                        <span class="match-detail-winner red">Winner: {{Helper::getMatchDetails($match['id'])->winner}} </span>
									@endif

														<br>
														@if(!empty($add_score_link[$match['id']]))
															@if($add_score_link[$match['id']]==trans('message.schedule.viewscore'))
																<span class="tournament_score"><a href="{{ url('match/scorecard/view/'.$match['id']) }}">{{$add_score_link[$match['id']]}}</a></span>
															@else
																<span class="tournament_score"><a href="{{ url('match/scorecard/edit/'.$match['id']) }}">{{$add_score_link[$match['id']]}}</a></span>
															@endif
														@endif


													</td>



													<td>

													</td>

												</tr>
											@else

												<tr>
													<td>

													@if($user_profile[$match['a_id']]['url']!='')
														<!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/user_profile/'.$user_profile[$match['a_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->

															<div class="team_player_sj_img">
																{!! Helper::Images($user_profile[$match['a_id']]['url'],'user_profile',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
															</div>

													@else
														<!--	<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
															<div class="team_player_sj_img">
																{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
															</div>

													@endif
													{{'VS'}}

													@if($user_profile[$match['b_id']]['url']!='')
														<!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/user_profile/'.$user_profile[$match['b_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
															<div class="team_player_sj_img">
																{!! Helper::Images($user_profile[$match['b_id']]['url'],'user_profile',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
															</div>
													@else
														<!--	<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
															<div class="team_player_sj_img">
																{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
															</div>

														@endif

													</td>

													<td>
														<h4 class="tour-title">
															{{ $user_name[$match['a_id']] }}
															{{ 'VS' }}
															{{ $user_name[$match['b_id']] }}
														</h4>

														<span class="event-date">{{ Helper::displayDateTime($match['match_start_date'] . (isset( $match['match_start_time'] ) ? " " . $match['match_start_time'] : ""), 1) }}</span>
														<span class='sports_text'>{{ isset($sport_name)?$sport_name:'' }}</span>
														@if($match['match_type']!='other')
															<span class='match_type_text'>({{ $match['match_type']=='odi'?strtoupper($match['match_type']):ucfirst($match['match_type']) }})</span>
														@endif
														<br/>
														@if(!empty($add_score_link[$match['id']]))
															@if($add_score_link[$match['id']]==trans('message.schedule.viewscore'))
																<span class="tournament_score"><a href="{{ url('match/scorecard/view/'.$match['id']) }}">{{$add_score_link[$match['id']]}}</a></span>
															@else
																<span class="tournament_score"><a href="{{ url('match/scorecard/edit/'.$match['id']) }}">{{$add_score_link[$match['id']]}}</a></span>
															@endif

														@endif
													</td>



													<td>

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
		@else
			<div class="sj-alert sj-alert-info">
				{{ trans('message.tournament.group.nomatchschedules') }}
			</div>
@endif
@endforeach