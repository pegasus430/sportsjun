@extends('layouts.print')

@section('content')


	
					<h2 style="text-transform:uppercase">{{strtoupper($tournament->name)}}</h2>
		

					
					
				
							@foreach($schedules as  $match)
									<?php if($match->match_status=='completed') $match_class='sub_tour';
										  else $match_class='bg-grey';

										  if(empty($match->match_details)) $match_started=false;
										  else $match_started=true;
								    ?>
		@if(!empty($match['a_id']) && !empty($match['b_id']))
					<div>
					<p> 	  
					@if($match['schedule_type']=='team' )
		
					{!! Helper::Images($team_logo[$match['a_id']]['url'],'teams',array('class'=>'img-circle img-border ','height'=>32,'width'=>32) )!!}
		

						{{ $team_name_array[$match['a_id']] }}	{{'VS'}}
					   
										{!! Helper::Images($team_logo[$match['b_id']]['url'],'teams',array('class'=>'img-circle img-border ','height'=>32,'width'=>32) )!!}
					
						{{ $team_name_array[$match['b_id']] }}
		

					@else	              					
				
	                                {!! Helper::Images($user_profile[$match['a_id']]['logo'],'user_profile',array('class'=>'img-circle img-border ','height'=>32,'width'=>32) )!!}
	              
						{{ $user_name[$match['a_id']] }}	{{'VS'}}
					
	                                {!! Helper::Images($user_profile[$match['b_id']]['logo'],'user_profile',array('class'=>'img-circle img-border ','height'=>32,'width'=>32) )!!}
	             	
	                    {{ $user_name[$match['b_id']] }}					
					@endif  

					 <span style="color:#224488">{{Helper::getMatchDetails($match['id'])->scores}} </span>
						                           
                    <br>                  								
					<span    class="match-detail-score">{{ Helper::displayDateTime($match['match_start_date'] . (isset( $match['match_start_time'] ) ? " " . $match['match_start_time'] : ""), 1) }}  ({{ $match['match_type']=='odi'?strtoupper($match['match_type']):ucfirst($match['match_type']) }}, {{ucfirst($match['match_category'])}})
					</span>			
			
									
				 <br><span> {{$match['match_location']}} &nbsp; &nbsp;   </span>
				 <span   style="color:#224488">{{ucfirst($match['match_status']) }} </span>
									<br>	@if(!is_null($match['winner_id']))
					Winner:	<span    style="color:#ff0000"> {{Helper::getMatchDetails($match['id'])->winner}} </span>	  @endif
						
                                                 
					
				</div>
			@endif
					@endforeach






	

@stop


