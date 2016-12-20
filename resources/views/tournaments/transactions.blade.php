@extends('layouts.app')
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
	     
            <div class="form-body">
            	<div class="row">
                	<div class="col-sm-2">
                		<label class="field prepend-icon head_tr">	
	      					<div class="section">
	                        	<label class="field prepend-icon head_tr">Tournament Events</label>
	                        </div>
						</label>
					</div>

	                <div class="col-sm-2">
	                	<label class="field prepend-icon head_tr">	
	      					<div class="section">
	                        	<label class="field prepend-icon head_tr">Payment Name</label>
	                        </div>
						</label>
	                </div>

                    <div class="col-sm-1">
	                	<label class="field prepend-icon head_tr">	
	      					<div class="section">
	                        	<label class="field prepend-icon head_tr">Team</label>
	                        </div>
						</label>
	                </div>
                    
                    <div class="col-sm-3">
	                	<label class="field prepend-icon head_tr">	
	      					<div class="section">
	                        	<label class="field prepend-icon head_tr">Payment Email</label>
	                        </div>
						</label>
	                </div>  

 					<div class="col-sm-3">
 						<label class="field prepend-icon head_tr">	
	      					<div class="section">
	                        	<label class="field prepend-icon head_tr">Date</label>
	                        </div>
						</label>
					</div>
		
					<div class="col-sm-1">
						<label class="field prepend-icon head_tr">	
	      					<div class="section">
	                        	<label class="field prepend-icon head_tr">Amount</label>
	                        </div>
						</label>
	          		</div>

				</div><!--end row -->
        
        <br><br>

<?php $i=0;
	$options=array();?>
	@foreach ($details as $details)
	           <div class="row inner_events">
                    
                    <div class="col-sm-2">
						{{$details['tournament']}}
	          		</div>

	          		
                    
                    <div class="col-sm-2">
                   		<i class="fa fa-user"></i> {{$details['name']}}
	          		</div>

	          		<div class="col-sm-1">
						{{$details['team']}}
	          		</div>
                    
                    <div class="col-sm-3">
						<i class="fa fa-envelope"></i> {{$details['email']}}
	          		</div>

	          		 <div class="col-sm-3">
						{{date("d-M-Y h:i:s", strtotime($details['date']))}}  
	          		</div>

	          		<div class="col-sm-1">
						<i class="fa fa-inr"></i> {{$details['price']}}
	          		</div>

                    
                </div><!--end row -->
	<br><br>
	<?php $i++;?>
	@endforeach


	 
	        </div>

          @endif



	    </div>
	</div>
 </div>
</div>
@endsection