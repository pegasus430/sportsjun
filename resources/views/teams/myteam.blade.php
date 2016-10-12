@extends(Auth::user() ? 'layouts.app' : 'home.layout')
@section('content')
@include ('teams._leftmenu')
<div id="content-team" class="col-sm-10">
	<div class="row">
	<div class="col-sm-9">
				@if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
				@elseif (session('error_msg'))
				<div class="alert alert-danger">
					{{ session('error_msg')  }}

				</div>
				@endif
				
				<div class="row">
					<div class="team_managers_row clearfix">
						<div class="col-md-12">

						@include('teams.share')
							<div class="row players_row">
								<div class="col-lg-12">
									<div class="pull-left"><h4>Members</h4></div>
									<div class="players_row_right">
										<div class="text-center">
											<span class="switch-head">Looking For Players </span>
											@if($logged_in_user_role == 'owner' || $logged_in_user_role == 'manager')
											<input type="checkbox" class="switch-class" name="player_available" id="player_available" {{ (!empty($teams[0]['player_available']) && $teams[0]['player_available'] == 1)?'checked':'' }}>
											@else
											<span class="switch_but_show">{{ (!empty($teams[0]['player_available']) && $teams[0]['player_available'] == 1)?'YES':'NO' }}</span>
											@endif
											&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
											<span class="switch-head">Avaliable For Matches</span>
											@if($logged_in_user_role == 'owner' || $logged_in_user_role == 'manager')
											<input type="checkbox" class="switch-class" name="team_available"  id="team_available" {{ (!empty($teams[0]['team_available']) && $teams[0]['team_available'] == 1)?'checked':'' }}>
											@else
											<span class="switch_but_show">{{ (!empty($teams[0]['team_available']) && $teams[0]['team_available'] == 1)?'YES':'NO' }}</span>
											@endif
										</div>
									</div>
								</div>
							</div>
						</div>
                        <div class="clearfix"></div>

						@if(!empty($team_owners_managers))
						@foreach($team_owners_managers as $own)
						<div class="col-xs-6">
							<div class="player_profile">
								<div class="player_img">
								 {!! Helper::Images( (count($own['user']['photos'])?$own['user']['photos'][0]['url']:''),'user_profile',array('height'=>100,'width'=>100 ) )
                               !!}
							 </div>
								<div class="player_info">
									<div class="player_profile_title"><a href="{{ url('/editsportprofile').'/'.(!empty($own['user_id'])?$own['user_id']:0) }}">{{ !empty($own['user']['name'])?$own['user']['name']:'NA' }}</a></div>
									@if(($logged_in_user_role == 'owner' || $logged_in_user_role == 'manager') && $own['role'] == 'manager'	)
									<div>
										<div class="dropdown">
											<a href="#" data-toggle="dropdown" class="dropdown-toggle player_position">{{ !empty($own['role'])?$own['role']:'NA' }}&nbsp;<span class="glyphicon glyphicon-menu-down font-small"></span></a>
											<ul class="dropdown-menu">
												<li><a href="{{  URL::to('/team/removeteammanager/'.(!empty($own['team_id'])?$own['team_id']:0).'/'.(!empty($own['user_id'])?$own['user_id']:0)) }}">Remove Team Manager</a></li>
												<li><a href="{{  URL::to('/team/removefromteam/'.(!empty($own['team_id'])?$own['team_id']:0).'/'.(!empty($own['user_id'])?$own['user_id']:0)) }}">Remove From Team</a></li>
												<li><a href="{{  URL::to('/team/maketeamcaptain/'.(!empty($own['team_id'])?$own['team_id']:0).'/'.(!empty($own['user_id'])?$own['user_id']:0)) }}">Make Team Captain</a></li>
												<li><a href="{{  URL::to('/team/maketeamvicecaptain/'.(!empty($own['team_id'])?$own['team_id']:0).'/'.(!empty($own['user_id'])?$own['user_id']:0)) }}">Make Team Vice-Captain</a></li>
												<li><a href="{{  URL::to('/team/maketeamcoach/'.(!empty($own['team_id'])?$own['team_id']:0).'/'.(!empty($own['user_id'])?$own['user_id']:0)) }}">Make Coach</a></li>
												<li><a href="{{  URL::to('/team/maketeamphysio/'.(!empty($own['team_id'])?$own['team_id']:0).'/'.(!empty($own['user_id'])?$own['user_id']:0)) }}">Make Physio</a></li>
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
						<div class="col-xs-12">
							<div class="pull-right">
								<button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#transfer-owner-modal" data-team-id="{{$team_id}}"><i class="fa fa-exchange"></i> Transfer ownership</button>
							</div>
						</div>


					</div>
				</div>
				<div class="row players_row">
					<div class="col-lg-12">
						<div class="players_row_left"><h4>Players ({{ count($team_players) }})</h4></div>
						@if($logged_in_user_role == 'owner' || $logged_in_user_role == 'manager')
						<div class="players_row_right">
							<label>Filter By</label>
							<div class="dropdown filter_dropdown pull-right">
								<a href="#" data-toggle="dropdown" class="dropdown-toggle" id="statusdd">Status&nbsp;<b class="caret"></b></a>
								<ul class="dropdown-menu" id="statusul">
									<li id="all"><a href="javascript:void(0)">All</a></li>
									<li id="accepted"><a href="javascript:void(0)">Accepted</a></li>
									<li id="pending"><a href="javascript:void(0)">Pending</a></li>
								</ul>
							</div>
						</div>
						@endif
					</div>
				</div>
				<div class="row" id="team_players_div">
					@if(!empty($team_players))
						@foreach($team_players as $player)
						<div class="col-lg-3 col-md-6">
							<div class="team_players_sj">
								@if($logged_in_user_role == 'owner' || $logged_in_user_role == 'manager')
								<div class="team_players_actions">
									<div class="{{ (!empty($player['status'])?(($player['status'] == 'pending')?'player_inactive':($player['status'] == 'accepted'?'player_active':'player_rejected')):'player_inactive') }}">
										{{ (!empty($player['status'])?(($player['status'] == 'pending')?'P':($player['status'] == 'accepted'?'A':'R')):'P') }}</div>


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
													<li><a href="{{  URL::to('/team/maketeamcoach/'.(!empty($player['team_id'])?$player['team_id']:0).'/'.(!empty($player['user_id'])?$player['user_id']:0)) }}">Make Coach</a></li>
													<li><a href="{{  URL::to('/team/maketeamphysio/'.(!empty($player['team_id'])?$player['team_id']:0).'/'.(!empty($player['user_id'])?$player['user_id']:0)) }}">Make Physio</a></li>
												</ul>
											</div>
										</div>
									</div>
								</div>
								@endif
								<div class="team_player_sj_img"><!--<img src="{{ url('/uploads/user_profile/'.(count($player['user']['photos'])?$player['user']['photos'][0]['url']:'')) }}"  onerror="this.onerror=null;this.src='{{ asset('/images/default-profile-pic.jpg') }}';" height="90" width="90">-->
								{!! Helper::Images((count($player['user']['photos'])?$player['user']['photos'][0]['url']:''),'user_profile',array('height'=>90,'width'=>90) )!!}
								</div>
								<div class="teamplayer_profile_sj">
									<a href="{{ url('/editsportprofile').'/'.(!empty($player['user_id'])?$player['user_id']:0) }}" class="team_players_sj_title">{{ (!empty($player['user']['name'])?$player['user']['name']:'NA') }}</a>
									<div class="teamplayer_position">{{ !empty($player['role'])?$player['role']:'NA' }}</div>
								</div>

										<!-- add email -->
							@if($logged_in_user_role == 'owner' || $logged_in_user_role == 'manager')
									@if(empty(Helper::getUserDetails($player['user_id'])->email))
		<span class='add_email'><a href='javascript:void(0)' onclick="addEmail(this)" user_id="{{$player['user_id']}}" team_id=" {{$team_id}} ">Add Player Email</a></span>
									@endif
							@endif
							</div>
						</div>
						@endforeach
					@else
				<div class="col-md-12">
					<br />
					<p class="lead label label-default">No players.</p>
					<br />
					<br />

				</div>
			@endif
			<div class="addTeamPlayer">
			</div>
		</div>
	</div>
	<?php $role = Helper::isTeamOwnerorcaptain((!empty(Request::segment(3))?Request::segment(3):0),(isset(Auth::user()->id)?Auth::user()->id:0));
        if($role) { ?>
	<div class="col-sm-3 col-xs-12" id="sidebar-right">

        <div class="tournament_profile">
            <div class="panel panel-default">
                <div class="panel-body">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="active"><a href="#addplayer" data-toggle="tab" aria-expanded="true">Add Player</a></li>
                        <li class=""><a href="#inviteplayer" data-toggle="tab" aria-expanded="false">Invite Player</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="addplayer">

                            @include('widgets.teamplayer')
                        </div>
                        <div class="tab-pane fade" id="inviteplayer">
						<input type="hidden" name="teamid" value="{{ $team_id }}" >
                            @include('widgets.inviteplayer')
                        </div>
                    </div>
                </div>
            </div>
            </div>
        <div id="suggested_players"> </div>
        <br/>
        <div id="suggested_tournaments"></div>

	</div>
	<?php } ?>
	</div>
</div>
@include('teams.modal.change_ownership')
@include ('widgets.teamspopup')
<script type="text/javascript">
	$(window).ready(function(){
		$("#hid_flag").val('');
		$("#hid_val").val('');
		<?php $role = Helper::isTeamOwnerorcaptain((!empty(Request::segment(3))?Request::segment(3):0),(isset(Auth::user()->id)?Auth::user()->id:0));
        if($role) { ?>
		suggestedWidget('players','{{ !empty(Request::segment(3))?Request::segment(3):'' }}','{{ !empty($sport_id)?$sport_id:'' }}','team_to_player','');
		suggestedWidget('tournaments','{{ !empty(Request::segment(3))?Request::segment(3):'' }}','{{ !empty($sport_id)?$sport_id:'' }}','team_to_tournament','');
		<?php } ?>
	});
    //bootstrap code
    $("#player_available,#team_available").bootstrapSwitch();
    //player availability
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
	//team availability
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
			$(".players_row_left").html("<h4>Players ("+$('.player_active').length+")</h4>");
		}
		else if(status == 'Pending')
		{
			$('.player_active').parent().parent().parent().css("display","none");
			$('.player_inactive').parent().parent().parent().css("display","block");
			$(".players_row_left").html("<h4>Players ("+$('.player_inactive').length+")</h4>");
		}
		else
		{
			$('.player_active').parent().parent().parent().css("display","block");
			$('.player_inactive').parent().parent().parent().css("display","block");
			$(".players_row_left").html("<h4>Players ("+($('.player_inactive').length+$('.player_active').length)+")</h4>");
		}
	});



</script>

<script>
	//add email to player
		var oldContent='';
		function addEmail(that){
				var parentDiv=$(that).parents('.add_email');
				    oldContent=parentDiv.html();
				var user_id=$(that).attr('user_id');
				var team_id=$(that).attr('team_id');

		var newContent= "<input type='email' onblur='return saveEmail(this)' user_id='"+user_id+"' style='width:70%' team_id='"+team_id+"'  >";

				parentDiv.html(newContent);
		}

		function saveEmail(that){
			var parentDiv=$(that).parents('.add_email');				   
			var user_id=$(that).attr('user_id');
			var email=$(that).val();
			var team_id=$(that).attr('team_id');

			var data={
				user_id:user_id,
				email:email,
				team_id:team_id
			}

			if(email==''){
				parentDiv.html(oldContent);
				return false;
			}

			$.confirm({
				title:"Alert!",
				content:"Add Email?",
				confirm:function(){
					$.ajax({
						url:'/addemailtoplayer',
						data:data,
						type:'post',
						dataType:'json',
						success:function(response){
							if(response.error=='no'){
								parentDiv.addClass('green');
								parentDiv.html(response.message);

								setTimeout(function(){
									parentDiv.removeClass('green');
									parentDiv.html('');
								}, 3000)
							}
							else if(response.error=='yes'){
								parentDiv.addClass('red');
								parentDiv.html(response.message);

								setTimeout(function(){
									parentDiv.removeClass('red');
									parentDiv.html(oldContent);
								}, 3000)
							}
							
						},
						error:function(){
							parentDiv.html(oldContent);
						}
					})
				},
				cancel:function(){
					parentDiv.html(oldContent);
				}
			})
		}

		
</script>
@endsection

