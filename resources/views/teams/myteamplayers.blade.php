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
				<div class="team_players_sj_title">{{ (!empty($player['user']['name'])?$player['user']['name']:'NA') }}</div>
				<div class="teamplayer_position">{{ !empty($player['role'])?$player['role']:'NA' }}</div>
			</div>
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