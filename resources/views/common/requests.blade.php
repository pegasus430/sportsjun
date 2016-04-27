<?php
	$currentAction = \Route::currentRouteAction();
	list($controller, $method) = explode('@', $currentAction);
	$controller = preg_replace('/.*\\\/', '', $controller);
?>
<input type="hidden" name="limit" id="limit" value="{{$limit}}"/>
<input type="hidden" name="offset1" id="offset1" value="{{$offset1}}"/>
<input type="hidden" name="offset2" id="offset2" value="{{$offset2}}"/>
<input type="hidden" name="global_record_count1" id="global_record_count1" value="{{$received_count}}"/>
<input type="hidden" name="global_record_count2" id="global_record_count2" value="{{$sent_count}}"/>
<div id="content-team" class="col-sm-8 tournament_profile">
    	<div class="col-md-12">
    	<div class="panel panel-default">
		<h4 class="panel-heading">Requests</h4>
		<div class="panel-body">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#receive">Received</a></li>
				<li><a data-toggle="tab" href="#sent">Sent</a></li>
			</ul>

			<div class="tab-content" style="padding: 15px; margin-top: 0;">
				<div  id="receive" class="tab-pane fade in active">
					<div id="receivediv" class="viewmoreclass">
						@if(count($result))
							@foreach($result as $res)
								<?php $from_to_names = json_decode($res->from_to_names);  ?>
								<div class="clearfix" id="request_div_{{ !empty($res->id)?$res->id:0 }}">	
									<button type="button" class="close request" data-dismiss="alert" flag='d'reqid='{{ !empty($res->id)?$res->id:0 }}' title="Close">×</button>
									<div class="search_thumbnail right-caption">
										<div class="search_image">
											{!! Helper::Images( $res->logo ,config('constants.PHOTO_PATH.'.$res->logo_type),array('height'=>90,'width'=>90,'class'=>'img-circle img-border') )!!}
										</div>
										<div class="search_caption">
											<h3>{{ !empty($from_to_names->from_name)?$from_to_names->from_name:'' }}</h3>
                                            <ul class="t_tags" style="color: #999">
                                            	<li>To: <span class="green">{{ !empty($from_to_names->to_name)?$from_to_names->to_name:'' }}</span></li>
                                                <li>Sport: <span class="green">{{ !empty($from_to_names->sport_name)?$from_to_names->sport_name:'' }}</span></li>
                                            </ul>
											<div class="list_display">
												<p>{!! HTML::decode(str_replace("REQURL|",URL::to('/'),$res->message)) !!}</p>
											</div>
											<span class="box-action sportsjun-forms">
					                            <a href="#" class="btn-link btn-primary-link btn-primary-border request" flag='a'reqid='{{ !empty($res->id)?$res->id:0 }}'>Accept</a>
					                            <a href="#" class="btn-link btn-secondary-link btn-secondary-border request" flag='r' reqid='{{ !empty($res->id)?$res->id:0 }}'>Reject</a>
					                            <a href="#" class="btn-link btn-tertiary-link btn-tertiary-border request" flag='d'reqid='{{ !empty($res->id)?$res->id:0 }}'>Ignore</a>
					                        </span>
										</div>
									</div>
								</div>
							@endforeach
						@else
							No Records.
						@endif
					</div>
					@if($received_count>count($result)) 
						<div  id="viewmorediv1">
							<div id="viewmorebutton1" class="btn btn-view">{{ trans('message.view_more') }}</div>
						</div>
					@endif					
				</div>

				<div id="sent"  class="tab-pane fade">
					<div id="sentdiv">
						@if(count($sent))
							@foreach($sent as $res)
								<?php $from_to_names = json_decode($res->from_to_names);  ?>
								<div class="clearfix" id="request_div_{{ !empty($res->id)?$res->id:0 }}">	
									<button type="button" class="close request" data-dismiss="alert" flag='d'reqid='{{ !empty($res->id)?$res->id:0 }}' title="Close">×</button>
									<div class="search_thumbnail right-caption">
										<div class="search_image">
											{!! Helper::Images( $res->logo ,config('constants.PHOTO_PATH.'.$res->logo_type),array('height'=>90,'width'=>90,'class'=>'img-circle img-border') )!!}
										</div>
										<div class="search_caption">
											<h3>{{ !empty($from_to_names->from_name)?$from_to_names->from_name:'' }}</h3>
                                            <ul class="t_tags" style="color: #999">
                                            	<li>To: <span class="green">{{ !empty($from_to_names->to_name)?$from_to_names->to_name:'' }}</span></li>
                                                <li>Sport: <span class="green">{{ !empty($from_to_names->sport_name)?$from_to_names->sport_name:'' }}</span></li>
                                            </ul>
											<div class="list_display">
												<p>{!! HTML::decode(str_replace("REQURL|",URL::to('/'),$res->message_sent)) !!}</p>
											</div>
											<span class="box-action sportsjun-forms">
					                            <a href="#" class="btn-link btn-secondary-link btn-secondary-border request" flag='c' reqid='{{ !empty($res->id)?$res->id:0 }}'>Cancel</a>
					                        </span>
										</div>
									</div>
								</div>
							@endforeach
						@else
							No Records.
						@endif
					</div>
					@if($sent_count>count($sent)) 
						<div  id="viewmorediv">
							<div id="viewmorebutton" class="btn btn-view">{{ trans('message.view_more') }}</div>
						</div>
					@endif						
				</div>
					
			</div>
		</div>
	</div>
    	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#offset1").val({{$offset1}});
		$("#offset2").val({{$offset2}});
		if ($("#viewmorediv,#viewmorediv1").length) {
			$('#viewmorebutton,#viewmorebutton1').on("click", function(e) {
				var params = { limit:{{$limit}}, offset1:$("#offset1").val(), offset2:$("#offset2").val(), id:'{{Request::segment(3)}}', flag: $('.nav-tabs .active').text()};
				<?php if($controller == 'TeamController') { ?>
					viewMore(params, '{{URL('team/getviewmoreteamrequests')}}');
				<?php } else { ?>
					viewMore(params, '{{URL('sport/getviewmoreplayerrequests')}}');
				<?php } ?>
			});
		}
	});

	$(document).on( 'shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
	   var act_tab = $('.nav-tabs .active').text();
	   if(act_tab == 'Sent')
	   {
	   		$("#receivediv").removeClass('viewmoreclass');
	   		$("#sentdiv").addClass('viewmoreclass');
	   }
	   else
	   {
	   		$("#sentdiv").removeClass('viewmoreclass');
	   		$("#receivediv").addClass('viewmoreclass');
	   }
	})	

	//function for accept and reject a request
	$(document.body).on('click', '.request' ,function(){
		var reqid = $(this).attr('reqid');
		$.post(base_url+'/team/updateplayerrequest',{'request_id':$(this).attr('reqid'),'flag':$(this).attr('flag')},function(response,status){
			if(status == 'success' && response.result == 'success')
			{
				$(".notfication_bubble").html(response.notifications_count);
				$("#request_div_"+reqid).remove();
			}
		});
	});
</script>