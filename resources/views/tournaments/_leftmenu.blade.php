<?php

if(!Auth::user())$check_user='viewpublic';          //if user is public, redirects to the public link
else $check_user='';
?>
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
            <?php if(!in_array($tournament_id,$left_menu_data['exist_array']) && (!empty($left_menu_data['sub_tournament_details']['end_date'] && $left_menu_data['sub_tournament_details']['end_date']!='0000-00-00')?strtotime($left_menu_data['sub_tournament_details']['end_date']) >= strtotime(date(config('constants.DATE_FORMAT.DB_STORE_DATE_FORMAT'))):strtotime($left_menu_data['sub_tournament_details']['start_date']) >= strtotime(date(config('constants.DATE_FORMAT.DB_STORE_DATE_FORMAT'))))) {?>
            <div class="sb_join_tournament_main">
                <a href="javascript:void(0);" onclick="SJ.TOURNAMENT.joinTournament({{isset(Auth::user()->id)?Auth::user()->id:0}},{{$left_menu_data['sub_tournament_details']['id']}},{{$left_menu_data['sub_tournament_details']['sports_id']}},'{{!empty($left_menu_data['sub_tournament_details']['schedule_type'])?(($left_menu_data['sub_tournament_details']['schedule_type']=='individual')?'PLAYER_TO_TOURNAMENT':'TEAM_TO_TOURNAMENT'):''}}');" class="sj_add_but">
                    <span><i class="fa fa-check"></i>Join Tournament</span>
                </a>
            </div>
            <?php } ?>
            <?php $follow_unfollow = Helper::checkFollowUnfollow(isset(Auth::user()->id)?Auth::user()->id:0,'TOURNAMENT',$action_id);?>
            <div class="follow_unfollow_tournament" id="follow_unfollow_tournament_{{$action_id}}" uid="{{$action_id}}" val="TOURNAMENT" flag="{{ !empty($follow_unfollow)?0:1 }}">
                <a href="javascript:void(0);" id="follow_unfollow_tournament_a_{{$action_id}}" class="{{ !empty($follow_unfollow)?'sj_unfollow':'sj_follow' }}">
                    <span id="follow_unfollow_tournament_span_{{$action_id}}"><i class="{{ !empty($follow_unfollow)?'fa fa-remove':'fa fa-check' }}"></i>{{ !empty($follow_unfollow)?'Unfollow':'Follow' }}</span>
                </a>
            </div>
        </div>
        <ul class="nav sidemenu_nav leftmenu-icon" id="side-menu">
        <!--<li><a class="sidemenu_1" href="{{ url('tournaments') }}"><span class="ico ico-info"></span> Info</a></li>-->
            <li><a class="sidemenu_1" href="{{ url($check_user.'/gettournamentdetails/'.$tournament_id) }}"><span class="ico ico-info"></span> Info</a></li>
            @if($lef_menu_condition=='display_gallery')
                <li><a class="sidemenu_2" href="{{ url($check_user.'/user/album/show').'/tournaments'.'/0'.'/'.$action_id }}"><span class="ico ico-media-gallery"></span> Media Gallery</a></li>
            @endif
            @if($tournament_type=='league' || $tournament_type=='multistage')
                <li><a class="sidemenu_3" href="{{ url($check_user.'/tournaments/groups').'/'.$action_id.'/group'}}"><span class="ico ico-group-stage"></span> Group Stage</a></li>
            @endif
            @if($tournament_type=='knockout' || $tournament_type=='multistage')
                <li><a class="sidemenu_4" href="{{ url($check_user.'/tournaments/groups').'/'.$action_id.'/final'}}"><span class="ico ico-final-stage"></span> Final Stage</a></li>
            @endif

            @if(in_array($left_menu_data['sub_tournament_details']['sports_id'], [1,4,6,11,13]))
                <li><a class="sidemenu_5" href="{{ url($check_user.'/tournaments/groups').'/'.$action_id.'/player_standing'}}"><span class="ico ico-user"></span> Player Standing</a></li>
            @endif

    @if(!empty($tournamentDetails))

        @if(Helper::isTournamentOwner($tournamentDetails[0]['manager_id'],$tournamentDetails[0]['tournament_parent_id']))

            @if(in_array($left_menu_data['sub_tournament_details']['sports_id'], [5,7,14, 17]))
                <li><a class="" href="javascript:void(0)" data-toggle="modal" data-target="#settings"  onclick="getTournamentSettings({{$tournamentDetails[0]['id']}})"><span class="ico ico-settings"></span> Settings</a></li>
            @endif

            @include('tournaments.settings')
        @endif
    @endif
        </ul>
    </div>
</div>
@include ('widgets.teamspopup')