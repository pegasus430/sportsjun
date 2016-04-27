<div class="col-sm-2">
	<div class="row">
	<div class="team_view">
		{!! Helper::Images(!empty($left_menu_data['logo'])?$left_menu_data['logo']:'',!empty($left_menu_data['path'])?$left_menu_data['path']:'',array('height'=>100,'width'=>100) )!!}
		<h1>{{ $left_menu_data['name'] or 'Tournament'}}</h1>
        @if($left_menu_data['is_owner'])
        <a href="{{ route('tournaments.edit',[$left_menu_data['id']])}}" class="tvp_edit">
          <span class="fa fa-pencil" title="Edit"></span>
        </a>
        @endif
		<div class="sports_text">{{ $left_menu_data['sports_matchtype'] or '' }}</div>
		<div class="more desc">{{ $left_menu_data['description'] or 'Description' }}</div>
		<?php $follow_unfollow = Helper::checkFollowUnfollow(Auth::user()->id,'TOURNAMENT',$action_id);?>
	   <div class="follow_unfollow_tournament" id="follow_unfollow_tournament_{{$action_id}}" uid="{{$action_id}}" val="TOURNAMENT" flag="{{ !empty($follow_unfollow)?0:1 }}"><a href="#" id="follow_unfollow_tournament_a_{{$action_id}}" class="{{ !empty($follow_unfollow)?'sj_unfollow':'sj_follow' }}"><span id="follow_unfollow_tournament_span_{{$action_id}}"><i class="{{ !empty($follow_unfollow)?'fa fa-remove':'fa fa-check' }}"></i>{{ !empty($follow_unfollow)?'Unfollow':'Follow' }}</span></a></div> 
	</div>
	<ul class="nav sidemenu_nav leftmenu-icon" id="side-menu">
		<!--<li><a class="sidemenu_1" href="{{ url('tournaments') }}"><span class="ico ico-info"></span> Info</a></li>-->
		 <li><a class="sidemenu_1" href="{{ url('gettournamentdetails/'.$tournament_id) }}"><span class="ico ico-info"></span> Info</a></li>
		@if($lef_menu_condition=='display_gallery')
		<li><a class="sidemenu_2" href="{{ url('user/album/show').'/tournaments'.'/0'.'/'.$action_id }}"><span class="ico ico-media-gallery"></span> Media Gallery</a></li>
		@endif
                @if($tournament_type=='league' || $tournament_type=='multistage')
                    <li><a class="sidemenu_3" href="{{ url('tournaments/groups').'/'.$action_id.'/group'}}"><span class="ico ico-group-stage"></span> Group Stage</a></li>
                @endif
                @if($tournament_type=='knockout' || $tournament_type=='multistage')
                    <li><a class="sidemenu_4" href="{{ url('tournaments/groups').'/'.$action_id.'/final'}}"><span class="ico ico-final-stage"></span> Final Stage</a></li>
                @endif
	</ul>
</div>
</div>