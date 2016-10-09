<!-- Modal -->
<div id="editsubtournament" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content sportsjun-forms">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ trans('message.tournament.edit_tournament_event_modal.heading') }}</h4>
      </div>

<!--<div class="form-header header-primary"><h4><i class="fa fa-pencil-square"></i>{{ trans('message.tournament.fields.heading') }}</h4></div>-->
                  				
				@if( $roletype=='admin')
                 {!! Form::model( $tournament ,(array('route' => array('admin.tournaments.update',$id),'class'=>'form-horizontal','method' => 'put','id' => 'edit-tournaments'))) !!}   
			         <div class="modal-body">
      	<div class="sportsjun-forms">                 
			     <div class="form-body">
                        @include ('tournaments._form', ['submitButtonText' => 'Update'])
						</div>	
				 <div class="form-footer">
                  <button type="submit" class="button btn-primary">Update</button>

                </div>	
                {!! Form::close() !!}
					{!! JsValidator::formRequest('App\Http\Requests\CreateTournamentRequest', '#edit-tournaments'); !!}
			   @endif
					
					
					
			   @if( $roletype=='user')
			   {!! Form::model( $tournament,(array('route' => array('tournaments.update',$id),'class'=>'form-horizontal','method' => 'put','id' => 'edit-tournaments'))) !!}   
			         <div class="modal-body">
      	<div class="sportsjun-forms">
				 
			  <div class="form-body">
                       @include ('tournaments._form', ['submitButtonText' => 'Update'])
			    </div>	
				
                
					
      				 
</div>       
      </div>
      <div class="modal-footer">
	  <button type="submit" onclick="update();" class="button btn-primary">Update</button>
        <button type="button" class="button btn-secondary" data-dismiss="modal">Close</button>
      </div>
	  {!! Form::close() !!}
					{!! JsValidator::formRequest('App\Http\Requests\CreateTournamentRequest', '#edit-tournaments'); !!}

					
				@endif	
    </div>

  </div>
</div>

					<script type="text/javascript">
							$(function() {
								$.validator.addMethod("greater_startdate", function(value, element) {
									 var startDate = $('[name="start_date"]').val();
									  var endDate = $('[name="end_date"]').val();
									  var startDate = startDate.split('/').reverse().join('-');
									var endDate = endDate.split('/').reverse().join('-');										
									return Date.parse(endDate) >= Date.parse(startDate);
								}, "End Date must be equal to or after Start Date");
								$('[name="end_date"]').rules("add", "greater_startdate");
								
								
								$.validator.addMethod("win_loose_points", function(value, element) {
									 var points_win = $('[name="points_win"]').val();
									  var points_loose = $('[name="points_loose"]').val();
									  
									  	if(points_win == '' && points_loose == '')
					        		    return true;
					        	    else
									return (parseInt(points_win)) >= parseInt((points_loose));
								}, " Losing team points must be less than Winning team points");
								$('[name="points_loose"]').rules("add", "win_loose_points");

								$.validator.addMethod("win_tie_points", function(value, element) {
									 var points_win = $('[name="points_win"]').val();
									  var points_tie = $('[name="points_tie"]').val();
									  
									  	if(points_win == '' && points_tie == '')
					        		    return true;
					        	    else
									return (parseInt(points_win)) >= parseInt((points_tie));
								}, " tie match points must be less than Winning team points");
								$('[name="points_tie"]').rules("add", "win_tie_points");
								
								
								
								
								
							});
                    </script>



