@extends('admin.layouts.app')
@section('content')
@include ('admin.teams._leftmenu')
<?php //echo '<pre>';print_r($teams);?>
<div class="col_middle bg_white_new">
	<div class="col-lg-9 leftsidebar">
	<div class="container-fluid">
		<div class="row">
			<div>
				@if (session('status'))
				<div class="alert alert-success">
					{{ session('status') }}
				</div>
				@elseif (session('error_msg'))
				<div class="alert alert-danger">
					{{ session('error_msg') }}
				</div>
				@endif
				<div class="row team_managers_row">
					<div>
						<h1>Members</h1>
						<div>
						    Looking For Players <input type="checkbox" name="player_available" id="player_available" {{ (!empty($teams[0]['player_available']) && $teams[0]['player_available'] == 1)?'checked':'' }}> 
						</div>
						<div>
						    Avaliable For Matches <input type="checkbox"  name="team_available"  id="team_available" {{ (!empty($teams[0]['team_available']) && $teams[0]['team_available'] == 1)?'checked':'' }}> 
						</div>
						@if(!empty($team_owners_managers))
						@foreach($team_owners_managers as $own)
						<div class="col-lg-4 col-md-6 col-sm-6">
							<div class="player_profile">
								<div class="player_img"><img src="{{ url('/uploads/user_profile/'.( count($own['user']['photos'])?$own['user']['photos'][0]['url']:'')) }}"  onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';"  height="90" width="90"></div>
								<div class="player_info">
									<div class="player_profile_title">{{ !empty($own['user']['name'])?$own['user']['name']:'NA' }}</div>
									@if(($logged_in_user_role == 'owner' || $logged_in_user_role == 'manager') && $own['role'] == 'manager'	)
									<div>
										<div class="dropdown">
											<a href="#" data-toggle="dropdown" class="dropdown-toggle player_position">{{ !empty($own['role'])?$own['role']:'NA' }}&nbsp;<span class="glyphicon glyphicon-menu-down font-small"></span></a>
											<ul class="dropdown-menu">
												<li><a href="{{  URL::to('/team/removeteammanager/'.(!empty($own['team_id'])?$own['team_id']:0).'/'.(!empty($own['user_id'])?$own['user_id']:0)) }}">Remove Team Manager</a></li>
												<li><a href="{{  URL::to('/team/removefromteam/'.(!empty($own['team_id'])?$own['team_id']:0).'/'.(!empty($own['user_id'])?$own['user_id']:0)) }}">Remove From Team</a></li>
												<li><a href="{{  URL::to('/team/maketeamcaptain/'.(!empty($own['team_id'])?$own['team_id']:0).'/'.(!empty($own['user_id'])?$own['user_id']:0)) }}">Make Team Captain</a></li>
												<li><a href="{{  URL::to('/team/maketeamvicecaptain/'.(!empty($own['team_id'])?$own['team_id']:0).'/'.(!empty($own['user_id'])?$own['user_id']:0)) }}">Make Team Vice-Captain</a></li>
											</ul>
										</div>
									</div>
									@else
									<div class="player_position">{{ !empty($own['role'])?$own['role']:'NA' }}</div>
									@endif
								</div>
							</div>
						</div>
						@endforeach
						@endif
					</div>
				</div>
				<div class="row players_row">
					<div class="col-lg-12">
						<div class="players_row_left"><h1>Players({{ count($team_players) }})</h1></div>
						@if($logged_in_user_role == 'owner' || $logged_in_user_role == 'manager')
						<div class="players_row_right">
							<label>Filter By</label>
							<div class="dropdown filter_dropdown pull-right">
								<a href="#" data-toggle="dropdown" class="dropdown-toggle" id="statusdd">Status&nbsp;<b class="caret"></b></a>
								<ul class="dropdown-menu" id="statusul">
									<li id="all"><a href="#">All</a></li>
									<li id="accepted"><a href="#">Accepted</a></li>
									<li id="pending"><a href="#">Pending</a></li>
								</ul>
							</div>
						</div>
						@endif
					</div>
				</div>
				<div class="row">
					@if(!empty($team_players))
						@foreach($team_players as $player)
						<div class="col-lg-3 col-md-6">
							<div class="team_players_sj">
								@if($logged_in_user_role == 'owner' || $logged_in_user_role == 'manager')
								<div class="team_players_actions">
									<div class="{{ (!empty($player['status']) && $player['status'] == 'pending')?'player_inactive':'player_active' }}"></div>
									<div class="player_glyph_action">
										<div class="bs-example">
											<div class="dropdown">
												<a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="glyphicon glyphicon-option-vertical"></span></a>
												<ul class="dropdown-menu pull-right">
													<li><a href="{{  URL::to('/team/maketeammanager/'.(!empty($player['team_id'])?$player['team_id']:0).'/'.(!empty($player['user_id'])?$player['user_id']:0)) }}">Make Team Manager</a></li>
													<li><a href="{{  URL::to('/team/removefromteam/'.(!empty($player['team_id'])?$player['team_id']:0).'/'.(!empty($player['user_id'])?$player['user_id']:0)) }}">Remove from Team</a></li>
													@if(!empty($player['status']) && $player['status'] == 'pending')
													<li><a href="{{  URL::to('/team/sendinvitereminder/'.(!empty($player['team_id'])?$player['team_id']:0).'/'.(!empty($player['user_id'])?$player['user_id']:0)) }}">Send Invite Reminder</a></li>
													@endif
													<li><a href="{{  URL::to('/team/maketeamcaptain/'.(!empty($player['team_id'])?$player['team_id']:0).'/'.(!empty($player['user_id'])?$player['user_id']:0)) }}">Make Team Captain</a></li>
													<li><a href="{{  URL::to('/team/maketeamvicecaptain/'.(!empty($player['team_id'])?$player['team_id']:0).'/'.(!empty($player['user_id'])?$player['user_id']:0)) }}">Make Team Vice-Captain</a></li>
												</ul>
											</div>
										</div>
									</div>
								</div>
								@endif
								<div class="team_player_sj_img"><img src="{{ url('/uploads/user_profile/'.(count($player['user']['photos'])?$player['user']['photos'][0]['url']:'')) }}"  onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" height="90" width="90"></div>
								<div class="teamplayer_profile_sj">
									<div class="team_players_sj_title">{{ (!empty($player['user']['name'])?$player['user']['name']:'NA') }}</div>
									<div class="teamplayer_position">{{ !empty($player['role'])?$player['role']:'NA' }}</div>
								</div>
							</div>
						</div>
						@endforeach
					@else
						<div class="col-lg-4 col-md-6">
							<div class="row">
								No players.
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
	</div>
	<div class="col-lg-3 rightsidebar">
		@include('widgets.teamplayer')
	</div>
</div>
<script type="text/javascript">
    //bootstrap code
    $("#player_available,#team_available").bootstrapSwitch();
	$("#player_available").on('switchChange.bootstrapSwitch', function(event, state) {
        $.post(base_url+'/team/updateavailability',{'team_id':'{{Request::segment(3)}}','flag':'player_available','update_value':state},function(response,status)
        {
        	if(response.return_val == 1)
        	{
        		$("#player_available").attr('checked',true);
        	}
           	else
           	{
           		$("#player_available").attr('checked',false);
           	}
        });		
	});	
	$("#team_available").on('switchChange.bootstrapSwitch', function(event, state) {
 		$.post(base_url+'/team/updateavailability',{'team_id':'{{Request::segment(3)}}','flag':'team_available','update_value':state},function(response,status)
        {
        	if(response.return_val == 1)
        	{
        		$("#team_available").attr('checked',true);
        	}
           	else
           	{
           		$("#team_available").attr('checked',false);
           	}
        });	
	});
	//status change filter
	$("#statusul li").click(function(){
		var status = $(this).text();
		$("#statusdd").html(status+'&nbsp;<b class="caret"></b>');
		if(status == 'Accepted')
		{
			$('.player_active').parent().parent().parent().css("display","block");
			$('.player_inactive').parent().parent().parent().css("display","none");
			$(".players_row_left").html("<h1>Players("+$('.player_active').length+")</h1>");
		}
		else if(status == 'Pending')
		{
			$('.player_active').parent().parent().parent().css("display","none");
			$('.player_inactive').parent().parent().parent().css("display","block");
			$(".players_row_left").html("<h1>Players("+$('.player_inactive').length+")</h1>");
		}
		else
		{
			$('.player_active').parent().parent().parent().css("display","block");
			$('.player_inactive').parent().parent().parent().css("display","block");
			$(".players_row_left").html("<h1>Players("+($('.player_inactive').length+$('.player_active').length)+")</h1>");
		}
	});
</script>
@endsection
