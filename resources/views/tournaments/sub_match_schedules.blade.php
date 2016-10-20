
@if(count($firstRoundBracketArray))
  @foreach($firstRoundBracketArray as $key => $schedule)
  	    @if((isset($schedule['tournament_round_number']) && $schedule['tournament_round_number']==$round) && isset($schedule['id'])  )

  	  
                <div class="col-sm-12 match_set " style="">             	
                  
                       <?php $match=Helper::getMatchDetails($schedule['id']); ?>
      @if(($match['a_id']!='' && $match['b_id']) ) 

        <?php 
  	    	$i=$i+1;;
								$class='schedule_new_req_nor';	
								if($i % 2 == 0)
								{
									$class='schedule_new_req_alt';	
								}else
								{
									
									$class='schedule_new_req_nor';	
								}
		?>
      		<br>
      		
				@if($match['schedule_type']=='team' )
							<div class="row row_to_filter_ {{$class}}">
						
								<div class="col-md-3 schedule_new_team_img">
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
								<div class="col-md-6 col-sm-8 schedule_new_team_txt">
                                	<h4 class="tour-title">
                                    	<a href="/team/members/{{$match['a_id']}}" class="primary">	
															{{ $team_name_array[$match['a_id']] }}
														</a>
															{{ 'VS' }}
														<a href="/team/members/{{$match['b_id']}}" class="primary">	
															{{ $team_name_array[$match['b_id']] }}
														</a>
                                    </h4>
                                    <br>									
									<span class="match-detail-score">
										@if ($match['match_start_date'])
											{{ Helper::displayDateTime($match['match_start_date'] . (isset( $match['match_start_time'] ) ? " " . $match['match_start_time'] : ""), 1) }}
										@else
											TBD
										@endif
									</span>
									<span class='sports_text'>{{ isset($sport_name)?$sport_name:'' }}</span>
									
					@if($match['match_type']!='other')
											<span class='match_type_text'>({{ $match['match_type']=='odi'?strtoupper($match['match_type']):ucfirst($match['match_type']) }}, {{ucfirst($match['match_category'])}})</span>
					@endif
									<br/>
									<!-- match_details -->
									
									<span class=''>{{$match['address']}}</span><br>
									Status: <span class='event_date sports_text'>{{ ucfirst($match['match_status']) }}</span> <br>
									Scores: <span class='blue'>{{Helper::getMatchDetails($match['id'])->scores}} </span> <br>
					@if(!is_null($match['winner_id']))
								<span class='red'>Winner: {{Helper::getMatchDetails($match['id'])->winner}} </span>
								
					@endif

									<br>
					@if(isset($schedule['winner_text']))
							<span class="pull-left">
								<br>
                                  <a href="{{ url('match/scorecard/edit/'.$schedule['id']) }}" class="btn-primary " style="padding: .3em 1em;">{{$schedule['winner_text']}}</a>   
                            </span>                         
      
         		    @endif

         		    @if($match['game_type']=='rubber')
         		    			<span class="pull-right">
         		    			<br>
                 			  <a href="javascript:void(0)" class="show_sub_field show_sub_tournament" parent_field_id = "{{$match['id']}}">View Rubbers</a>
                 			  	</span>
                 	@endif
         			  </div>


								<div class="col-md-3 col-sm-4">

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
											
							    </center>
					
					 	@endif							
								
								</div>			
														

							</div>
				@else
							<div class="row row_to_filter_ {{$class}}">
								<div class="col-md-3 schedule_new_team_img">
								
				      <div class="team_player_sj_img">
                                	{!! Helper::Images($user_profile[$match['a_id']]['logo'],'user_profile',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
                      </div>	
                 
					{{'VS'}}
					
				
					<div class="team_player_sj_img">
                                	{!! Helper::Images($user_profile[$match['b_id']]['logo'],'user_profile',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
                    </div>		
								
					
					</div>                               
                                                  <div class="col-md-6 schedule_new_team_txt">
                                                  	<h4 class="tour-title">
                                                      	<a href="/team/members/{{$match['a_id']}}" class="primary">	
															{{ $user_name[$match['a_id']] }}
														</a>
															{{ 'VS' }}
														<a href="/team/members/{{$match['b_id']}}" class="primary">	
															{{ $user_name[$match['b_id']] }}
														</a>
                                                      </h4>
                                                  <br>
                  									
                  									<span class="match-detail-score">
														@if($match['match_start_date'])
															{{ Helper::displayDateTime($match['match_start_date'] . (isset( $match['match_start_time'] ) ? " " . $match['match_start_time'] : ""), 1) }}
														@else
															TBD
														@endif
													</span>
                  									<span class='sports_text'>{{ isset($sport_name)?$sport_name:'' }}</span>
	                	@if($match['match_type']!='other')
	                			<span class='match_type_text'>({{ $match['match_type']=='odi'?strtoupper($match['match_type']):ucfirst($match['match_type']) }})</span>
	                	@endif
									<br/>

									<span class=''>{{$match['address']}}</span><br>
									Status: <span class='event_date sports_text'>{{ ucfirst($match['match_status']) }}</span> <br>
									Scores: <span class='blue'>{{Helper::getMatchDetails($match['id'])->scores}} </span> <br>
						@if(!is_null($match['winner_id']))
								<span class='red'>Winner: {{Helper::getMatchDetails($match['id'])->winner}} </span>								
						@endif
							<br>
						@if(isset($schedule['winner_text']))
							<br>
							<span class="pull-left">
	                          <a href="{{ url('match/scorecard/edit/'.$schedule['id']) }}" class="btn-primary" style="padding: .3em 1em;">{{$schedule['winner_text']}}</a>
	                         </span>
                 		@else
		                    @if($isOwner)
		                     <span class="pull-left">
		                            <a href="javascript:void(0)" id="scheduleEdit_{{$schedule['id']}}"  onclick="editMatchSchedule({{$schedule['id']}},1,'','myModal')">Edit</a>
		                      </span>
		                    @endif
                 		@endif

                 		@if($match['game_type']=='rubber')
                 			<br>
                 				<span class="pull-right">
                 			  <a href="#" class="show_sub_field show_sub_tournament " parent_field_id = "{{$match['id']}}">View Rubber</a>
                 			  	</span>
                 		@endif
								</div>


								<!-- Player of the match single -->
								<div class="col-md-3 col-sm-4">

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
															
											    </center>
									
									 	@endif							
								
								</div>	
						</div>					
								
	
				@endif

					<!-- Show Rubbers -->

				@if($match['game_type']=='rubber')
					<div id="subfield_{{$match['id']}}" class="row" style="display:none;">
							@include('tournaments.sub_match_schedules_rubber')
					</div>

				@endif

					<!-- End of Rubber -->

              @else  
          @endif
                 </div>

    @endif
              
   @endforeach

@endif
        
