<div class="modal fade"  id="settings" role="dialog">
	<div class="modal-dialog sj_modal">
	  <!-- Modal content-->
	  <div class="modal-content sportsjun-forms">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">{{ trans('message.settings') }}</h4>
		</div>
@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
@endif
	<form action='/tournaments/settings/{{$tournament_id}}/update' method="post">
		{!!csrf_field()!!}

		<div class="alert alert-success" id="div_success_tourney" style="display:none;"></div>
		<div class="alert alert-danger" id="div_failure_tourney" style="display:none;"></div>
		<div class="modal-body">
	        <div class="sportsjun-forms sportsjun-container sportsjun-forms-modal">
		        <div class="form-body" id='tournamentSettings'>
		        	


					
        			</div>
        		</div>
        	</div>
		<div class="modal-footer">
			<button type="submit" class="button btn-primary">Save</button>
			<button type="button" class="button btn-secondary" data-dismiss="modal">Close</button>
		</div>
		</form>

	  </div>
	</div>
</div>
	