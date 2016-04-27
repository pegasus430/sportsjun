@if(count($notifications))
	@foreach($notifications as $res)
		<div class="teams_search_display tournament_profile" id="request_div_{{ !empty($res->id)?$res->id:0 }}">
			@if($res->is_read == 0)
			<button type="button" class="close request" data-dismiss="alert" flag='d' reqid='{{ !empty($res->request_id)?$res->request_id:0 }}' notid="{{ !empty($res->id)?$res->id:0 }}" id="close_request_{{ !empty($res->id)?$res->id:0 }}" title="Close">×</button>
			@endif
			<div class="search_thumbnail right-caption">
				<div class="search_image">
					{!! Helper::Images( $res->logo ,config('constants.PHOTO_PATH.'.$res->logo_type),array('height'=>90,'width'=>90,'class'=>'img-circle') )!!}
				</div>
				<div class="search_caption">
					<h4>{!! HTML::decode(str_replace("REQURL|",URL::to('/'),$res->message)) !!}</h4>
					@if(!empty($res->request_id))
					<span class="search_location">Type: {{str_replace("_"," ",array_search(!empty($res->type)?$res->type:'',config('constants.REQUEST_TYPE'),true))}} </span>
					@endif
					<div class="list_display">
						<p>{{ !empty($res->diff_time)?$res->diff_time:'' }}</p>
					</div>
					@if(!empty($res->request_id) && $res->is_read == 0)
						<span class="box-action sportsjun-forms" id="box_action_{{ !empty($res->request_id)?$res->request_id:0 }}">
                            <a href="#" class="btn-link btn-primary-link btn-primary-border request" flag='a'reqid='{{ !empty($res->request_id)?$res->request_id:0 }}' notid="{{ !empty($res->id)?$res->id:0 }}" >Accept</a>
                            <a href="#" class="btn-link btn-secondary-link btn-secondary-border request" flag='r' reqid='{{ !empty($res->request_id)?$res->request_id:0 }}' notid="{{ !empty($res->id)?$res->id:0 }}" >Reject</a>
                            <a href="#" class="btn-link btn-tertiary-link btn-tertiary-border request" flag='d' reqid='{{ !empty($res->request_id)?$res->request_id:0 }}' notid="{{ !empty($res->id)?$res->id:0 }}" >Ignore</a>
                        </span>	
                    @endif																
				</div>
			</div>
		</div>
	@endforeach
@else
	<div class="sj-alert sj-alert-info">You donot have any notifications.</div>
@endif

<script type="text/javascript">
	$(document).ready(function() {
		var offset = {{$offset}};
		$("#offset").val(offset);
		if (offset >= global_record_count)
		{
			$("#viewmorediv").remove();
		}
	});	
</script>