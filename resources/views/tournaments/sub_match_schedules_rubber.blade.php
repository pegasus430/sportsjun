
		@if(count($match['rubbers']))
										@foreach($match['rubbers'] as  $rubber)
									<?php if($rubber->match_status=='completed') $rubber_class='bg-grey';
										  else $rubber_class='';

										  if(empty($rubber->match_details)) $rubber_started=false;
										  else $rubber_started=true;
								    ?>

							 <div class="sub_tour clearfix {{$rubber_class}}" style="margin:0 0 0 0">
							 	     <div class="col-md-3 schedule_new_team_img">	
					@if($match['schedule_type']=='team' )
						<div class="team_player_sj_img">
										{!! Helper::Images($team_logo[$rubber['a_id']]['url'],'teams',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
						</div>
							{{'VS'}}
						<div class="team_player_sj_img">
										{!! Helper::Images($team_logo[$rubber['b_id']]['url'],'teams',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
						</div>    

					@else	              					
					    <div class="team_player_sj_img">
	                                {!! Helper::Images($user_profile[$rubber['a_id']]['logo'],'user_profile',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
	                    </div> 
							{{'VS'}}
						<div class="team_player_sj_img">
	                                {!! Helper::Images($user_profile[$rubber['b_id']]['logo'],'user_profile',array('class'=>'img-circle img-border ','height'=>52,'width'=>52) )!!}
	                    </div>						
					@endif  
							</div>                             
                           
                           					<!-- Start of Match Details -->
                                            <div class="col-md-6 col-sm-9 col-xs-12 schedule_new_team_txt">
                                                    <div class="t_tltle">
                                                    	<div class="pull-left">
                    @if($match['schedule_type']=='team' )
                    	<h4 class="tour-title">
	                    	{{ $team_name_array[$rubber['a_id']] }}
	                        {{ 'VS' }}                                        
	                        {{ $team_name_array[$rubber['b_id']] }}
                        </h4>
                    @else
                       <h4 class="tour-title">
                      	  {{ $user_name[rubber['a_id']] }}
                          {{ 'VS' }}                                        
                          {{ $user_name[$rubber['b_id']] }}
                      </h4>
                    @endif

                            <br>									
									<span class="match-detail-score">{{ Helper::displayDateTime($rubber['match_start_date'] . (isset( $rubber['match_start_time'] ) ? " " . $rubber['match_start_time'] : ""), 1) }}</span>
									<span class='sports_text'>{{ isset($sport_name)?$sport_name:'' }}</span>
									
					@if($rubber['match_type']!='other')
											<span class='match_type_text'>({{ $rubber['match_type']=='odi'?strtoupper($rubber['match_type']):ucfirst($rubber['match_type']) }}, {{ucfirst($rubber['match_category'])}})</span>
					@endif
									<br/>
									<!-- match_details -->
									
									<span class=''>{{$rubber['match_location']}}<br></span>
									Status: <span class='event_date sports_text'>{{ucfirst($rubber['match_status']) }}</span> <br>
									Scores: <span class='blue'>{{Helper::getMatchDetails($rubber['id'], 'rubber')->scores}} </span> <br>
					@if(!is_null($rubber['winner_id']))
								<span class='red'>Winner: {{Helper::getMatchDetails($rubber['id'], 'rubber')->winner}} </span>
								
					@endif
                                                        </div>
                                                       
                                                    </div>

                                                    <ul class="t_tags">
                                                        
                                                    </ul>
                                                   
                                                </div>
                                            <!-- End of Match Details -->
                                             <div class="col-md-3 col-sm-3">
                                             	@if(!$rubber_started)
                                             		  @if($isOwner)
                                             		 <div class="pull-right ed-btn">
                                                        <a href="javascript:void(0);" class="schedule_match_main edit" onclick="rubberEdit({{$rubber['id']}} )" title='Edit Schedule'><i class="fa fa-pencil"></i></a>
                                                     </div>
                                                     @endif
                                                @endif
                                                RUBBER {{$rubber->rubber_number}} 
                                             </div>
                                    </div>
											
										@endforeach
								@else 

		  @if($isOwner)
			 <div class="sj-alert sj-alert-info">
			 	<a href='javascript:void(0)' class='btn btn-secondary' onclick="addRubber({{$match['id']}})"> Add Rubber </a>
			 </div>
		  @endif

								@endif