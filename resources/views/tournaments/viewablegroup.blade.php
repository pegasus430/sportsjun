<!-- /.panel-heading -->



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
								    <th>Draw</th>
                                @if(in_array($sports_id, [4,11]))                               		
                                    <th>GF</th>
                                    <th>GA</th>
                                @endif
											<th>Points</th>
											@if ( $tour['sports_id'] == 1 )
												<th>Net Run Rate</th>
											@endif
										</tr>
										</thead>
										<tbody>
										@foreach($team_details[$group->id] as $team)
				 <?php $match_count_details=Helper::getMatchGroupDetails($tournament_id, $group->id, $team['team_id']);?>
											<tr>
												<td>{{ $team['name'] }}</td>
												<td>{{ !empty($match_count[$group->id][$team['team_id']])?$match_count[$group->id][$team['team_id']]:0 }}</td>
												<td>{{ !empty($team['won'])?$team['won']:0 }}</td>
												<td>{{ !empty($team['lost'])?$team['lost']:0 }}</td>
									<td>{{$match_count_details['tie']}}</td>
                                @if(in_array($sports_id, [4,11]))                               		
                                    <td>{{$match_count_details['gf']}}</td>
                                    <td>{{$match_count_details['ga']}}</td>
                                @endif
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
						<div class="tab-pane fade " id="matches_{{ $group->id }}">
						<div class="action-panel">
							<center><h4 class="mtc_details">Match Details</h4></center>
							
							<div class="clearfix"></div>

							@if(!empty($match_details[$group->id]))	
						<div class="pull-left half-width col-xs-12 col-sm-4 "> <input class='full-width form-control dark-border' placeholder="filter match e.g team name, date" onkeyup="filterDiv(this, {{$group->id}})"></div>
						<div class="clearfix"></div>
							<br>
									<?php $i=0;?>				
									@foreach($match_details[$group->id] as $match)

							<?php 
							$i++;
								$class='schedule_new_req_nor';	
								if($i % 2 == 0)
								{
									$class='schedule_new_req_alt';	
								}else
								{
									
									$class='schedule_new_req_nor';	
								}
							?>	
										@if($match['a_id']!='' && $match['b_id'])
											@if($match['schedule_type']=='team')
												<div class="row {{$class}} row_to_filter_{{$group->id}}">

													<div class='col-md-3 col-sm-12 schedule_new_team_img'>
													@if(!empty($team_logo[$match['a_id']]))
														@if($team_logo[$match['a_id']]['url']!='')
															<!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/teams/'.$team_logo[$match['a_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
																<div class="team_player_sj_img">
																	{!! Helper::Images($team_logo[$match['a_id']]['url'],'teams',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
																</div>
														@else
															<!--<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
																<div class="team_player_sj_img">
																	{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
																</div>
														@endif
													@else
														<!--<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
															<div class="team_player_sj_img">
																{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
															</div>
													@endif
													{{ 'VS' }}
													@if(!empty($team_logo[$match['b_id']]))
														@if($team_logo[$match['b_id']]['url']!='')
															<!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/teams/'.$team_logo[$match['b_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
																<div class="team_player_sj_img">
																	{!! Helper::Images($team_logo[$match['b_id']]['url'],'teams',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
																</div>
														@else
															<!--	<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
																<div class="team_player_sj_img">
																	{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
																</div>
														@endif
													@else
														<!--<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
															<div class="team_player_sj_img">
																{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
															</div>
														@endif
													</div>
													<div class='col-md-6 col-sm-8 schedule_new_team_txt'>
														<h4 class="tour-title">
															{{ $team_name_array[$match['a_id']] }}
															{{ 'VS' }}
															{{ $team_name_array[$match['b_id']] }}
														</h4>
														<br>
														<span class="match-detail-score">{{ Helper::displayDateTime($match['match_start_date'] . (isset( $match['match_start_time'] ) ? " " . $match['match_start_time'] : ""), 1) }}</span>
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
						{{--
														@if($match['sports_id']==1)
									<span class=""><a href="javascript:void(0)" class="text-primary" data-toggle="modal" data-target="#match_summary">Match Summary</a></span>

							      @include('tournaments.match_stats.match_summary')
								@endif
						--}}


													</div>



								<div class='col-md-3 col-sm-4  schedule_new_team_edit'>
																						

										@if(!empty($match['player_of_the_match']))
										<div class='visible-xs-block'>
											<hr>
										</div>
										<center><h5 class=' tour-title'>Player of the Match</h5></center>
										<br>
											<?php $player_of_the_match=Helper::getUserDetails($match['player_of_the_match']);
											?>
								{!! Helper::Images($player_of_the_match->logo, 'user_profile',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
								
								<center><br><a href='/editsportprofile/{{$player_of_the_match->id}}'>{{$player_of_the_match->name}}</a>
							         @endif
								

												<br>
											
							    </center>
													</div>

												</div>
											@else

												<div class='row'>
													<div class='col-md-3'>

													@if($user_profile[$match['a_id']]['url']!='')
														<!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/user_profile/'.$user_profile[$match['a_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->

															<div class="team_player_sj_img">
																{!! Helper::Images($user_profile[$match['a_id']]['url'],'user_profile',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
															</div>

													@else
														<!--	<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
															<div class="team_player_sj_img">
																{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
															</div>

													@endif
													{{'VS'}}

													@if($user_profile[$match['b_id']]['url']!='')
														<!--<img class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ url('/uploads/user_profile/'.$user_profile[$match['b_id']]['url']) }}" onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';">-->
															<div class="team_player_sj_img">
																{!! Helper::Images($user_profile[$match['b_id']]['url'],'user_profile',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
															</div>
													@else
														<!--	<img  class="fa fa-user fa-fw fa-2x" height="42" width="42" src="{{ asset('/images/default-profile-pic.jpg') }}">-->
															<div class="team_player_sj_img">
																{!! Helper::Images('default-profile-pic.jpg','images',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
															</div>

														@endif

													</div>

													<div class='col-md-6'>
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
													</div>



													<div class="col-md-3">
												

													</div>>
												</div>>

											@endif
										@else
											No Scheduled Matches.
										@endif
									@endforeach
									
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