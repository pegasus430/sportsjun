@extends('layouts.app')
@section('content')
<input type="hidden" name="limit" id="limit" value="{{$limit}}"/>
<input type="hidden" name="offset" id="offset" value="{{$offset}}"/>

	<div class="container">
		<div class="col-md-8 col-md-offset-2 viewmoreclass">
			<h4 class="stage_head" style="margin: 18px 0px; ">Notifications</h4>
			@if(count($notifications))
				@foreach($notifications as $res)
					<div class="teams_search_display tournament_profile" id="request_div_{{ !empty($res->id)?$res->id:0 }}">
						@if($res->is_read == 0)
						<button type="button" class="close request" data-dismiss="alert" flag='d' reqid='{{ !empty($res->request_id)?$res->request_id:0 }}' notid="{{ !empty($res->id)?$res->id:0 }}" id="close_request_{{ !empty($res->id)?$res->id:0 }}" title="Close">×</button>
						@endif
						<div class="search_thumbnail right-caption">
							<div class="search_image">
								{!! Helper::Images( $res->logo ,config('constants.PHOTO_PATH.'.$res->logo_type),array('height'=>90,'width'=>90,'class'=>'img-circle img-border') )!!}
							</div>
							<div class="search_caption">
								<h4>{!! HTML::decode(str_replace("REQURL|",URL::to('/'),$res->message)) !!}</h4>
								@if(!empty($res->request_id))
								<span class="search_location">Type: {{str_replace("_"," ",array_search(!empty($res->type)?$res->type:'',config('constants.REQUEST_TYPE')))}} </span>
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
		</div>
	</div>
	<div class="clearfix"></div>
	@if($notificationsCount>count($notifications))     
    <a id="viewmorediv" class="view_tageline_mkt">
    	<span class="market_place" id="viewmorebutton"><i class="fa fa-arrow-down"></i> <label>{{ trans('message.view_more') }}</label></span>
    </a>    
	@endif

<script type="text/javascript">
	$(document).ready(function() {
		$("#offset").val({{$offset}});
		if ($("#viewmorediv").length) {
			$('#viewmorebutton').on("click", function(e) {
				var params = { limit:{{$limit}}, offset:$("#offset").val() };
				viewMore(params, '{{URL('user/viewmorenotifications')}}');
			});
			global_record_count = {{$notificationsCount}}
		}
		var n_id = '{{ !empty(Request::segment(3))?Request::segment(3):0 }}';
		if($.isNumeric(n_id) && n_id != 0)
		{
			//for selecting a div
		    $('html, body').animate({
		        scrollTop: $('#request_div_'+n_id).offset().top
		    }, 'slow');	
		     $('#request_div_'+n_id).css("border-style","solid");
		     $('#request_div_'+n_id).css("border-color","grey");
		}
	});
	//function for accept and reject a request
	$(document.body).on('click', '.request' ,function(){
		var reqid = $(this).attr('reqid');
		var notid = $(this).attr('notid');
		var limit = '{{ config("constants.LIMIT" )}}';
		$.post(base_url+'/team/updateplayerrequest',{'request_id':$(this).attr('reqid'),'flag':$(this).attr('flag'),'notid':notid},function(response,status){
			if(status == 'success' && response.result == 'success')
			{
				$(".notfication_bubble").html(response.notifications_count);
				$("#request_div_"+notid).remove();
				global_record_count = parseInt(response.notifications_count);
				var new_offset = $("#offset").val();
				new_offset--;
				$("#offset").val(new_offset);
				if($(".tournament_profile").length == 0 && $("#viewmorediv").length == 0)
				{
					window.location.reload();
				}
				// if(parseInt(response.notifications_count) <= parseInt(limit))
				// {
				// 	$("#viewmorediv").hide();
				// }
			}
		});
	});
</script>
@endsection