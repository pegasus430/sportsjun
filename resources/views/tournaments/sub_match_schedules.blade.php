     <div class="col-sm-12">
            <div class="round-{{Helper::convert_number_to_words($round)}}">
                <div class="round"><p>    {{Helper::getRoundStage($tournament_id, $key)}} </p></div>
@if(count($firstRoundBracketArray))
  @foreach($firstRoundBracketArray as $key => $schedule)
  	    @if((isset($schedule['tournament_round_number']) && $schedule['tournament_round_number']==$round) && isset($schedule['id'])  )
                 	<div class="col-sm-12 match_set" style="">             	

                       <?php $match=Helper::getMatchDetails($schedule['id']); ?>


      @if(($match['a_id']!='' && $match['b_id']) ) 
				@if($match['schedule_type']=='team' )
							<div class="row">
						
								<div class="col-md-3 schedule_new_team_img">
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
								</div>
								<div class="col-md-6 schedule_new_team_txt">
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
									<!-- match_details -->
									
									<span class=''>{{$match['address']}}</span><br>
									Status: <span class='event_date'>{{ ucfirst($match['match_status']) }}</span> <br>
									Scores: <span class='blue'>{{Helper::getMatchDetails($match['id'])->scores}} </span> <br>
					@if(!is_null($match['winner_id']))
								<span class='red'>Winner: {{Helper::getMatchDetails($match['id'])->winner}} </span>
								
					@endif

									<br>
					@if(isset($schedule['winner_text']))
                                  <a href="{{ url('match/scorecard/edit/'.$schedule['id']) }}">{{$schedule['winner_text']}}</a>
                                 </div>
      
          @endif


								<div class="col-md-3 ">

						@if(!empty($match['player_of_the_match']))
								<div class='visible-xs-block'>
									<hr>
								</div>
								<center><h5 class=' tour-title'>Player of the Match</h5></center>
								<br>
									<?php $player_of_the_match=Helper::getUserDetails($match['player_of_the_match']);
									?>
								{!! Helper::Images($player_of_the_match->url, 'user_profile',array('class'=>'img-circle img-border img-responsive lazy','height'=>52,'width'=>52) )!!}
								
								<center><br><a href='/editsportprofile/{{$player_of_the_match->id}}'>{{$player_of_the_match->name}}</a>
											
							    </center>
					
					 	@endif							
								
								</div>


										
								
								
								

							</div>
				@else
							<div class="row">
								<div class="col-md-3 schedule_new_team_img">
								
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
					
								</div>
                                
                                                  <div class="col-md-6 schedule_new_team_txt">
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
								  @if(isset($schedule['winner_text']))
                                  <a href="{{ url('match/scorecard/edit/'.$schedule['id']) }}">{{$schedule['winner_text']}}</a>
                  @else
                    @if($isOwner)
                                          <a href="javascript:void(0)" id="scheduleEdit_{{$schedule['id']}}"  onclick="editMatchSchedule({{$schedule['id']}},1,'','myModal')">Edit</a>
                    @endif
                  @endif
								</div>
								
								
	
							  @endif




              @else


   
                                    @endif
                 </div>



        @endif
                 <p><p>&nbsp;</p>
                 @endforeach

                 @endif
            </div>
        </div>
