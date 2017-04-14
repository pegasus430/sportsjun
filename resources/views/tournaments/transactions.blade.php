@extends(Helper::check_if_org_template_enabled()?'layouts.organisation':'layouts.app') 
@section('content')

<div class="container">
 <div class="col-lg-12 col-md-10 col-sm-16  tournament_profile teamslist-pg" style="padding-top: 3px !important;">
	<div class="panel panel-default">
		<div class="panel-body">

         
			<div class="transcations_head">    
	        	<h4 class="left_head">Your Payments</h4>
	   	    	<h4 class="right_head"><a href="/mytournamentregistrations/{{$u_id}}">Registrations of Your Tournaments</a></h4>
		    	<div class="clear"></div><br>
			</div>

			 @if(count($details)==0)
               <div class="transcations_head">
               <h4>No Transactions</h4>
               </div> 
          @else
          		<div class="table-responsive">
	     
	     	<div class="col-sm-12" id="teamStatsDiv">
	     	
	     	
	     				<table class="table table-striped table-hover">
	     						<thead>
	     							<tr>
	     								<th>Tournament Events</th>
	     								<th>Payment Name</th>
	     								<th>Team</th>
	     								<th>Payment Email</th>
	     								<th>Date </th>
	     								<th>Amount</th>
	     							</tr>
	     						</thead>
	     						<tbody>
	     							
<?php $i=0;
	$options=array();?>
	@foreach ($details as $detail)
	           		<tr>
	           			<td>{{$detail['tournament']}} </td>
	           			<td><i class="fa fa-user"></i> {{$detail['name']}}</td>
	           			<td>	{{$detail['team']}}</td>
	           			<td><i class="fa fa-envelope"></i> {{$detail['email']}}</td>
	           			<td>{{date("jS M, Y", strtotime($detail['date']))}} </td>
	           			<td><i class="fa fa-inr"></i> {{$detail['price']}}</td>
	           		</tr>
	          		
	<?php $i++;?>
	@endforeach

	     						</tbody>

	     				</table>

          @endif



	    </div>
	</div>
 </div>
</div>
@endsection