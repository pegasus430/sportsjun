@extends('layouts.print')

@section('content')
<style>
	table{
		background: white
	}
</style>

			<center> 
			<div style="">
					<h3>{{$tournament->name}}</h3>
			</div>
			  </center>


		<table style="width:100%">
		
					<tr>
						<th> Teams </th>
						<th> Scheduled Date </th>
						<th> Match Type </th>
						<th> Player Type </th>
						<th> Score </th>
						<th> Winner </th>
						<th></th>
					</tr>
				
							@foreach($schedules as  $match)
									<?php if($match->match_status=='completed') $match_class='sub_tour';
										  else $match_class='bg-grey';

										  if(empty($match->match_details)) $match_started=false;
										  else $match_started=true;
								    ?>

							 <tr  >
							 	     <td>	
					@if($match['schedule_type']=='team' )
					
					{!! Helper::Images($team_logo[$match['a_id']]['url'],'teams',array('class'=>'img-circle img-border ','height'=>32,'width'=>32) )!!}

						{{ $team_name_array[$match['a_id']] }}	{{'VS'}}
					
										{!! Helper::Images($team_logo[$match['b_id']]['url'],'teams',array('class'=>'img-circle img-border ','height'=>32,'width'=>32) )!!}
					
						{{ $team_name_array[$match['b_id']] }}

					@else	              					
					    <div class="team_player_sj_img">
	                                {!! Helper::Images($user_profile[$match['a_id']]['logo'],'user_profile',array('class'=>'img-circle img-border ','height'=>32,'width'=>32) )!!}
	                    </div> 
						{{ $user_name[$match['a_id']] }}	{{'VS'}}
						<div class="team_player_sj_img">
	                                {!! Helper::Images($user_profile[$match['b_id']]['logo'],'user_profile',array('class'=>'img-circle img-border ','height'=>32,'width'=>32) )!!}
	                    </div>	
	                    {{ $user_name[$match['b_id']] }}					
					@endif  
							</td>                             
                    
                  								
					<td class="match-detail-score">{{ Helper::displayDateTime($match['match_start_date'] . (isset( $match['match_start_time'] ) ? " " . $match['match_start_time'] : ""), 1) }}</td>
								
									
					<td class='match_type_text'>({{ $match['match_type']=='odi'?strtoupper($match['match_type']):ucfirst($match['match_type']) }}, {{ucfirst($match['match_category'])}})
					</td>			
			
									
				 <td class=''> {{$match['match_location']}} 								   </td>
				 <td class='event_date sports_text'>{{ucfirst($match['match_status']) }}      </td>
				 <td class='blue'>{{Helper::getMatchDetails($match['id'])->scores}} </td>
						<td>
							@if(!is_null($match['winner_id']))
						<span class='red'>Winner: {{Helper::getMatchDetails($match['id'])->winner}} </span>	  @endif
						</td>
                                                 
					</tr>			
					@endforeach


		</table>

@stop


