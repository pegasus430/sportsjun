<!-- Modal -->
{!! Form::open(['route' => 'generateLeagueMatching','class'=>'form-horizontal','method' => 'get','id' => 'frm_generate']) !!} 
<div class="modal fade"  id="myModal" role="dialog">
	<div class="modal-dialog sj_modal sportsjun-forms">
	  <!-- Modal content-->
	  <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">{{ trans('message.schedule.fields.schedulematch') }}</h4>
		</div>
		<div class="alert alert-success" id="div_success"></div>
		<div class="alert alert-danger" id="div_failure"></div>
		<div class="modal-body">
	        <div class="sportsjun-forms sportsjun-container wrap-2 sportsjun-forms-modal">
		        <div class="form-body">
			        <div class="spacer-b30">
						<div class="tagline"><span>{{ trans('message.schedule.fields.scheduletype') }}</span></div>
					</div> 
				</div>
	        </div>
        </div>
		<div class="modal-footer">
			<button type="button" name="save_schedule" id="save_schedule" class="button btn-primary">Schedule</button>
			<button type="button" class="button btn-secondary" data-dismiss="modal">Close</button>
		</div>
	  </div>
	  
	</div>
</div>
{!! Form::close() !!}
{!! JsValidator::formRequest('App\Http\Requests\GenerateMatchRequest', '#frm_generate'); !!}
<script type="text/javascript">
    $(document).ready(function() { 
    
    }
</script>