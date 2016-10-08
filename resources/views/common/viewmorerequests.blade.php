@if(count($sent))
	@foreach($sent as $res)
		<?php $from_to_names = json_decode($res->from_to_names);  ?>
		<div class="teams_search_display" id="request_div_{{ !empty($res->id)?$res->id:0 }}">	
			<button type="button" class="close request" data-dismiss="alert" flag='d'reqid='{{ !empty($res->id)?$res->id:0 }}' title="Close">×</button>
			<div class="search_thumbnail right-caption">
				<div class="search_image">
					{!! Helper::Images( $res->logo ,config('constants.PHOTO_PATH.'.$res->logo_type),array('height'=>90,'width'=>90,'class'=>'img-circle') )!!}
				</div>
				<div class="search_caption">
					<h3>{{ !empty($from_to_names->from_name)?$from_to_names->from_name:'' }}</h3>
					<div class="list_display">
						<p>
							<label>To</label><span>{{ !empty($from_to_names->to_name)?$from_to_names->to_name:'' }}</span>
							<label>Sport</label><span>{{ !empty($from_to_names->sport_name)?$from_to_names->sport_name:'' }}</span>
						</p>
						<p>{!! HTML::decode(str_replace("REQURL|",URL::to('/'),$res->message)) !!}</p>
					</div>
					<span class="box-action sportsjun-forms">
						@if($flag == 'Received')
                            <a href="#" class="btn-link btn-primary-link btn-primary-border request" flag='a'reqid='{{ !empty($res->id)?$res->id:0 }}'>Accept</a>
                            <a href="#" class="btn-link btn-secondary-link btn-secondary-border request" flag='r' reqid='{{ !empty($res->id)?$res->id:0 }}'>Reject</a>
                            <a href="#" class="btn-link btn-tertiary-link btn-tertiary-border request" flag='d'reqid='{{ !empty($res->id)?$res->id:0 }}'>Ignore</a>							
						@else
                        	<a href="#" class="button btn-sm btn-secondary request" flag='c' reqid='{{ !empty($res->id)?$res->id:0 }}'>Cancel</a>
                        @endif
                    </span>
				</div>
			</div>
		</div>
	@endforeach
@else
	No Records.
@endif
<script type="text/javascript">
	$(document).ready(function() {
		var offset1 = {{$offset1}};
		var offset2 = {{$offset2}};
		$("#offset1").val(offset1);
		$("#offset2").val(offset2);
		if (offset1 >= $("#global_record_count1").val())
		{
			$("#viewmorediv1").remove();
		}
		if (offset2 >= $("#global_record_count2").val())
		{
			$("#viewmorediv").remove();
		}
	});	
</script>