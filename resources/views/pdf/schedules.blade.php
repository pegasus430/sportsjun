@extends('layouts.print')

@section('content')

<style>
img{
	border-radius: 50%;
}

.div_1{
	background: none;
	color: white;
}
.div_0{
	background: #ddd;
	

}
th, td{
	text-align: center;
	padding: 10px;
}
table{
	width: 100%;
}
tr{
	height: 50px;
}


</style>



				<center>	
									@if(!empty($tournament->logo)))
						{!! Helper::Images($tournament->logo,'tournaments',array('height'=>100,'width'=>100) )!!}
									@endif

				<h2 style="text-transform:uppercase">{{strtoupper($tournament->name)}}</h2> 
		




	<table >
		<thead>
			<tr>
				<th>DATE</th>
				<th>TIME</th>
				<th>MATCHES</th>
				<th>VENUE </th>
				<TH>CATEGORY</TH>
			</tr>
			@foreach($schedules as  $key=>$match)
				<?php $i=$key%2;?>
				<tr style="width:100%" class="div_{{$i}}">
					<td> 				
						{{ date('jS F , Y',strtotime($match['match_start_date'])) }}						
					</td>
					<td>
					{{ isset( $match['match_start_time'] ) ? " " . $match['match_start_time'] : "" }}
					</td>
					<td>
					{{ $team_name_array[$match['a_id']] }}  VS {{ $team_name_array[$match['b_id']] }}
						<br>
						<br>

					@if($match['schedule_type']=='team')
								  {!! Helper::Images($team_logo[$match['a_id']]['url'],'teams',array('class'=>'img-circle img-border ','height'=>32,'width'=>32), true )!!}
	              
					   {{Helper::getMatchDetails($match['id'])->scores}} 
					
	                                {!! Helper::Images($team_logo[$match['b_id']]['url'],'teams',array('class'=>'img-circle img-border ','height'=>32,'width'=>32), true )!!} 

					@else	              					
				
	                                {!! Helper::Images($user_profile[$match['a_id']]['logo'],'user_profile',array('class'=>'img-circle img-border ','height'=>32,'width'=>32), true )!!}
	              
					pan style="color:#224488">{{Helper::getMatchDetails($match['id'])->scores}}
					
	                                {!! Helper::Images($user_profile[$match['b_id']]['logo'],'user_profile',array('class'=>'img-circle img-border ','height'=>32,'width'=>32), true )!!}             	
	           				
					@endif

					</td>
					<td>  {{ $match['match_type']=='odi'?strtoupper($match['match_type']):ucfirst($match['match_type']) }}
					</td>

					<td>
						{{ucfirst($match['match_category'])}}
					</td>
					
				</tr>

			@endforeach

	</table>


	

@stop


